<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://doedejaarsma.nl/diensten/web-development
 * @since   1.0.0
 * @package Vmw_Wc
 *
 * @wordpress-plugin
 * Plugin Name:       Vindmijnwijn woocommerce koppeling
 * Plugin URI:        https://vindmijnwijn.nl/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Doede Jaarsma communicatie
 * Author URI:        https://doedejaarsma.nl/diensten/web-development
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vmw-wc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC') ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('VMW_WC_VERSION', '1.0.0');
define('VMW_WC_FILE', __FILE__);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vmw-wc-activator.php
 */
function activate_vmw_wc()
{
    include_once plugin_dir_path(__FILE__) . 'includes/class-vmw-wc-activator.php';
    Vmw_Wc_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vmw-wc-deactivator.php
 */
function deactivate_vmw_wc()
{
    include_once plugin_dir_path(__FILE__) . 'includes/class-vmw-wc-deactivator.php';
    Vmw_Wc_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_vmw_wc');
register_deactivation_hook(__FILE__, 'deactivate_vmw_wc');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-vmw-wc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_vmw_wc()
{
    
    include_once __DIR__ . '/class-vmw-wc-autoloader.php';
    include_once __DIR__ . '/vendor/autoload.php';
    Vmw_Wc_Autoloader::init();
    
    $plugin = new Vmw_Wc();
    $plugin->run();

}
run_vmw_wc();
