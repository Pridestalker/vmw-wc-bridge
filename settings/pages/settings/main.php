<div class="wrap">
    <h2><?= __('VMW Bridge Settings', 'vmw-wc') ?></h2>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
            settings_fields('vmw-wc-bridge-main-settings');
            do_settings_sections('vmw-wc-bridge-main-settings');

            settings_fields('vmw-wc-bridge-attribute-settings');
            do_settings_sections('vmw-wc-bridge-attribute-settings');
            submit_button();
        ?>
    </form>
</div>
