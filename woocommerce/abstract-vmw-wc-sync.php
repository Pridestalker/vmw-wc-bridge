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

        $productDbo = Vmw_Wc_Product_Dbo::create();

        $productDbo->setData('name', $product->get_title());
        $productDbo->setData('price', $product->get_regular_price());
        $productDbo->setData('description', $product->get_description());
        $productDbo->setData('short_description', $product->get_short_description());
        $productDbo->setData('thumbnail', static::getThumbnailData($post_id));
        $productDbo->setData('category', static::getProductCategory($post_id));
        static::setCountry($productDbo, $product);
        static::setRegion($productDbo, $product);
        static::setGrapes($productDbo, $product);
        static::setAlcohol($productDbo, $product);
        static::setYear($productDbo, $product);
        static::setClassification($productDbo, $product);
        static::setDomain($productDbo, $product);
        static::setContents($productDbo, $product);

        var_dump($productDbo->getData());
        die;

        return $productDbo->getData();
    }

    /**
     * @param Vmw_Wc_Product_Dbo $productDbo
     * @param WC_Product $product
     */
    protected static function setCountry($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_country') !== 'none') {
            static::setDboData(
                $productDbo,
                'country',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_country')
                )
            );
        }
    }

    /**
     * @param Vmw_Wc_Product_Dbo $productDbo
     * @param WC_Product $product
     */
    protected static function setRegion($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_region') !== 'none') {
            static::setDboData(
                $productDbo,
                'region',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_region')
                )
            );
        }
    }

    protected static function setGrapes($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_grapes') !== 'none') {
            static::setDboData(
                $productDbo,
                'grapes',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_grapes')
                )
            );
        }
    }

    protected static function setAlcohol($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_alcohol') !== 'none') {
            static::setDboData(
                $productDbo,
                'alcohol',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_alcohol')
                )
            );
        }
    }

    protected static function setYear($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_year') !== 'none') {
            static::setDboData(
                $productDbo,
                'year',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_year')
                )
            );
        }
    }

    protected static function setClassification($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_classification') !== 'none') {
            static::setDboData(
                $productDbo,
                'classification',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_classification')
                )
            );
        }
    }

    protected static function setDomain($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_domain') !== 'none') {
            static::setDboData(
                $productDbo,
                'domain',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_domain')
                )
            );
        }
    }

    protected static function setContents($productDbo, $product)
    {
        if (static::getTitan()->getOption('vmw_contents') !== 'none') {
            static::setDboData(
                $productDbo,
                'contents',
                $product->get_attribute(
                    static::getTitan()->getOption('vmw_contents')
                )
            );
        }
    }

    /**
     * @param Vmw_Wc_Product_Dbo $dbo
     * @param string $key
     * @param string $data
     */
    private static function setDboData($dbo, $key, $data)
    {
        $dbo->setData($key, $data);
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
