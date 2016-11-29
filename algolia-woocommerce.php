<?php

/**
 * @wordpress-plugin
 * Plugin Name: Algolia Search for WooCommerce
 */

// The Algolia Search FOR WooCommerce plugin version.
define( 'ALGOLIA_WOOCOMMERCE_VERSION', '1.0.0' );
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

/**
 * If Algolia is not active, let users know.
 **/
if ( ! in_array( 'search-by-algolia-instant-relevant-results/algolia.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error notice">
			  	<p>' . __( 'Algolia Search for WooCommerce: <a href="' . admin_url( 'plugin-install.php?s=Search+by+Algolia+–+Instant+%26+Relevant+results&tab=search&type=term' ) . '">Search by Algolia – Instant & Relevant results</a> plugin should be enabled.', 'algolia-woocommerce' ) . '</p>
		  	  </div>';
	} );
}

/**
 * If WooCommerce is not active, let users know.
 **/
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
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

