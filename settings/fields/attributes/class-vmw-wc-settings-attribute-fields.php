<?php

class Vmw_Wc_Settings_Attribute_fields
{
    protected static $attributes = [];

    public static function vmw_country_input()
    {
        ?>
        <select name="vmw_country" id="vmw_country">
            <option value="-1">
                <?= __('None', 'vmw-wc') ?>
            </option>
            <?php foreach (static::get_attributes() as $attribute) : ?>
                <option value="<?= $attribute->id ?>"
                        <?= (get_option('vmw_country') === $attribute->id) ? 'selected' : '' ?>>
                    <?= $attribute->attribute_label ?>
                </option>
            <?php endforeach;?>
        </select>
        <?php
    }

    private static function get_attributes()
    {
        if (!empty(static::$attributes)) {
            return static::$attributes;
        }

        return static::$attributes = wc_get_attribute_taxonomies();
    }
}
