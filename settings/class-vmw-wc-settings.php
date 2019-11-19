<?php
/**
 * This file handles the base of the settings.
 *
 * @package Vmw_Cs
 */

/**
 * Class Vmw_Wc_Settings
 */
class Vmw_Wc_Settings
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
	 * @var Vmw_Wc_Loader $loader
	 */
    protected $loader;

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


    public static function create($plugin_name, $version)
    {
        return new self($plugin_name, $version);
    }

    public static function register()
    {
        static::register_main_settings();
    }

    public static function register_main_settings(): void
    {
        add_options_page(
            __('VMW Bridge Settings', 'vmw-wc'),
            __('VMW Bridge Settings', 'vmw-wc'),
            'manage_options',
            'vmw-wc-bridge-settings',
            ['Vmw_Wc_Settings', 'render_page']
        );
    }

    public static function render_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    }
}
