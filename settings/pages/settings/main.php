<div class="wrap">
    <h2><?= __('VMW Bridge Settings', 'vmw-wc') ?></h2>
    <form method="post" action="options.php">
        <?php
            settings_fields('vmw-wc-bridge-main-settings');
            do_settings_sections('vmw-wc-bridge-main-settings');
            submit_button();
        ?>
    </form>
</div>
