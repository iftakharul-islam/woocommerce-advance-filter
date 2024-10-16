<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
function wc_advanced_filter_form()
{
    if (!is_shop() && !is_product_category()) {
        return;
    }

    // Get enabled fields from settings
    $enabled_fields = get_option('wc_advanced_filter_enabled_fields', array(
        'product_name' => 1,
        'price_range' => 1,
        'category' => 1,
        'stock_status' => 1,
        'on_sale' => 1,
        'tags' => 1,
        'rating' => 1,
        'attributes' => 0,
    ));
    ?>
    <section class="wc-advanced-filter-section">
        <form id="wc-advanced-filter-form" method="get" action="<?php echo esc_url(home_url('/shop')); ?>">

            <div class="form-group">

                <?php if (!empty($enabled_fields['product_name'])): ?>
                    <div class="filter-field">
                        <label for="s"> <?php esc_html_e('Search products...', 'woocommerce-advanced-filter-plugin') ?> </label>
                        <input type="search" name="s" placeholder="Search products..."
                            value="<?php echo esc_attr(get_search_query()); ?>" />
                    </div>
                <?php endif; ?>

                <?php if (!empty($enabled_fields['price_range'])): ?>

                    <div class="filter-field price-range">
                        <label for="min_price"><?php esc_html_e('Price Range', 'woocommerce-advanced-filter-plugin') ?></label>
                        <div>
                            <input type="number" id="min_price" name="min_price" placeholder="Min Price" step="0.01"
                                value="<?php echo isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : ''; ?>" />
                            <input type="number" id="max_price" name="max_price" placeholder="Max Price" step="0.01"
                                value="<?php echo isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : ''; ?>" />
                        </div>
                    </div>

                <?php endif; ?>

                <?php if (!empty($enabled_fields['cccategory'])): ?>
                    <select title="Category" name="category">
                        <option value=""> <?php esc_html_e('Select Category', 'woocommerce-advanced-filter-plugin') ?> </option>
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
                <?php endif; ?>

                <?php if (!empty($enabled_fields['category'])): ?>
                    <div class="filter-field">
                        <label for="category"><?php esc_html_e('Category', 'woocommerce-advanced-filter-plugin'); ?> </label>
                        <select name="category">
                            <option value=""><?php esc_html_e('Select Category', 'woocommerce-advanced-filter-plugin'); ?>
                            </option>
                            <?php
                            // Get all top-level categories
                            $args = array(
                                'taxonomy' => 'product_cat',
                                'hide_empty' => true,
                                'parent' => 0,  // Get only top-level categories
                            );
                            $categories = get_terms($args);

                            // Recursively display categories and their children
                            wc_advanced_filter_category_dropdown($categories);
                            ?>
                        </select>
                    </div>

                <?php endif; ?>

                <?php if (!empty($enabled_fields['stock_status'])): ?>
                    <div class="filter-field">
                        <label for="stock_status"><?php esc_html_e(' Stock Status', 'woocommerce-advanced-filter-plugin'); ?>
                        </label>
                        <select title="<?php esc_html_e(' Stock Status', 'woocommerce-advanced-filter-plugin'); ?>"
                            name="stock_status">
                            <option value=""> <?php esc_html_e('Select Stock Status', 'woocommerce-advanced-filter-plugin') ?>
                            </option>
                            <option value="instock" <?php selected(isset($_GET['stock_status']) && $_GET['stock_status'] === 'instock', true); ?>>
                                <?php esc_html_e('In Stock', 'woocommerce-advanced-filter-plugin'); ?>
                            </option>
                            <option value="outofstock" <?php selected(isset($_GET['stock_status']) && $_GET['stock_status'] === 'outofstock', true); ?>>
                                <?php esc_html_e('Out of Stock', 'woocommerce-advanced-filter-plugin'); ?>
                            </option>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if (!empty($enabled_fields['on_sale'])): ?>
                    <div class="filter-field">
                        <label for="on_sale"><?php esc_html_e(' Sale Status', 'woocommerce-advanced-filter-plugin'); ?> </label>

                        <select title="<?php esc_html_e(' Sale Status', 'woocommerce-advanced-filter-plugin'); ?>"
                            name="on_sale">
                            <option value=""><?php esc_html_e('Select Sale Status', 'woocommerce-advanced-filter-plugin'); ?>
                            </option>
                            <option value="no" <?php selected(isset($_GET['on_sale']) && $_GET['on_sale'] === 'no', true); ?>>
                                <?php esc_html_e('No', 'woocommerce-advanced-filter-plugin'); ?>
                            </option>
                            <option value="yes" <?php selected(isset($_GET['on_sale']) && $_GET['on_sale'] === 'yes', true); ?>>
                                <?php esc_html_e('Yes', 'woocommerce-advanced-filter-plugin'); ?>
                            </option>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if (!empty($enabled_fields['tags'])): ?>
                    <div class="filter-field">
                        <label for="Tags"><?php esc_html_e(' Tags', 'woocommerce-advanced-filter-plugin'); ?> </label>

                        <select title="<?php _e('Tags', 'woocommerce-advanced-filter-plugin'); ?>" name="tags">
                            <option value=""> <?php _e('Select Tags', 'woocommerce-advanced-filter-plugin'); ?></option>
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
                    </div>
                <?php endif; ?>

                <?php if (!empty($enabled_fields['rating'])): ?>
                    <div class="filter-field">
                        <label for="rating"><?php esc_html_e('Rating', 'woocommerce-advanced-filter-plugin'); ?>
                        </label>

                        <select title="Rating" name="rating">
                            <option value=""><?php _e('Select Rating', 'woocommerce-advanced-filter-plugin'); ?></option>
                            <option value="5" <?php selected(isset($_GET['rating']) && $_GET['rating'] == '5', true); ?>>5 Stars
                            </option>
                            <option value="4" <?php selected(isset($_GET['rating']) && $_GET['rating'] == '4', true); ?>>4 Stars &
                                Up
                            </option>
                            <option value="3" <?php selected(isset($_GET['rating']) && $_GET['rating'] == '3', true); ?>>3 Stars &
                                Up
                            </option>
                            <option value="2" <?php selected(isset($_GET['rating']) && $_GET['rating'] == '2', true); ?>>2 Stars &
                                Up
                            </option>
                            <option value="1" <?php selected(isset($_GET['rating']) && $_GET['rating'] == '1', true); ?>>1 Star &
                                Up
                            </option>
                        </select>
                    </div>
                <?php endif; ?>

                <?php
                $attributes = wc_get_attribute_taxonomies(); // Get all available product attributes
            
                if (!empty($enabled_fields['attributes'])):
                    foreach ($attributes as $attribute):
                        $taxonomy = 'pa_' . $attribute->attribute_name; // WooCommerce stores attributes as 'pa_attribute-name'
                        $terms = get_terms(array(
                            'taxonomy' => $taxonomy,
                            'hide_empty' => true,
                        ));

                        if (!empty($terms)): ?>
                            <div class="filter-field">
                                <label
                                    for="attributes[<?php echo esc_attr($attribute->attribute_name); ?>]"><?php printf(__('Select %s', 'woocommerce-advanced-filter-plugin'), wc_attribute_label($taxonomy)); ?>
                                </label>

                                <select name="attributes[<?php echo esc_attr($attribute->attribute_name); ?>]">
                                    <option value="">
                                        <?php printf(__('Select %s', 'woocommerce-advanced-filter-plugin'), wc_attribute_label($taxonomy)); ?>
                                    </option>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?php echo esc_attr($term->slug); ?>" <?php selected(isset($_GET['attributes'][$attribute->attribute_name]) && $_GET['attributes'][$attribute->attribute_name] == $term->slug, true); ?>>
                                            <?php echo esc_html($term->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif;
                    endforeach;
                endif;
                ?>
                <div class="wc-advanced-filter-buttons filter-field">
                    <button type="submit" class="filter-button">
                        <?php esc_html_e('Filter', 'woocommerce-advanced-filter-plugin'); ?>
                        <span class="dashicons dashicons-filter"></span>
                    </button>
                    <button type="reset" class="filter-button filter-button-reset">
                        <?php esc_html_e('Clear', 'woocommerce-advanced-filter-plugin'); ?>
                        <span class="dashicons dashicons-image-rotate"></span>
                    </button>
                </div>
            </div>

        </form>
    </section>
    <?php

    add_action('wp_footer', 'wc_advanced_filter_js');
}



add_action('woocommerce_before_shop_loop', 'wc_advanced_filter_form', 20);
// add js file 
function wc_advanced_filter_js()
{
    ?>
    <script type="text/javascript" id="wc-advanced-filter-js">

    </script>
    <?php
}

// Recursive function to display nested categories in the dropdown
function wc_advanced_filter_category_dropdown($categories, $level = 0)
{
    foreach ($categories as $category) {
        // Indentation for child categories
        $indentation = str_repeat('&nbsp;', $level * 4);

        // Display the category with its product count
        echo '<option value="' . esc_attr($category->slug) . '"' . selected(isset($_GET['category']) && $_GET['category'] === $category->slug, true, false) . '>';
        echo $indentation . esc_html($category->name) . ' (' . esc_html($category->count) . ')';
        echo '</option>';

        // Get child categories for the current category
        $child_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $category->term_id,
        ));

        // Recursively display child categories
        if (!empty($child_categories)) {
            wc_advanced_filter_category_dropdown($child_categories, $level + 1);
        }
    }
}