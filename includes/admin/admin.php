<?php



function aw_admin_enqueue_scripts() {
	wp_enqueue_style( 'algolia-woocommerce-admin', plugin_dir_url( __FILE__ ) . '../../assets/css/admin.css', array(), ALGOLIA_WOOCOMMERCE_VERSION );
	wp_enqueue_script( 'algolia-woocommerce-admin', plugin_dir_url( __FILE__ ) . '../../assets/js/admin.js', array(), ALGOLIA_WOOCOMMERCE_VERSION );
}

add_action( 'admin_enqueue_scripts', 'aw_admin_enqueue_scripts' );

function aw_render_admin_page() {
	require_once dirname( __FILE__ ) . '/views/admin.php';
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




