<?php

class Vmw_Wc_Sync_Tab
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

    public static function register_sync_tab($tabs)
    {
        $tabs ['sync'] = [
            'label'     => __('Vindmijnwijn.nl Sync', 'vmw-wc'),
            'priority'  => 50,
            'target'    => 'vmw_sync_product'
        ];

        return $tabs;
    }

    /**
     * @global $post
     * @global $woocommerce
     */
    public static function sync_tab_content()
    {
        global $post;
        ?>
        <div id="vmw_sync_product" class="panel woocommerce_options_panel">
            <?php
            woocommerce_wp_checkbox([
                'id'            => '_product_sync_vmw',
                'wrapper_class' => 'show_if_simple',
                'label'         => __('Sync product', 'vmw-wc'),
                'description'   => __('Sends the product to Vindmijnwijn.nl on save', 'vmw-wc'),
                'default'       => '0',
                'desc_tip'      => false,
            ]);

            woocommerce_wp_select([
                'id'            => '_product_sync_category',
                'wrapper_class' => 'show_if_simple',
                'label'         => __('Product category', 'vmw-wc'),
                'description'   => __('Select the correct category for Vindmijnwijn.nl', 'vmw-wc'),
                'options'       => [
                    1           => __('Red wine', 'vmw-wc'),
                    3           => __('White wine', 'vmw-wc'),
                    4           => __('Rose wine', 'vmw-wc'),
                    5           => __('Sparkling wine', 'vmw-wc'),
                    6           => __('Dessert wine', 'vmw-wc')
                ]
            ]);
            submit_button(
                __('Push to Vindmijnwijn.nl', 'vmw-wc'),
                'primary',
                '_push_to_vmw'
            );
            ?>
        </div>
        <?php
    }

    public static function save_meta_callback($post_id)
    {
        static::store_sync_meta($post_id);
        static::store_category_meta($post_id);

        if (isset($_POST['_push_to_vmw'])) {
            \do_action('vmw/bridge/product/push', $post_id);
        }
        \do_action('vmw/bridge/product/save/meta', $post_id);
    }

    private static function store_category_meta($post_id)
    {
        $key = '_product_sync_category';
        $sync_category = $_POST[$key];

        \update_post_meta($post_id, $key, (int) $sync_category);
    }

    private static function store_sync_meta($post_id)
    {
        $key = '_product_sync_vmw';
        $sync_vmw_checkbox = isset($_POST[$key]) ? 'yes' : 'no';
        \update_post_meta($post_id, $key, $sync_vmw_checkbox);
    }
}
