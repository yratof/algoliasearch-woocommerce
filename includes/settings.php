<?php

add_option( 'algolia_wc_pages', array() );
add_option( 'algolia_wc_selector', '' );

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
