﻿# WooCommerce Advanced Filter Plugin

## Description

The **WooCommerce Advanced Filter Plugin** extends WooCommerce product searches by adding advanced filtering capabilities with custom fields. Enhance your WooCommerce shop by allowing customers to filter products based on multiple criteria including price, category, stock status, sale status, tags, rating, and product attributes.

## Features

- **Custom Filters**: Add filters for product name, price range, category, stock status, sale status, tags, rating, and product attributes.
- **Enable/Disable Filters**: Manage which filters are visible on the frontend through the plugin's settings page.
- **Nested Categories**: Display nested categories with product counts for better user navigation.
- **Custom Styles and Scripts**: Include custom CSS and JS for your filter form and settings page.

## Installation

1. **Upload Plugin**:
   - Upload the plugin folder to your `/wp-content/plugins/` directory.

2. **Activate Plugin**:
   - Go to the WordPress admin area and activate the plugin through the ‘Plugins’ menu.

## Usage

### Adding Filters to Your Shop Page

1. Go to **WooCommerce > Advanced Filter Settings** in the WordPress admin area.
2. **Enable/Disable Filters**:
   - Check the boxes next to the filters you want to enable on the frontend.
   - Customize filter settings as needed.

### Viewing and Using the Filter Form

1. Visit your shop page or product category page.
2. The filter form will be displayed with the enabled filters.
3. Use the filter options to refine product searches according to your criteria.

## Configuration

### Settings Page

1. Navigate to **WooCommerce > Advanced Filter Settings**.
2. Configure the following options:
   - **Product Name**: Toggle visibility for the product name search.
   - **Price Range**: Enable or disable price range filters.
   - **Category**: Choose to display categories and their counts.
   - **Stock Status**: Select stock status options.
   - **On Sale**: Filter products that are on sale.
   - **Tags**: Display product tags for filtering.
   - **Rating**: Enable rating filters.
   - **Product Attributes**: Configure attributes to be used for filtering.

### Customizing Styles and Scripts

- **CSS**: Add custom styles in the `css/style.css` file located in the plugin directory.
- **JS**: Add custom JavaScript in the `js/scripts.js` file located in the plugin directory.

## Optimization

### Caching

To ensure optimal performance, the plugin uses caching for queries. The cache is automatically updated when:
- New products are added.
- Products are deleted.
- Product categories or attributes are updated.

## Troubleshooting

1. **Filters Not Appearing**:
   - Ensure filters are enabled in the settings page.
   - Verify that the shop or category pages are being accessed.

2. **Slow Performance**:
   - Check caching settings and ensure the cache is functioning correctly.
   - Optimize your WooCommerce setup and server environment if necessary.

## Support

For support and inquiries, please contact us via our [support page](https://codecanyon.net/user/codeturner).

## Changelog

**Version 1.0**:
- Initial release with basic filtering capabilities.

## License

This plugin is licensed under the [GPL-2.0](https://opensource.org/licenses/GPL-2.0).

---

Feel free to adjust any sections or add more details as necessary!
