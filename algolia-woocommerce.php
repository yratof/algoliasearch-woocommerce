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

// Load files.
require_once ALGOLIA_WOOCOMMERCE_PATH . '/includes/settings.php';

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

	// Extract prices.
	$variations_count = 0;
	if ( $product instanceof WC_Product_Variable ) {
		$price = $product->get_variation_price( 'min', true );
		$regular_price = $product->get_variation_regular_price( 'min', true );
		$sale_price = $product->get_variation_sale_price( 'min', true );
		$max_price = $product->get_variation_price( 'max', true );
		$variations_count = count( $product->get_available_variations() );
	} else {
		$price = $max_price = $product->get_display_price();
		$regular_price = $product->get_display_price( $product->get_regular_price() );
		$sale_price = $product->get_display_price( $product->get_sale_price() );
	}
	// Todo: deal with grouped products.
	
	$attributes['product_type'] = (string) $product->get_type();
	$attributes['price'] = (float) $price;
	$attributes['regular_price'] = (float) $regular_price;
	$attributes['sale_price'] = (float) $sale_price;
	$attributes['max_price'] = (float) $max_price;
	$attributes['is_on_sale'] = (bool) $product->is_on_sale();
	$attributes['average_rating'] = (float) $product->get_average_rating();
	$attributes['rating_count'] = (int) $product->get_rating_count();
	$attributes['attributes'] = (array) $product->get_attributes();
	$attributes['width'] = (string) $product->get_width();
	$attributes['height'] = (string) $product->get_height();
	$attributes['weight'] = (string) $product->get_weight();
	$attributes['length'] = (string) $product->get_length();
	$attributes['review_count'] = (int) $product->get_review_count();
	$attributes['dimensions'] = (string) $product->get_dimensions();
	$attributes['variations_count'] = $variations_count;

	// TODO: Not sure how this behaves with variants.
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
	wp_register_style( 'algolia-woocommerce-selector', plugin_dir_url( __FILE__ ) . 'assets/css/selector.css', array(), ALGOLIA_WOOCOMMERCE_VERSION, 'screen' );

	wp_register_script( 'algolia-woocommerce-selector', plugin_dir_url( __FILE__ ) . 'assets/js/selector.js', array('jquery'), ALGOLIA_WOOCOMMERCE_VERSION );
}

add_action( 'init', 'aw_register_assets' );


function aw_enqueue_script() {
	if ( aw_should_display_instantsearch() ) {
		wp_enqueue_script( 'algolia-instantsearch' );
		wp_dequeue_style( 'algolia-instantsearch' );
		wp_enqueue_style( 'algolia-woocommerce-instantsearch' );
	}
}

add_action( 'wp_enqueue_scripts', 'aw_enqueue_script', 11 );

/**
 * @return bool
 */
function aw_should_display_instantsearch() {
	$pages = aw_get_pages();
	$should_display = false;
	if ( is_product_category() && in_array( 'category', $pages ) ) {
		$should_display = true;
	}

	if ( is_product_tag() && in_array( 'tag', $pages ) ) {
		$should_display = true;
	}

	return (bool) apply_filters( 'algolia_wc_should_display_instantsearch', $should_display );
}

function aw_footer() {
	if ( aw_should_display_instantsearch() ) {
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

	$algolia = Algolia_Plugin::get_instance();
	$index = $algolia->get_index( 'posts_product' );

	$config['woocommerce']['sort_by'] = array();
	if ( null === $index ) {
		return $config;
	}

	$replicas = $index->get_replicas();

	$mapping = aw_get_sort_by_mapping();
	
 	$default_option = aw_get_default_order_by_option();

	foreach ( $replicas as $replica ) {
		/** @var Algolia_Index_Replica $replica */
		$replica_index_name = $replica->get_replica_index_name( $index );
		$replica_attribute_name = $replica->get_attribute_name();
		$replica_order = $replica->get_order();

		// Only add the replica as a sorting option if a mapping is found.
		foreach ( $mapping as $wc_key => $entry ) {
			if ( ! isset( $entry['attribute'] ) ) {
				continue;
			}

			if ( $entry['attribute'] !== $replica_attribute_name ) {
				continue;
			}

			if ( $replica_order === $entry['order'] ) {
				$config['woocommerce']['sort_by'][] = array(
					'index_name'   => $replica_index_name,
					'display_name' => $entry['display_name'],
					'order'        => $replica_order,
					'attribute'    => $replica_attribute_name,
				);

				if ( $default_option === $wc_key ) {
					$config['woocommerce']['default_index_name'] = $replica_index_name;
				}

				// Once we found the display name, we can break.
				break;
			}
		}
	}

	// Todo: get the default index from the WC config.
	// loop over all and add 'default' as boolean

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
	if ( 'posts_product' !== $index->get_id() ) {
		return $replicas;
	}

	$mapping = aw_get_sort_by_mapping();
	foreach ( $mapping as $sort ) {
		if ( ! isset( $sort['attribute'] ) ) {
			// No attribute means we are dealing with the master index.
			continue;
		}

		$order = isset( $sort['order'] ) ? $sort['order'] : 'desc';
		$replicas[] = new Algolia_Index_Replica( $sort['attribute'], $order );
	}

	return $replicas;
}

add_filter( 'algolia_index_replicas', 'aw_products_index_replicas', 10, 2 );

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

/**
 * @return array
 */
function aw_get_catalog_order_by_options() {
	$options = (array) apply_filters( 'woocommerce_catalog_orderby', array(
		'menu_order' => __( 'Default sorting', 'woocommerce' ),
		'popularity' => __( 'Sort by popularity', 'woocommerce' ),
		'rating'     => __( 'Sort by average rating', 'woocommerce' ),
		'date'       => __( 'Sort by newness', 'woocommerce' ),
		'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
		'price-desc' => __( 'Sort by price: high to low', 'woocommerce' )
	) );

	if ( 'menu_order' !== aw_get_default_order_by_option() ) {
		unset( $options['menu_order'] );
	}

	if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
		unset( $options['rating'] );
	}

	return $options;
}

/**
 * @return array
 */
function aw_get_sort_by_mapping() {
	$mapping = array(
		'menu_order' => array(
			'attribute' 	=> 'menu_order',
			'order'	    	=> 'asc',
		),
		'popularity' => array(
			'attribute'    	=> 'total_sales',
			'order'		    => 'desc',
		),
		'rating'     => array(
			'attribute'    	=> 'average_rating',
			'order'		    => 'desc',
		),
		'date'       => array(
			'attribute' 	=> 'post_date',
			'order'		    => 'desc',
		),
		'price'      => array(
			'attribute' 	=> 'price',
			'order' 		=> 'asc',
		),
		'price-desc' => array(
			'attribute' 	=> 'price',
			'order'		    => 'desc',
		),
	);

	$mapping = (array) apply_filters( 'algolia_wc_sort_by_mapping', $mapping );

	$wc_options = aw_get_catalog_order_by_options();
	foreach ( $mapping as $key => &$entry ) {
		if ( ! isset( $wc_options[ $key ] ) ) {
			// If the option does not exist in WooCommerce, remove it from the mapping.
			unset( $mapping[$key] );

			continue;
		}

		// Get the display name from WooCommerce.
		$entry['display_name'] = $wc_options[ $key ];
	}

	return $mapping;
}

/**
 * @return string
 */
function aw_get_default_order_by_option() {
	return (string) apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
}



add_action( 'init', function() {
	if (
		current_user_can( 'manage_options' ) &&
		isset( $_GET['algolia_selector'] ) &&
		$_GET['algolia_selector'] === 'true'
	) {
		// Make sure we don't inject instantsearch while choosing the selector.
		add_filter( 'algolia_wc_should_display_instantsearch', '__return_false', 30 );

		add_action( 'wp_enqueue_scripts', function() {
			wp_enqueue_script( 'algolia-woocommerce-selector' );
			wp_enqueue_style( 'algolia-woocommerce-selector' );
		} );
	}
} );


if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/includes/admin/admin.php';
}
