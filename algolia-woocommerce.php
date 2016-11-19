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
}

/**
 * @param array   $attributes
 * @param WP_Post $post
 *
 * @return array
 */
function aw_product_shared_attributes( array $attributes, WP_Post $post ) {
	$product = wc_get_product( $post );
	
	$attributes['product_type'] = (string) $product->get_type();
	$attributes['display_price'] = (float) $product->get_display_price();
	$attributes['price'] = (float) $product->get_price();
	$attributes['price_excluding_tax'] = (float) $product->get_price_excluding_tax();
	$attributes['regular_price'] = (float) $product->get_regular_price();
	$attributes['sale_price'] = (float) $product->get_sale_price();
	$attributes['price'] = (float) $product->get_price();
	$attributes['average_rating'] = (float) $product->get_average_rating();
	$attributes['rating_count'] = (int) $product->get_rating_count();
	$attributes['attributes'] = (array) $product->get_attributes();
	$attributes['width'] = (string) $product->get_width();
	$attributes['height'] = (string) $product->get_height();
	$attributes['weight'] = (string) $product->get_weight();
	$attributes['length'] = (string) $product->get_length();
	$attributes['review_count'] = (int) $product->get_review_count();
	$attributes['dimensions'] = (string) $product->get_dimensions();
	$attributes['total_sales'] = (int) get_post_meta( $post->ID, 'total_sales', true );

	return $attributes;
}

add_filter( 'algolia_post_product_shared_attributes', 'aw_product_shared_attributes', 10, 2 );
add_filter( 'algolia_searchable_post_product_shared_attributes', 'aw_product_shared_attributes', 10, 2 );

/**
 * @param bool    $should_index
 * @param WP_Post $post
 *
 * @return bool
 */
function aw_should_index_post( $should_index, WP_Post $post ) {
	// Only alter decision making if we are dealing with a product.
	if ( 'product' !== $post->post_type ) {
		return $should_index;
	}

	// This is required as `is_visible` method also checks for user_cap.
	if( 'publish' !== $post->post_status ) {
		return false;
	}

	$product = wc_get_product( $post );
	// We extracted this check because `is_visible` will not detect searchable products if not in a loop.
	if ( 'search' === $product->visibility ) {
		return true;
	}

	return $product->is_visible();
}

add_filter( 'algolia_should_index_post', 'aw_should_index_post', 10, 2 );
add_filter( 'algolia_should_index_searchable_post', 'aw_should_index_post', 10, 2 );

/**
 * @return string
 */
function aw_plugin_path() {
	return untrailingslashit( ALGOLIA_WOOCOMMERCE_PATH );
}

function aw_register_assets()
{
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// CSS.
	// wp_register_style( 'algolia-autocomplete', plugin_dir_url( __FILE__ ) . '../assets/css/algolia-autocomplete.css', array(), ALGOLIA_VERSION, 'screen' );
	wp_register_style( 'algolia-woocommerce-instantsearch', plugin_dir_url( __FILE__ ) . 'assets/css/algolia-woocommerce-instantsearch.css', array(), ALGOLIA_WOOCOMMERCE_VERSION, 'screen' );
}

add_action( 'init', 'aw_register_assets' );


function aw_enqueue_script() {
	if ( is_product_category() || is_product_tag() ) {
		wp_enqueue_script( 'algolia-instantsearch' );
		wp_dequeue_style( 'algolia-instantsearch' );
		wp_enqueue_style( 'algolia-woocommerce-instantsearch' );
	}
}

add_action( 'wp_enqueue_scripts', 'aw_enqueue_script', 11 );


function aw_footer() {
	if ( is_product_category() || is_product_tag() ) {
		include_once aw_plugin_path() . '/templates/woocommerce-instantsearch.php';
	}
}
add_action( 'wp_footer', 'aw_footer' );

/**
 * @param string $template
 * @param string $file
 *
 * @return string
 */
function aw_default_template( $template, $file ) {

	// Replace instantsearch.php template if we search for products.
	if( 'instantsearch.php' === $file && get_query_var( 'post_type' ) === 'product' ) {
		return aw_plugin_path() . '/templates/woocommerce-instantsearch.php';
	}

	// Provide with a store oriented autocomplete by default.
	// Todo: we should probably de-register default autocomplete CSS.
	if( 'autocomplete.php' === $file ) {
		return aw_plugin_path() . '/templates/' . $file;
	}

	return $template;
}

add_filter( 'algolia_default_template', 'aw_default_template', 9, 2 );

/**
 * Add WooCommerce configuration to the Algolia config var
 * So that the templates can use it more easily.
 *
 * @param array $config
 *
 * @return array
 */
function aw_woocommerce_config( array $config ) {
	$config['woocommerce']['currency_symbol'] = get_woocommerce_currency_symbol();

	return $config;
}

add_filter( 'algolia_config', 'aw_woocommerce_config', 5 );


function aw_instantsearch_scripts() {

	// Are we on a product search page?
	if( get_query_var( 'post_type' ) === 'product' ) {
		// Remove the default instantsearch styles and add the WooCommerce ones.
		wp_dequeue_style( 'algolia-instantsearch' );
		wp_enqueue_style( 'algolia-woocommerce-instantsearch' );
	}
}

add_action( 'algolia_instantsearch_scripts', 'aw_instantsearch_scripts' );

/**
 * @param array         $replicas
 * @param Algolia_Index $index
 *
 * @return array
 */
function aw_products_index_replicas( array $replicas, Algolia_Index $index ) {
	if ( 'posts_product' === $index->get_id() ) {
		$replicas[] = new Algolia_Index_Replica( 'price', 'asc' );
		$replicas[] = new Algolia_Index_Replica( 'price', 'desc' );
		$replicas[] = new Algolia_Index_Replica( 'total_sales', 'desc' );
		$replicas[] = new Algolia_Index_Replica( 'average_rating', 'desc' );
		// Todo: Add menu_order to the custom ranking rule.`
		// $replicas[] = new Algolia_Index_Replica( 'menu_order', 'asc' );
	}

	return $replicas;
}
add_filter( 'algolia_index_replicas', 'aw_products_index_replicas', 10, 2 );

function aw_template_loader( $file ) {
	/*var_dump( $file );
	exit('test');*/
	// var_dump( is_product_category() );
	// is_product_tag();

	return $file;
}

add_filter( 'template_include', 'aw_template_loader' );

/**
 * @param array $settings
 *
 * @return array
 */
function aw_product_index_settings( array $settings ) {

	$settings['attributesForFaceting'][] = 'price';
	
	return $settings;
}

add_filter( 'algolia_posts_product_index_settings', 'aw_product_index_settings' );
