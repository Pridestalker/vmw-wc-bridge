<?php

class Vmw_Wc_WooCommerce
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

        $this->includes();
    }

    public static function register()
    {
        \add_action('vmw/bridge/product/push', ['Vmw_Wc_Sync_Product', 'sync_product'], 10, 1);

        \add_filter('woocommerce_product_data_tabs', ['Vmw_Wc_Sync_Tab', 'register_sync_tab']);
        \add_action('woocommerce_product_data_panels', ['Vmw_Wc_Sync_Tab', 'sync_tab_content']);
        \add_action('woocommerce_process_product_meta', ['Vmw_Wc_Sync_Tab', 'save_meta_callback']);
    }

    public function includes()
    {
        require_once __DIR__ . '/class-vmw-wc-sync-tab.php';
        require_once __DIR__ . '/class-vmw-wc-sync-product.php';
    }

    public static function create($plugin_name, $plugin_version)
    {
        return new static($plugin_name, $plugin_version);
    }
}
