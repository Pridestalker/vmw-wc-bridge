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
    protected static $attributes = [];

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
     * @var TitanFramework
     */
    protected $titan;

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
        $this->titan = TitanFramework::getInstance('vmw-wc');
    }


    public static function create($plugin_name, $version)
    {
        return new self($plugin_name, $version);
    }

    public function register()
    {
        $panel = $this->titan->createAdminPage([
            'name'      => __('VMW Bridge Settings', 'vmw-wc'),
	        'parent'    => 'options-general.php'
        ]);

        static::main_settings_credentials($panel);
        static::main_settings_attributes($panel);

        $panel->createOption([
            'type'      => 'save',
        ]);
    }

    /**
     * @param TitanFrameworkAdminPage $panel
     */
    public static function main_settings_credentials($panel)
    {
        $panel->createOption([
            'name'      => __('Vindmijnwijn.nl portal url', 'vmw-wc'),
            'id'        => 'vmw_base_url',
            'type'      => 'text',
            'desc'      => _x('Caution, only edit when asked by staff.', 'VMW Bridge', 'vmw-wc'),
            'default'   => 'https://verkoper.vindmijnwijn.nl/'
        ]);

        $panel->createOption([
            'name'      => __('Vindmijnwijn.nl login key', 'vmw-wc'),
            'id'        => 'vmw_key',
            'type'      => 'textarea',
            'desc'      => _x('', 'VMW Bridge', 'vmw-wc'),
        ]);
    }

    /**
     * @param TitanFrameworkAdminPage $panel
     */
    public static function main_settings_attributes($panel)
    {
        $atts[0] = __('None', 'vmw-wc');

        foreach (static::get_attributes() as $attribute) {
            $atts[$attribute->attribute_id] = $attribute->attribute_label;
        }

        $settings = [
            'vmw_country'           => __('Country attribute', 'vmw-wc'),
            'vmw_region'            => __('Region attribute', 'vmw-wc'),
            'vmw_grapes'            => __('Grapes attribute', 'vmw-wc'),
            'vmw_alcohol'           => __('Alcohol attribute', 'vmw-wc'),
            'vmw_year'              => __('Year attribute', 'vmw-wc'),
            'vmw_classification'    => __('Classification attribute', 'vmw-wc'),
            'vmw_domain'            => __('Domain attribute', 'vmw-wc'),
            'vmw_contents'          => __('Contents attribute', 'vmw-wc')
        ];

        foreach ($settings as $key => $setting) {
            $panel->createOption([
                'name'      => $setting,
                'id'        => $key,
                'type'      => 'select',
                'default'   => 0,
                'options'   => $atts
            ]);
        }
    }

    private static function get_attributes()
    {
        if (!empty(static::$attributes)) {
            return static::$attributes;
        }

        return static::$attributes = \wc_get_attribute_taxonomies();
    }
}
