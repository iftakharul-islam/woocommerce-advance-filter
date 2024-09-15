<?php
/**
 * Plugin Name: WooCommerce Advanced Filter Plugin
 * Description: Adds advanced search functionality to WooCommerce product searches with custom fields.
 * Version: 1.0
 * Author: codeturner
 * Author URI: https://codecanyon.net/user/codeturner
 * Text Domain: woocommerce-advanced-filter-plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WC_ADVANCED_FILTER_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Enqueue styles

// Enqueue styles and scripts for the admin settings page
function wc_advanced_filter_enqueue_admin_styles_scripts($hook) {
    // Check if we are on the settings page for the plugin
    if ($hook !== 'woocommerce_page_wc-advanced-filter-settings') {
        return;
    }

    // Enqueue admin-specific styles and scripts
    wp_enqueue_style('wc-advanced-filter-admin-styles', plugins_url('css/admin-style.css', __FILE__));
    wp_enqueue_script('wc-advanced-filter-admin-scripts', plugins_url('js/admin-scripts.js', __FILE__), array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'wc_advanced_filter_enqueue_admin_styles_scripts');



// Enqueue styles and scripts for the frontend filter form
function wc_advanced_filter_enqueue_frontend_styles_scripts() {
    if (is_shop() || is_product_category()) {
        wp_enqueue_style('wc-advanced-filter-styles', plugins_url('css/style.css', __FILE__));
        wp_enqueue_script('wc-advanced-filter-scripts', plugins_url('js/scripts.js', __FILE__), array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'wc_advanced_filter_enqueue_frontend_styles_scripts');

// Include necessary files
require_once WC_ADVANCED_FILTER_PLUGIN_DIR . 'includes/filter-form.php';
require_once WC_ADVANCED_FILTER_PLUGIN_DIR . 'includes/filter-query.php';
require_once WC_ADVANCED_FILTER_PLUGIN_DIR . 'admin/settings-init.php';
require_once WC_ADVANCED_FILTER_PLUGIN_DIR . 'admin/settings-page.php';
