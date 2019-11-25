<?php

abstract class Vmw_Wc_Sync
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $post_id;

    /**
     * @var array
     */
    protected $data;

	/**
	 * @var TitanFramework|null
	 */
    protected static $titan = null;

    public function __construct($post_id)
    {
        $this->client  = new \GuzzleHttp\Client(
            [
                'headers'   => [
                    'Authorization' => static::getAuthenticationHeader(),
                ],
            ]
        );

        $this->post_id = $post_id;
    }

    protected function setData()
    {
        $this->data = static::fetchData($this->post_id);
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function makeRequest()
    {
        return $this->client->request(
            'POST',
            static::getRoute(),
            [
                'multipart' => $this->data
            ]
        );
    }

    protected function makeUpdateRequest()
    {
        return $this->client->request(
            'POST',
            static::getUpdateRoute($this->post_id),
            [
                'multipart' => $this->data
            ]
        );
    }

    public static function create($post_id)
    {
        return new static($post_id);
    }

    public static function hasSyncEnabled($post_id)
    {
        return get_post_meta($post_id, '_product_snyc_mvw', true) === 'yes';
    }

    public static function getProductCategory($post_id)
    {
        return get_post_meta($post_id, '_product_sync_category', true);
    }

    private static function fetchData($post_id)
    {
        $product = static::getProduct($post_id);

        $data = [
	        [
		        'name'      => 'name',
		        'contents'  => $product->get_title(),
	        ],
	        [
		        'name'      => 'price',
		        'contents'  => $product->get_regular_price(),
	        ],
	        [
		        'name'      => 'description',
		        'contents'  => $product->get_description(),
	        ],
	        [
		        'name'      => 'short_description',
		        'contents'  => $product->get_short_description(),
	        ],
	        [
		        'name'      => 'thumbnail',
		        'contents'  => static::getThumbnailData($post_id),
	        ],
	        [
		        'name'      => 'category',
		        'contents'  => static::getProductCategory($post_id),
	        ],
        ];


        return $data;
    }

    private static function getThumbnailData($post_id)
    {
        $thumbnail = get_post_thumbnail_id($post_id);
        if (!$thumbnail) {
            wp_die(__('No thumbnail supplied. Aborting.', 'vmw-wc'));
        }
        $thumbnail = get_attached_file($thumbnail);
        return fopen($thumbnail, 'rb');
    }

    public static function getProduct($post_id)
    {
        return wc_get_product($post_id);
    }

    public static function setPostSku($post_id, string $sku)
    {
        add_post_meta($post_id, '_vmw_product_sku', $sku);
    }

    public static function setPostId($post_id, $vmw_id)
    {
        add_post_meta($post_id, '_vmw_product_id', $vmw_id);
    }

    public static function getPostSku($post_id)
    {
        return get_post_meta($post_id, '_vmw_product_sku', true);
    }

    public static function getPostId($post_id)
    {
        return get_post_meta($post_id, '_vmw_product_id', true);
    }

    public static function hasPostSku($post_id)
    {
        return static::getPostSku($post_id) !== '';
    }

    public static function hasPostId($post_id)
    {
        return static::getPostId($post_id) !== '';
    }

    public static function getToken()
    {
        return static::getTitan()
                     ->getOption('vmw_key');
    }

    public static function hasToken()
    {
        return static::getToken() !== false;
    }

    public static function getBaseUrl()
    {
        return static::getTitan()
                     ->getOption('vmw_base_url');
    }

    public static function hasBaseUrl()
    {
        return static::getBaseUrl() !== false;
    }

    public static function getRoute()
    {
        if (!static::hasBaseUrl()) {
            wp_die(__('No URL passed to base Vindmijnwijn.nl URL. Aborting.', 'vmw-wc'));
        }

        return sprintf(
            '%sapi/%s/products',
            trailingslashit(static::getBaseUrl()),
            static::apiVersion()
        );
    }

    public static function getUpdateRoute($post_id)
    {
        if (!static::hasBaseUrl()) {
            wp_die(__('No URL passed to base Vindmijnwijn.nl URL. Aborting.', 'vmw-wc'));
        }

        return sprintf(
            '%sapi/%s/product/%s',
            trailingslashit(static::getBaseUrl()),
            static::apiVersion(),
            static::getPostId($post_id)
        );
    }

    public static function apiVersion(): string
    {
        return 'v3';
    }

    public static function getAuthenticationHeader()
    {
        if (!static::hasToken()) {
            wp_die(__('Incorrect data passed to Vindmijnwijn.nl, disable product sync to continue.', 'vmw-wc'));
        }

        return 'Bearer ' . static::getToken();
    }

    /**
     * @return TitanFramework
     */
    public static function getTitan()
    {
        if (null === static::$titan) {
            static::$titan = TitanFramework::getInstance('vmw-wc');
        }

        return static::$titan;
    }
}
