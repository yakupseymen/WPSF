Create a custom settings page for your plugin or theme easily!

<img src="screenshot.png" alt="Screenshot" width="400"/>

## Features
* Register Settings Page
* Register Settings SubPage
* Register Custom Fields to Taxonomies
* Get data easily.

## Installation

1. Copy the plugin files to the `/wp-content/plugins/wpsf` directory.
2. Activate the plugin through the **Plugins** screen in WordPress
3. See `includes\example.php` to usage.

## Frequently Asked Questions

### How can I register new settings and page?

See `includes\example.php` to usage.

### How can I get my settings?
You can use the helper function: `wpsf_get_option`.

Example:
``````
$siteDescription = wpsf_get_option('your_field_name', 'Your Default Data');
``````

## Changelog
https://github.com/yakupseymen/wpsf/releases
