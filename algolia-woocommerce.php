<?php
/**
 * @wordpress-plugin
 * Plugin Name: Algolia Search for WooCommerce
 * Plugin URI: https://community.algolia.com/woocommerce
 * Description: Extend Algolia for wordpress features into Woocommerce
 * Version: 0.11.1
 * Author: Algolia
 * Author URI: https://www.algolia.com
 *
 * Text Domain: algolia-woocommerce
 * Domain Path: /languages/
 */

// The Algolia Search for WooCommerce plugin version.
define( 'ALGOLIA_WOOCOMMERCE_VERSION', '0.11.1' );
define( 'ALGOLIA_WOOCOMMERCE_PLUGIN_BASENAME', plugin_basename(__FILE__) );

if ( ! defined( 'ALGOLIA_WOOCOMMERCE_PATH' ) ) {
	define( 'ALGOLIA_WOOCOMMERCE_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ALGOLIA_WOOCOMMERCE_URL' ) ) {
	define( 'ALGOLIA_WOOCOMMERCE_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * @return string
 */
function aw_plugin_path() {
	return untrailingslashit( ALGOLIA_WOOCOMMERCE_PATH );
}

add_filter( 'algolia_ua_integration_name', function() {
	return 'Woocommerce';
} );

add_filter( 'algolia_ua_integration_version', function() {
	return ALGOLIA_WOOCOMMERCE_VERSION;
} );

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 *      - WP_LANG_DIR/algolia-woocommerce/algolia-woocommerce-LOCALE.mo
 *      - WP_LANG_DIR/plugins/algolia-woocommerce-LOCALE.mo
 */
function aw_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'algolia-woocommerce' );
	load_textdomain( 'algolia-woocommerce', WP_LANG_DIR . '/algolia-woocommerce/algolia-woocommerce-' . $locale . '.mo' );
	load_plugin_textdomain( 'algolia-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'aw_load_plugin_textdomain' );

// Makes sure the plugin is defined before trying to use it
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}


function aw_is_algolia_plugin_active() {
	return is_plugin_active( 'search-by-algolia-instant-relevant-results/algolia.php' ) ||
	       is_plugin_active( 'algoliasearch-wordpress/algolia.php' );
}

/**
 * If Algolia is not active, let users know.
 **/
if ( ! aw_is_algolia_plugin_active() ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error notice">
			  	<p>' . __( 'Algolia Search for WooCommerce: <a href="' . admin_url( 'plugin-install.php?s=Search+by+Algolia+–+Instant+%26+Relevant+results&tab=search&type=term' ) . '">Search by Algolia – Instant & Relevant results</a> plugin should be enabled.', 'algolia-woocommerce' ) . '</p>
		  	  </div>';
	} );
}

/**
 * If Algolia version is lower than what is expected invite users to update.
 **/
add_action( 'plugins_loaded', function() {
	if ( defined( 'ALGOLIA_VERSION' ) && version_compare( ALGOLIA_VERSION, '2.1.0', '<' ) ) {
		add_action( 'admin_notices', function () {
				echo '<div class="error notice">
					<p>' . __(
						'Algolia Search for WooCommerce: Search by Algolia – Instant & Relevant should be updated to at least version 2.1.0.',
						'algolia-woocommerce'
					) . '</p>
				</div>';
		} );
	}
} );

/**
 * If WooCommerce is not active, let users know.
 **/
if ( ! is_plugin_active('woocommerce/woocommerce.php') ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error notice">
			  	<p>' . esc_html__( 'Algolia Search for WooCommerce: WooCommerce plugin should be enabled.', 'algolia-woocommerce' ) . '</p>
		  	  </div>';
	} );
} else {
	// Only load the plugin files if the WooCommerce plugin is enabled.
	require_once ALGOLIA_WOOCOMMERCE_PATH . '/includes/settings.php';
	require_once ALGOLIA_WOOCOMMERCE_PATH . '/includes/frontend.php';
	require_once ALGOLIA_WOOCOMMERCE_PATH . '/includes/indexing.php';

	if ( is_admin() ) {
		require_once dirname( __FILE__ ) . '/includes/admin/admin.php';
	}
}

