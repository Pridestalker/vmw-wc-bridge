<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link  https://doedejaarsma.nl/diensten/web-development
 * @since 1.0.0
 *
 * @package    Vmw_Wc
 * @subpackage Vmw_Wc/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Vmw_Wc
 * @subpackage Vmw_Wc/includes
 * @author     Doede Jaarsma communicatie <support@doedejaarsma.nl>
 */
class Vmw_Wc_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'vmw-wc',
            false,
	        dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
        );

    }



}
