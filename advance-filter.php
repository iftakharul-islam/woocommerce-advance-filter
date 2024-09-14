<?php
/**
 * Plugin Name: WooCommerce Advanced Filter Plugin
 * Description: Adds advanced search functionality to WooCommerce product searches with custom fields.
 * Version: 1.0
 * Author: Saastrick
 * Author URI: https://saastrick.com/
 * Text Domain: woocommerce-advanced-filter-plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue custom styles
function wc_advanced_filter_enqueue_styles()
{
    wp_enqueue_style('wc-advanced-filter-styles', plugins_url('css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'wc_advanced_filter_enqueue_styles');

// Hook into WooCommerce product search query// Hook into WooCommerce product search query
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

// Add a custom filter form to the shop page
function wc_advanced_filter_form()
{
    if (!is_shop() && !is_product_category()) {
        return;
    }
    ?>
    <section>
        <form id="wc-advanced-filter-form" method="get" action="<?php ?>">
            <input type="search" name="s" placeholder="Search products..."
                value="<?php echo esc_attr(get_search_query()); ?>" />

            <input type="number" name="min_price" placeholder="Min Price" step="0.01"
                value="<?php echo isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : ''; ?>" />
            <input type="number" name="max_price" placeholder="Max Price" step="0.01"
                value="<?php echo isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : ''; ?>" />

            <select name="category">
                <option value="">Select Category</option>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                ));
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->slug) . '"' . selected(isset($_GET['category']) && $_GET['category'] === $category->slug, true, false) . '>' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>

            <select name="stock_status">
                <option value="">Select Stock Status</option>
                <option value="instock" <?php selected(isset($_GET['stock_status']) && $_GET['stock_status'] === 'instock', true); ?>>In Stock</option>
                <option value="outofstock" <?php selected(isset($_GET['stock_status']) && $_GET['stock_status'] === 'outofstock', true); ?>>Out of Stock</option>
            </select>

            <select name="on_sale">
                <option value="">On Sale</option>
                <option value="yes" <?php selected(isset($_GET['on_sale']) && $_GET['on_sale'] === 'yes', true); ?>>Yes
                </option>
            </select>

            <select name="tags">
                <option value="">Select Tags</option>
                <?php
                $tags = get_terms(array(
                    'taxonomy' => 'product_tag',
                    'hide_empty' => true,
                ));
                foreach ($tags as $tag) {
                    echo '<option value="' . esc_attr($tag->slug) . '"' . selected(isset($_GET['tags']) && $_GET['tags'] === $tag->slug, true, false) . '>' . esc_html($tag->name) . '</option>';
                }
                ?>
            </select>

            <button type="submit">Filter</button>
        </form>
        <script>
            document.getElementById('wc-advanced-filter-form').addEventListener('submit', function (event) {
                // Get all form fields
                var inputs = this.querySelectorAll('input, select');

                // Loop through all form fields and remove empty ones
                inputs.forEach(function (input) {
                    if (!input.value) {
                        input.name = ''; // Remove the name attribute to exclude it from the query string
                    }
                });
            });
        </script>
    </section>
    <?php
}
add_action('woocommerce_before_shop_loop', 'wc_advanced_filter_form', 20);

