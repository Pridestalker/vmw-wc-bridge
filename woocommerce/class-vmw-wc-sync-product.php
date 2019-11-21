<?php

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
        static::includes();
        if (!Vmw_Wc_Sync::hasPostSku($post_id)) {
            $sync = Vmw_Wc_New_Product::create($post_id);

            $sync->storeProduct();
        }
    }

    public static function create($plugin_name, $plugin_version)
    {
        return new static($plugin_name, $plugin_version);
    }

    private static function includes()
    {
        require_once __DIR__ . '/abstract-vmw-wc-sync.php';
        require_once __DIR__ . '/class-vmw-wc-new-product.php';
        require_once __DIR__ . '/class-vmw-wc-existing-product.php';
    }
}
