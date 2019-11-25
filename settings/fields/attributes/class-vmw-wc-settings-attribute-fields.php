<?php

class Vmw_Wc_Settings_Attribute_fields
{
    protected static $attributes = [];

    public static function vmw_country_input(): void
    {
        static::input('vmw_country');
    }

    public static function vmw_grapes_input(): void
    {
        static::input('vmw_grapes');
    }

    public static function vmw_alcohol_input(): void
    {
        static::input('vmw_alcohol');
    }

	public static function vmw_year_input(): void
    {
        static::input('vmw_year');
    }

	public static function vmw_domain_input()
    {
        static::input('vmw_domain');
    }

	public static function vmw_region_input()
    {
        static::input('vmw_region');
    }

	public static function vmw_contents_input()
    {
        static::input('vmw_contents');
    }

	public static function vmw_classification_input(): void
    {
        static::input('vmw_classification');
    }

    private static function get_attributes()
    {
        if (!empty(static::$attributes)) {
            return static::$attributes;
        }

        return static::$attributes = wc_get_attribute_taxonomies();
    }

    private static function input($id): void
    {
        ?>
        <select name="<?=$id?>" id="<?=$id?>">
            <option value="-1">
                <?= __('None', 'vmw-wc') ?>
            </option>
            <?php foreach (static::get_attributes() as $attribute) : ?>
                <option value="<?= $attribute->id ?>"
                        <?= (get_option($id) === $attribute->id) ? 'selected' : '' ?>>
                    <?= $attribute->attribute_label ?>
                </option>
            <?php endforeach;?>
        </select>
        <?php
    }
}
