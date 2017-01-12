<?php



function aw_admin_enqueue_scripts() {
	wp_enqueue_style( 'algolia-woocommerce-admin', plugin_dir_url( __FILE__ ) . '../../assets/css/admin.css', array(), ALGOLIA_WOOCOMMERCE_VERSION );
	wp_enqueue_script( 'algolia-woocommerce-admin', plugin_dir_url( __FILE__ ) . '../../assets/js/admin.js', array(), ALGOLIA_WOOCOMMERCE_VERSION );

	// Enqueue the color picker assets.
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}

add_action( 'admin_enqueue_scripts', 'aw_admin_enqueue_scripts' );

function aw_render_admin_page() {
	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'pages';

	switch ($tab) {
		case 'pages': 
			aw_render_pages_tab();
			break;
		case 'zoning':
			aw_render_zoning_tab();
			break;
		case 'appearance':
			aw_render_appearance_tab();
			break;
		default:
			wp_die();
	}
}

function aw_render_pages_tab() {
	
	if ( isset( $_POST['submitted'] ) ) {
		if( ! isset( $_POST['pages'] ) ) {
			aw_set_pages( array() );
		} else {
			aw_set_pages( $_POST['pages'] );
		}
		$message = esc_html__( 'Your changes have been saved.', 'algolia-woocommerce' );
	}

	$pages = aw_get_pages();
	require_once dirname( __FILE__ ) . '/views/pages.php';
}

function aw_render_appearance_tab() {
	if ( isset( $_POST['submitted'] ) ) {
		if( isset( $_POST['primary_color'] ) ) {
			aw_set_primary_color( $_POST['primary_color'] );
		}
		$message = esc_html__( 'Your changes have been saved.', 'algolia-woocommerce' );
	}

	$primary_color = aw_get_primary_color();

	require_once dirname( __FILE__ ) . '/views/appearance.php';
}

function aw_render_zoning_tab() {
	if ( isset( $_POST['submitted'] ) ) {
		if( isset( $_POST['selector'] ) ) {
			aw_set_selector( $_POST['selector'] );
		}
		$message = esc_html__( 'Your changes have been saved.', 'algolia-woocommerce' );
	}

	$selector = aw_get_selector();
	$iframe_url = aw_get_zoning_test_page_url();
	require_once dirname( __FILE__ ) . '/views/zoning.php';
}

function aw_get_zoning_test_page_url() {
	// Todo: Handle no categories edge case.
	$page_url = '';

	$categories = get_categories( array(
		'taxonomy'     => 'product_cat',
		'orderby'      => 'name',
		'empty'        => 0
	) );

	foreach ( $categories as $category ) {
		$page_url = get_term_link( (int) $category->term_id, 'product_cat' );
		break;
	}

	if ( strpos( $page_url, '?' ) === false ) {
		$page_url .= '?algolia_selector=true';
	} else {
		$page_url .= '&algolia_selector=true';
	}

	return $page_url;
}

add_action( 'admin_menu', function() {
	add_submenu_page(
		'algolia',
		__( 'WooCommerce', 'algolia-woocommerce' ),
		__( 'WooCommerce', 'algolia-woocommerce' ),
		'manage_options',
		'algolia-woocommerce',
		'aw_render_admin_page'
	);
}, 11 );

function aw_admin_notices() {
	if ( ! class_exists('Algolia_Plugin') ) {
		return;
	}
	$algolia = Algolia_Plugin::get_instance();
	if ( ! $algolia->get_api()->is_reachable() ) {
		return;
	}
	$products_index = $algolia->get_index( 'posts_product' );

	if ( ! $products_index->is_enabled() ) {
		echo '<div class="error notice is-dismissible"><p>' .
			 sprintf( __( 'For the Algolia plugin for WooCommerce to work you should index the <b>`Products [posts_product]`</b> index on the <a href="%s">indexing page</a>.', 'algolia' ), admin_url( 'admin.php?page=algolia-indexing' ) ) .
			 '</p></div>';
	}
}

add_action( 'admin_notices', 'aw_admin_notices' );


