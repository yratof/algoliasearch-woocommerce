<?php



function aw_admin_enqueue_scripts() {
	wp_enqueue_style( 'algolia-woocommerce-admin', plugin_dir_url( __FILE__ ) . '../../assets/css/admin.css', array(), ALGOLIA_WOOCOMMERCE_VERSION );
	wp_enqueue_script( 'algolia-woocommerce-admin', plugin_dir_url( __FILE__ ) . '../../assets/js/admin.js', array(), ALGOLIA_WOOCOMMERCE_VERSION );
}

add_action( 'admin_enqueue_scripts', 'aw_admin_enqueue_scripts' );

function aw_render_admin_page() {
	$tab = get_query_var( 'tab', 'pages' );
	
	switch ($tab) {
		case 'pages': 
			aw_render_pages_tab();
			break;
		default:
			wp_die();
	}
}

function aw_render_pages_tab() {
	
	if ( isset( $_POST['update_pages'] ) ) {
		if( ! isset( $_POST['pages'] ) ) {
			aw_set_pages( array() );
		} else {
			aw_set_pages( $_POST['pages'] );
		}
		// Todo: Trigger notice.

		$message = esc_html__( 'Your changes have been saved.', 'algolia-woocommerce' );
	}

	$pages = aw_get_pages();
	require_once dirname( __FILE__ ) . '/views/pages.php';
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




