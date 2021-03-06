<?php

function aw_register_assets() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// CSS.
	// wp_register_style( 'algolia-autocomplete', ALGOLIA_WOOCOMMERCE_URL . 'assets/css/algolia-autocomplete.css', array(), ALGOLIA_VERSION, 'screen' );
	wp_register_style( 'algolia-woocommerce-instantsearch', ALGOLIA_WOOCOMMERCE_URL . 'assets/css/algolia-woocommerce-instantsearch.css', array(), ALGOLIA_WOOCOMMERCE_VERSION, 'screen' );
	wp_register_style( 'algolia-woocommerce-selector', ALGOLIA_WOOCOMMERCE_URL . 'assets/css/selector.css', array(), ALGOLIA_WOOCOMMERCE_VERSION, 'screen' );

	wp_register_script( 'algolia-woocommerce-selector', ALGOLIA_WOOCOMMERCE_URL . 'assets/js/selector.js', array('jquery'), ALGOLIA_WOOCOMMERCE_VERSION );
}

add_action( 'init', 'aw_register_assets' );


function aw_template_loader( $template ) {
	if ( ! aw_should_display_instantsearch() ) {
		return $template;
	}

	// Avoid injecting instantsearch 2 times.
	add_filter( 'algolia_should_override_search_with_instantsearch', '__return_false' );

	return $template;
}

// Make sure this is called before the Algolia plugin for WordPress one so that we can disable the template overriding.
add_filter( 'template_include', 'aw_template_loader', 9 );

function aw_enqueue_script() {
	if ( aw_should_display_instantsearch() ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'algolia-instantsearch' );
		wp_enqueue_script( 'wp-util' );
		wp_enqueue_style( 'algolia-woocommerce-instantsearch' );
		wp_add_inline_style( 'algolia-woocommerce-instantsearch', aw_get_user_styles() );

		// Avoid flickering. In JS so that if JS is turned off, page displays nicely.
		// Only use this trick on pages where instantsearch.js is not displayed by default.
		$pages = aw_get_pages();
		if (
			( is_product_category() && in_array( 'category', $pages ) ) ||
			( is_product_tag() && in_array( 'tag', $pages ) ) ||
			( is_search() && in_array( 'search', $pages ) && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' )
		) {
			$selector = aw_get_selector();
			$selector = str_replace( array( ':first', ':last' ), array( ':first-child', ':last-child' ), $selector );

			if ( function_exists( 'wp_add_inline_script' ) ) {
				// This approach is better because if JS is disabled we are able to display the original content properly.
				// Only supported on WordPress 4.5+ so we provide a CSS fallback.
				wp_add_inline_script( 'algolia-instantsearch', 'document.write(\'<style type="text/css"> ' . $selector . '{display:none}</style>\');' );
			} else {
				wp_add_inline_style( 'algolia-woocommerce-instantsearch', $selector . '{display:none}' );
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'aw_enqueue_script', 15 );

/**
 * Returns the user styles configured in the admin panel.
 *
 * @return string
 */
function aw_get_user_styles() {
	$primary_color = aw_get_primary_color();
	if ( empty( $primary_color ) ) {
		return '';
	}

	$background_colors = array(
		'.ais-refinement-list--item.ais-refinement-list--item__active .ais-refinement-list--count',
		'.ais-range-slider--connect',
		'.alg-hit .alg-hit__ribbon',
		'.alg-hit .alg-hit__overlay .alg-cta--blue',
		'.ais-hierarchical-menu--item.ais-hierarchical-menu--item__active > div > a .ais-hierarchical-menu--count',
		'#ais-facets .ais-facets__wrapper .ais-hierarchical-menu--item__active > div > a .ais-hierarchical-menu--count',
		'#ais-facets .ais-facets__wrapper .ais-hierarchical-menu--list__lvl1 .ais-hierarchical-menu--item__active > div > a .ais-hierarchical-menu--count',
		'.ais-current-refined-values--item a',
	);

	$styles  = implode( ', ', $background_colors ) . ' { background-color : ' .  $primary_color . '}';

	$color_selectors = array(
		'.ais-pagination .ais-pagination--item__active .ais-pagination--link',
		'.alg-primary-color',
		'.ais-hierarchical-menu--item.ais-hierarchical-menu--item__active > div > a',
		'.ais-facets a:hover',
		'.ais-refinement-list--item__active label',
		'.ais-refinement-list label:hover',
		'.alg-hit .alg-hit__details .alg-hit__currentprice',
		'.ais-pagination .ais-pagination--link:hover',
		'.alg-hit .alg-hit__details .alg-hit__title a em',
	);

	$styles .= implode( ', ', $color_selectors ) . ' { color : ' .  $primary_color . '}';

	$styles .= '.alg-hit .alg-hit__overlay .alg-cta--blue { border-color: ' . $primary_color . '}';

	$styles .= '.alg-hit .alg-hit__details .alg-hit__description em:after {
    background: ' . $primary_color . ';
}';
	$styles .= '.alg-hit .alg-hit__details .alg-hit__title a em { background-color: ' . aw_hexadecimal_to_rgba( $primary_color, 0.1 ) . '}';
	$styles .= '.alg-hit .alg-hit__overlay .alg-cta--blue:hover { background-color: ' . aw_color_luminance( $primary_color, 0.1 ) . '}';

	return $styles;
}

function aw_hexadecimal_to_rgba( $color, $opacity = 1 ){
	$color = trim( $color, "#" );

	if( strlen( $color ) == 6 ){
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return '';
	}

	return 'rgba(' . $r . ", " . $g . ", " . $b . ", " . $opacity . ')';
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
function aw_color_luminance( $hex, $percent ) {

	// validate hex string

	$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
	$new_hex = '#';

	if ( strlen( $hex ) < 6 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	}

	// convert to decimal and change luminosity
	for ($i = 0; $i < 3; $i++) {
		$dec = hexdec( substr( $hex, $i*2, 2 ) );
		$dec = min( max( 0, $dec + $dec * $percent ), 255 );
		$new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
	}

	return $new_hex;
}

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

	if ( is_search() && in_array( 'search', $pages ) && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' ) {
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
	/*// Replace instantsearch.php template if we search for products.
	if( 'instantsearch.php' === $file && get_query_var( 'post_type' ) === 'product' ) {
		return aw_plugin_path() . '/templates/woocommerce-instantsearch.php';
	}

	// Provide with a store oriented autocomplete by default.
	// Todo: we should probably de-register default autocomplete CSS.
	if( 'autocomplete.php' === $file ) {
		return aw_plugin_path() . '/templates/' . $file;
	}*/

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
	$config['woocommerce']['products_per_page'] = (int) apply_filters( 'loop_shop_per_page', get_option( 'posts_per_page' ) );
	$config['woocommerce']['selector'] = aw_get_selector();

	// WooCommerce settings.
	$config['woocommerce']['currency'] = get_woocommerce_currency();
	$config['woocommerce']['currency_symbol'] = html_entity_decode( get_woocommerce_currency_symbol() );
	$config['woocommerce']['currency_position'] = get_option( 'woocommerce_currency_pos', 'left' );
	$config['woocommerce']['decimal_separator'] = get_option( 'woocommerce_price_decimal_sep', '.' );
	$config['woocommerce']['number_decimals'] = (int) get_option( 'woocommerce_price_num_decimals', '2' );
	$config['woocommerce']['thousands_separator'] = get_option( 'woocommerce_price_thousand_sep', ',' );

	$config['woocommerce']['replace_page'] = false;

	$config['woocommerce']['i18n'] = [
		'filter_by'                   => __('Filter by', 'algolia-woocommerce'),
		'sale'                        => __('SALE', 'algolia-woocommerce'),
		'view_details'                => __('VIEW DETAILS', 'algolia-woocommerce'),
		'add_to_cart'                 => __('ADD TO CART', 'algolia-woocommerce'),
		'we_found'                    => __('We found', 'algolia-woocommerce'),
		'products'                    => __('products', 'algolia-woocommerce'),
		'matching'                    => __('matching', 'algolia-woocommerce'),
		'in'                          => __('in', 'algolia-woocommerce'),
		'with'                        => __('with', 'algolia-woocommerce'),
		'invalid_selector_notice'     => __(
			'You need to configure a valid selector in the "Zoning" tab of the "WooCommerce" settings inside the "Algolia" plugin.',
			'algolia-woocommerce'
		),
		'products_not_indexed_notice' => __(
			'It looks like you haven\'t indexed the posts_product index . Please head to the Indexing page of the Algolia Search plugin and index it .',
			'algolia-woocommerce'
		),
		'search_input_placeholder'    => __('Search Products, Categories...', 'algolia-woocommerce'),
		'no_results_template'         => __(
			'No results were found for "<strong>{{query}}</strong>".',
			'algolia-woocommerce'
		),
		'product_categories'          => __('Product categories', 'algolia-woocommerce'),
		'filter_by_tag'               => __('Filter by tag', 'algolia-woocommerce'),
		'filter_by_price'             => __('Filter by price', 'algolia-woocommerce'),
	];

	$pages = aw_get_pages();
	if(is_product_category()) {
		$config['woocommerce']['page'] = 'category';

		$category = get_queried_object();
		$category_full_path = Algolia_Utils::get_taxonomy_tree( array( $category ), 'product_cat' );
		$deepest_level = array_pop( $category_full_path );
		$config['woocommerce']['category'] = html_entity_decode( $deepest_level[0] );

		if ( in_array( 'category', $pages ) ) {
			$config['woocommerce']['replace_page'] = true;
		}
	} elseif(is_product_tag()) {
		$config['woocommerce']['page'] = 'tag';
		$tag = get_queried_object();
		$config['woocommerce']['tag'] = html_entity_decode( $tag->name );

		if ( in_array( 'tag', $pages ) ) {
			$config['woocommerce']['replace_page'] = true;
		}
	} elseif(is_search()) {
		$config['woocommerce']['page'] = 'search';

		if ( in_array( 'search', $pages ) ) {
			$config['woocommerce']['replace_page'] = true;
		}
	} else {
		$config['woocommerce']['page'] = 'other';
	}

	$attributes = wc_get_attribute_taxonomies();
	
	$attributes_for_faceting = array();
	foreach ( $attributes as $attribute ) {
		$attribute = (array) $attribute;
		$attribute['attribute_id'] = (int) $attribute['attribute_id'];
		$attribute['attribute_public'] = (bool) $attribute['attribute_public'];

		$attributes_for_faceting[] = array_merge( (array) $attribute, array(
			'operator'     => 'or',
			'limit'	       => 8,
			'show_more'    => true,
			'sort_by'	   => array( 'isRefined:desc', 'count:desc', 'name:asc' ),
			'is_enabled'   => true, // Todo: find a better default for this.
			'weight'	   => 0,
		) );
	}

	// Let users filter attributes for faceting.
	$attributes_for_faceting = (array) apply_filters( 'algolia_wc_attributes_for_faceting', $attributes_for_faceting );
	
	// Remove all attributes that are not public.
	$attributes_for_faceting = array_filter( $attributes_for_faceting, function( $attribute ) {
		return (bool) $attribute['is_enabled'];
	} );

	// Order attributes based on weight.
	usort( $attributes_for_faceting, function ( $a, $b ) {
		return $a['weight'] > $b['weight'];
	} );

	$config['woocommerce']['attributes_for_faceting'] = $attributes_for_faceting;


	$algolia = Algolia_Plugin::get_instance();
	$index = $algolia->get_index( 'posts_product' );

	$config['woocommerce']['sort_by'] = array();
	if ( null === $index ) {
		return $config;
	}


	$mapping = aw_get_sort_by_mapping();
	$replicas = $index->get_replicas();

	$master_index_replica = new Algolia_Index_Replica( $mapping['menu_order']['attribute'], $mapping['menu_order']['order'] );
	array_unshift($replicas, $master_index_replica);

	$default_option = aw_get_current_order_by_option();

	foreach ( $replicas as $replica ) {
		/** @var Algolia_Index_Replica $replica */
		if ( 'menu_order' === $replica->get_attribute_name() ) {
			$replica_index_name = $index->get_name();
		} else {
			$replica_index_name = $replica->get_replica_index_name( $index );
		}
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

	if ( ! isset( $config['woocommerce']['default_index_name'] ) ) {
		$config['woocommerce']['default_index_name'] = $index->get_name();
	}

	return $config;
}

add_filter( 'algolia_config', 'aw_woocommerce_config', 5 );

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

	// Here WooCommerce is used to remove `menu_order` if not the default ordering option.
	// In our case we always keep it as it is the default index.
	// We even for its presence.
	if ( ! isset( $options['menu_order'] ) ) {
		// Make sure `menu_order` is the first entry which makes more sense.
		$options = array_merge( array(
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
		), $options );
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

/**
 * @return string
 */
function aw_get_current_order_by_option() {
	if ( isset( $_GET['orderby'] ) ) {
		$default_order = (string) $_GET['orderby'];
		$available_options = aw_get_catalog_order_by_options();
		if ( isset( $available_options[ $default_order ] ) ) {
			return $default_order;
		}
	}

	return aw_get_default_order_by_option();
}

add_action( 'init', function() {
	if (
		current_user_can( 'manage_options' ) &&
		isset( $_GET['algolia_selector'] ) &&
		$_GET['algolia_selector'] === 'true'
	) {
		show_admin_bar(false);

		// Make sure we don't inject instantsearch while choosing the selector.
		add_filter( 'algolia_wc_should_display_instantsearch', '__return_false', 30 );

		add_action( 'wp_enqueue_scripts', function() {
			wp_enqueue_script( 'algolia-woocommerce-selector' );
			wp_enqueue_style( 'algolia-woocommerce-selector' );
		} );
	}
} );
