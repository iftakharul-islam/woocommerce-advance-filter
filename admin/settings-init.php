<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function wc_advanced_filter_settings_init()
{
    register_setting('wc_advanced_filter_settings_group', 'wc_advanced_filter_enabled_fields');

    add_settings_section(
        'wc_advanced_filter_settings_section',
        __('Filter Settings', 'woocommerce-advanced-filter-plugin'),
        null,
        'wc-advanced-filter-settings'
    );

    $fields = array(
        'product_name' => __('Product Name', 'woocommerce-advanced-filter-plugin'),
        'price_range' => __('Price Range', 'woocommerce-advanced-filter-plugin'),
        'category' => __('Category', 'woocommerce-advanced-filter-plugin'),
        'stock_status' => __('Stock Status', 'woocommerce-advanced-filter-plugin'),
        'on_sale' => __('On Sale', 'woocommerce-advanced-filter-plugin'),
        'tags' => __('Tags', 'woocommerce-advanced-filter-plugin'),
        'rating' => __('Rating', 'woocommerce-advanced-filter-plugin'),
        'attributes' => __('Product Attribute', 'woocommerce-advanced-filter-plugin'),
    );


    foreach ($fields as $key => $label) {
        add_settings_field(
            'wc_advanced_filter_' . $key,
            $label,
            'wc_advanced_filter_field_render',
            'wc-advanced-filter-settings',
            'wc_advanced_filter_settings_section',
            array('key' => $key)
        );
    }
}
add_action('admin_init', 'wc_advanced_filter_settings_init');

function wc_advanced_filter_field_render($args)
{
    $options = get_option('wc_advanced_filter_enabled_fields');
    $checked = isset($options[$args['key']]) ? $options[$args['key']] : false;
    ?>
    <input type="checkbox" name="wc_advanced_filter_enabled_fields[<?php echo esc_attr($args['key']); ?>]" value="1" <?php checked($checked, 1); ?> />
    <?php
}
