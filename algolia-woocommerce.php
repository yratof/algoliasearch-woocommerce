<?php

/**
 * @wordpress-plugin
 * Plugin Name: Algolia Search for WooCommerce
 */

// The Algolia Search FOR WooCommerce plugin version.
define( 'ALGOLIA_WOOCOMMERCE_VERSION', '0.0.1' );
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

function aw_is_algolia_plugin_active() {
	$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
	// Check for both the official dir name and the GitHub repository name.
	return in_array( 'search-by-algolia-instant-relevant-results/algolia.php', $active_plugins ) ||
		   in_array( 'algoliasearch-wordpress/algolia.php', $active_plugins );
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
	if ( defined( 'ALGOLIA_VERSION' ) && version_compare( ALGOLIA_VERSION, '1.5.0', '<' ) ) {
		add_action( 'admin_notices', function () {
				echo '<div class="error notice">
					<p>' . __(
						'Algolia Search for WooCommerce: Search by Algolia – Instant & Relevant should be updated to at least version 1.5.0.',
						'algolia-woocommerce'
					) . '</p>
				</div>';
		} );
	}
} );

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

