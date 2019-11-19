<?php

/**
 * Fired during plugin activation
 *
 * @link  https://doedejaarsma.nl/diensten/web-development
 * @since 1.0.0
 *
 * @package    Vmw_Wc
 * @subpackage Vmw_Wc/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Vmw_Wc
 * @subpackage Vmw_Wc/includes
 * @author     Doede Jaarsma communicatie <support@doedejaarsma.nl>
 */
class Vmw_Wc_Activator
{
    private static $min_wp_version = '5.0';

    protected static $bloginfo = [];
    
    protected static $options = [
        'vmw_base_url'  => 'https://verkoper.vindmijnwijn.nl/',
        'vmw_key'       => '',
        'vmw_secret'    => '',
    ];
    
    /**
     * Runs on plugin activation.
     *
     * Runs the activation hooks
     *
     * @since 1.0.0
     */
    public static function activate()
    {

        static::is_correct_wp_version();
        static::register_options();
        \do_action('vmw/bridge/init');
    }

    public static function register_options()
    {
        foreach ( static::$options as $option => $default ) {
            if (!get_option($option)) {
                add_option($option, $default);
            }
        }
    }
    
    public static function is_correct_wp_version()
    {
        if (version_compare(static::get_bloginfo('version'), static::$min_wp_version) === -1) {
            $message = sprintf('Minimum WP version required %1$s. Please update to a later version', static::$min_wp_version);
            Vmw_Wc_Admin_Notices::error($message);
            \add_action(
                'admin_init', function () {
                    \deactivate_plugins([plugin_basename(VMW_WC_FILE)]);
                }
            );
        }
    }
    
    public static function get_bloginfo($key = 'name')
    {
        return static::set_get_bloginfo($key);
    }
    
    public static function set_get_bloginfo($key)
    {
        if (isset(static::$bloginfo[$key])) {
            return static::$bloginfo[$key];
        }
        
        return static::$bloginfo[$key] = get_bloginfo($key);
    }
    
}
