<?php

use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Vmw_Wc_Sync_Product
{
    /**
     * Holds the plugin name of the current plugin.
     *
     * @var string $plugin_name
     */
    protected $plugin_name;

    /**
     * Holds the current plugin version.
     *
     * @var string $plugin_version
     */
    protected $plugin_version;


    /**
     * Vmw_Wc_Settings constructor.
     *
     * @param string $plugin_name The plugin name.
     * @param string $plugin_version The plugin version.
     */
    public function __construct($plugin_name, $plugin_version)
    {
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
    }

    public static function sync_product($post_id)
    {
        $sync_product = get_post_meta($post_id, '_product_sync_vmw', true) === 'yes';
        $product_category = get_post_meta($post_id, '_product_sync_category', true);
        if (!$sync_product || !$product_category) {
            return;
        }

        $product = wc_get_product($post_id);
        $thumbnail = get_post_thumbnail_id($post_id);
        if (!$thumbnail) {
            wp_die(__('No thumbnail supplied. Aborting.', 'vmw-wc'));
        }
        $thumbnail = get_attached_file($thumbnail);
        $thumbnail = DataPart::fromPath($thumbnail);

        $thumbnail = get_the_post_thumbnail_url($post_id);

        $data = [
            'name'              => $product->get_title(),
            'price'             => $product->get_regular_price(),
            'description'       => $product->get_description(),
            'short_description' => $product->get_short_description(),
            'thumbnail'         => $thumbnail,
            'category'          => $product_category
        ];

        static::send_to_vmw(new FormDataPart($data));
    }

	/**
	 * @param array|FormDataPart $data
	 */
    public static function send_to_vmw($data)
    {
        if (count($data->getParts()) === 0) {
            wp_die(__('Incorrect data passed to Vindmijnwijn.nl, disable product sync to continue.', 'vmw-wc'));
        }

        $token = get_option('vmw_key');
        if (!$token) {
            wp_die(__('No Vindmijnwijn.nl Vendor token supplied. Aborting.', 'vmw-wc'));
        }

        $vmw_url = get_option('vmw_base_url');
        if (!$vmw_url) {
            wp_die(__('No URL passed to base Vindmijnwijn.nl URL. Aborting.', 'vmw-wc'));
        }

        $route = sprintf('%sapi/v3/products', trailingslashit($vmw_url));

        $client = HttpClient::create(['auth_bearer' => $token]);
        try {
            $client->request(
                'POST',
                $route,
                [
                    'headers'    => $data->getHeaders()->toArray(),
                    'body'       => $data->bodyToIterable()
                ]
            );
        } catch (TransportExceptionInterface $exception) {
            wp_die($exception->getMessage());
        } catch (ServerException $exception) {
            wp_die($exception->getMessage());
        }
    }

    public static function create($plugin_name, $plugin_version)
    {
        return new static($plugin_name, $plugin_version);
    }
}
