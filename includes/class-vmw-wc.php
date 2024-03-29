<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://doedejaarsma.nl/diensten/web-development
 * @since 1.0.0
 *
 * @package    Vmw_Wc
 * @subpackage Vmw_Wc/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Vmw_Wc
 * @subpackage Vmw_Wc/includes
 * @author     Doede Jaarsma communicatie <support@doedejaarsma.nl>
 */
class Vmw_Wc
{
	public static $update_url = 'https://github.com/Pridestalker/vmw-wc-bridge/';

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    Vmw_Wc_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (defined('VMW_WC_VERSION')) {
            $this->version = VMW_WC_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'vmw-wc';

        static::updateCheck();

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_settings_hooks();
        $this->define_woocommerce_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Vmw_Wc_Loader. Orchestrates the hooks of the plugin.
     * - Vmw_Wc_i18n. Defines internationalization functionality.
     * - Vmw_Wc_Admin. Defines all hooks for the admin area.
     * - Vmw_Wc_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(__DIR__) . 'includes/class-vmw-wc-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        include_once plugin_dir_path(__DIR__) . 'includes/class-vmw-wc-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        include_once plugin_dir_path(__DIR__) . 'admin/class-vmw-wc-admin.php';

        /**
         * The class responsible for defining all actions that are required for the settings.
         */
        include_once plugin_dir_path(__DIR__) . 'settings/class-vmw-wc-settings.php';

        /**
         * The class responsible for defining all actions and filters that
         * help with the WooCommerce connection.
         */
        include_once plugin_dir_path(__DIR__) . 'woocommerce/class-vmw-wc-woocommerce.php';

        $this->loader = new Vmw_Wc_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Vmw_Wc_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function set_locale()
    {

        $plugin_i18n = new Vmw_Wc_i18n();

        $this->get_loader()->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Vmw_Wc_Admin($this->get_plugin_name(), $this->get_version());

        $this->get_loader()->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->get_loader()->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    private function define_settings_hooks(): void
    {
        $plugin_settings = Vmw_Wc_Settings::create($this->get_plugin_name(), $this->get_version());

        $this->get_loader()->add_action('tf_create_options', $plugin_settings, 'register');
    }

    private function define_woocommerce_hooks()
    {
        $plugin_woocommerce = Vmw_Wc_WooCommerce::create($this->get_plugin_name(), $this->get_version());

        $this->get_loader()->add_action('woocommerce_init', $plugin_woocommerce, 'register');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.0.0
     * @return Vmw_Wc_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.0.0
     * @return string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    protected static function updateCheck()
    {
    	$updater = Puc_v4_Factory::buildUpdateChecker(
    		static::$update_url,
		    VMW_WC_FILE,
		    'vmw-wc',
		    12
	    );
    }
}
