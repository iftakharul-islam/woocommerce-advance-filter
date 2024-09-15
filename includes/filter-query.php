<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
function wc_advanced_filter_products($query)
{
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category())) {

        $meta_query = array();
        $tax_query = array();
        $search_params = array();

        // Add search term if provided
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $search_params['s'] = sanitize_text_field($_GET['s']);
        }

        // Add price range if provided
        if (isset($_GET['min_price']) && !empty($_GET['min_price']) && isset($_GET['max_price']) && !empty($_GET['max_price'])) {
            $search_params['min_price'] = floatval($_GET['min_price']);
            $search_params['max_price'] = floatval($_GET['max_price']);
            $meta_query[] = array(
                'key' => '_price',
                'value' => array($search_params['min_price'], $search_params['max_price']),
                'type' => 'NUMERIC',
                'compare' => 'BETWEEN',
            );
        }

        // Add category if provided
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $search_params['category'] = sanitize_text_field($_GET['category']);
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $search_params['category'],
            );
        }

        // Add stock status if provided
        if (isset($_GET['stock_status']) && !empty($_GET['stock_status'])) {
            $search_params['stock_status'] = sanitize_text_field($_GET['stock_status']);
            $meta_query[] = array(
                'key' => '_stock_status',
                'value' => $search_params['stock_status'],
                'compare' => '=',
            );
        }

        // Add on sale status if provided
        if (isset($_GET['on_sale']) && $_GET['on_sale'] === 'yes') {
            $search_params['on_sale'] = 'yes';
            $meta_query[] = array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC',
            );
        }

        // Add tags if provided
        if (isset($_GET['tags']) && !empty($_GET['tags'])) {
            $search_params['tags'] = sanitize_text_field($_GET['tags']);
            $tax_query[] = array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => $search_params['tags'],
            );
        }

        // Add rating if provided
        if (isset($_GET['rating']) && !empty($_GET['rating'])) {
            $search_params['rating'] = intval($_GET['rating']);
            $meta_query[] = array(
                'key' => '_wc_average_rating',
                'value' => $search_params['rating'],
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }


        // Add product attribute filter if provided
        if (isset($_GET['attributes']) && !empty($_GET['attributes'])) {
            $search_params['attributes'] = array_map('sanitize_text_field', $_GET['attributes']);

            foreach ($search_params['attributes'] as $attribute => $value) {
                if (!empty($value)) {
                    $tax_query[] = array(
                        'taxonomy' => 'pa_' . sanitize_text_field($attribute), // WooCommerce stores attributes as 'pa_attribute-name'
                        'field' => 'slug',
                        'terms' => sanitize_text_field($value),
                    );
                }
            }
        }

        // Apply meta_query if any filters are set
        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }

        // Apply tax_query if any taxonomy filters are set
        if (!empty($tax_query)) {
            $query->set('tax_query', $tax_query);
        }

        // Set the search parameters
        if (!empty($search_params)) {
            foreach ($search_params as $key => $value) {
                $query->set($key, $value);
            }
        }
    }
}
add_action('pre_get_posts', 'wc_advanced_filter_products');
