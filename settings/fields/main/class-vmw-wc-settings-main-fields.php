<?php

class Vmw_Wc_Settings_Main_fields
{
    public static function base_url_callback($args)
    {
        static::base_url_input();
        static::base_url_supplemental();
    }

    protected static function base_url_input(): void
    {
        ?>
        <input
            name="vmw_base_url"
            id="vmw_base_url"
            value="<?=get_option('vmw_base_url')?>"
            type="text"
        />
        <?php
    }

    protected static function base_url_supplemental()
    {
        ?>
        <p class="description">
            <?= _x('Caution, only edit when asked by staff.', 'vmw-wc') ?>
        </p>
        <?php
    }

    public static function key_callback($args)
    {
        static::key_input();
    }

    public static function key_input()
    {
        ?>
        <textarea
            name="vmw_key"
            id="vmw_key"
            type="text"
        ><?=get_option('vmw_key')?></textarea>
        <?php
    }

    public static function secret_callback($args)
    {
        static::secret_input();
    }

    public static function secret_input()
    {
        ?>
        <input
            name="vmw_secret"
            id="vmw_secret"
            value="<?=get_option('vmw_secret')?>"
            type="text"
        />
        <?php
    }
}
