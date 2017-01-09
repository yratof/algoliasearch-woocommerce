---
title: Algolia search for WooCommerce quick start
description: Getting started guide to get you up and running.
layout: page.html
---
## Remove some sorting options

By default the plugin will create as many Algolia indices as there are sorting options in WooCommerce. To understand why, please read [this guide](https://www.algolia.com/doc/guides/relevance/sorting/#multiple-sorting-strategies).

If you don't want to let your users to be able to sort on some criterion you need to remove the sorting option directly from WooCommerce.

To do so, add the following to the `functions.php` of your theme or add it to a custom plugin:

```php
<?php
add_filter( 'woocommerce_catalog_orderby', function( $wc_options ) {
		// Adjust this array and only keep the sorting options you don't want to keep.
    $remove_sort_options = array(
        'popularity',
        'rating',
        'date',
        'price',
        'price-desc',
    );
    foreach ( $remove_sort_options as $option ) {
        if ( isset( $wc_options[$option] ) ) {
            unset( $wc_options[$option] );
        }
    }
    return $wc_options;
} );
```

<div class="alert alert-warning">Once this code has been injected, you will need to manually delete the indices that are no longer used from the Algolia dashboard.</div>
<div class="alert alert-warning">If you include a sorting option that was previously excluded, you will need to do a full re-index to ensure de sorting index replicas are created properly in Algolia.</div>

## Can I make it so that the search replaces the content dynamically on every page?

Yes; here is the code you should add to your `functions.php` file of your theme or in a plugin:

```php
// Ensures instantsearch.js search experience is injected on every page regardless
// of the configuration on the 'Algolia Search -> WooCommerce -> Pages' admin page.
add_filter( 'algolia_wc_should_display_instantsearch', '__return_true' );
```








