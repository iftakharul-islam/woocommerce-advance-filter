<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function wc_advanced_filter_add_admin_menu()
{
    add_submenu_page(
        'woocommerce',
        'Advanced Filter Settings',
        'Advanced Filter Settings',
        'manage_options',
        'wc-advanced-filter-settings',
        'wc_advanced_filter_settings_page'
    );
}
add_action('admin_menu', 'wc_advanced_filter_add_admin_menu');

function wc_advanced_filter_settings_page()
{
    ?>
    <div class="wrap">
        <h1><?php _e('Advanced Filter Settings', 'woocommerce-advanced-filter-plugin'); ?> <i
                class="dashicons dashicons-filter"></i></h1>
        <p style="font-size: 14px; color: #555;">
            <?php _e('Configure the filters you want to display on the shop page for better product search functionality.', 'woocommerce-advanced-filter-plugin'); ?>
        </p>
        <form method="post" action="options.php">
            <?php
            settings_fields('wc_advanced_filter_settings_group');
            do_settings_sections('wc-advanced-filter-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
