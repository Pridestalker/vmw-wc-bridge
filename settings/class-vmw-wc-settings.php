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
            'vmw-wc-bridge-main-settings',
            ['Vmw_Wc_Settings', 'render_page']
        );
    }

    public static function render_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        include_once __DIR__ . '/pages/settings/main.php';
    }

    public static function main_settings_credentials()
    {
        add_settings_section(
            'vmw-wc-bridge-main-credentials-section',
            __('Login credentials', 'vmw-wc'),
            '__return_false',
            'vmw-wc-bridge-main-settings'
        );
        register_setting(
            'vmw-wc-bridge-main-settings',
            'vmw_base_url'
        );

        register_setting(
            'vmw-wc-bridge-main-settings',
            'vmw_key'
        );

        register_setting(
            'vmw-wc-bridge-main-settings',
            'vmw_secret'
        );

        add_settings_field(
            'vmw_base_url',
            __('Vindmijnwijn.nl portal url', 'vmw-wc'),
            ['Vmw_Wc_Settings_Main_fields', 'base_url_callback'],
            'vmw-wc-bridge-main-settings',
            'vmw-wc-bridge-main-credentials-section'
        );

        add_settings_field(
            'vmw_key',
            __('Vindmijnwijn.nl portal key', 'vmw-wc'),
            ['Vmw_Wc_Settings_Main_fields', 'key_callback'],
            'vmw-wc-bridge-main-settings',
            'vmw-wc-bridge-main-credentials-section'
        );
    }

    public static function settings_whitelist($whitelist_options)
    {
        $whitelist_options['vmw-wc-bridge-main-settings'] = [
            'vmw_base_url',
            'vmw_key',
        ];

        return $whitelist_options;
    }

    public function includes()
    {
        require_once __DIR__ . '/fields/main/class-vmw-wc-settings-main-fields.php';
    }
}
