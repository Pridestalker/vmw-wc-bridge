<div class="wrap">
    <h2><?= __('VMW Bridge Settings', 'vmw-wc') ?></h2>
    <form method="post">
        <?php
            settings_fields('vmw-wc-bridge-settings');
            do_settings_sections('vmw-wc-bridge-settings');
            submit_button();
        ?>
    </form>
</div>
