<?php

add_option( 'algolia_wc_pages', array() );
add_option( 'algolia_wc_selector', '' );
add_option( 'algolia_wc_primary_color', '#46AEDA' );

/**
 * @return array
 */
function aw_get_pages() {
	return (array) get_option( 'algolia_wc_pages', array() );
}

/**
 * @param array $pages
 */
function aw_set_pages( array $pages ) {
	$allowed = array( 'category', 'tag', 'search' );
	$filtered = array();
	foreach ( $pages as $page ) {
		if ( in_array( $page, $allowed, true ) ) {
			$filtered[] = $page;
		}
	}

	update_option( 'algolia_wc_pages', $filtered );
}

/**
 * @param string $selector
 */
function aw_set_selector( $selector ) {
	update_option( 'algolia_wc_selector', (string) $selector );
}

/**
 * @return string
 */
function aw_get_selector() {
	return (string) get_option( 'algolia_wc_selector' );
}

/**
 * @return array
 */
function aw_get_primary_color() {
	return (string) get_option( 'algolia_wc_primary_color', '#46AEDA' );
}

/**
 * @return array
 */
function aw_set_primary_color( $color ) {
	// Todo: validate hexacode.
	
	update_option( 'algolia_wc_primary_color', $color );
}
