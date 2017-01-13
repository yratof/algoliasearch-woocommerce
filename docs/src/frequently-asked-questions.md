---
title: Frequently asked questions
description: Common questions and answers about the Algolia plugin for WooCommerce.
layout: page.html
---
## How to remove sorting options

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

<div class="alert alert-info">If you choose to enable instantsearch.js on every page, you will have to make sure that the selector on `Algolia Search -> WooCommerce -> Zoning` has a match on every page.</div>

## Can I customize the search experience?

Yes and no; Currently you can only override the CSS by applying your rules on top of ours directly in your theme.

The HTML can not be overridden yet, and won't be until we release V1 of the plugin.

While the Beta takes place, we don't want our users to struggle updating the plugin. Letting our users override the HTML would make the updating process harder.

## Can I choose the attributes that are available in the filters?

Yes, for now you have to use a filter. You can add the following snippet to your `functions.php` theme or to a custom plugin:

```php
<?php
add_filter( 'algolia_wc_attributes_for_faceting', function( $attributes ) {
    // **Edit the values of this array**
    // You should put a list of all attribute ids you wish to see in the filters.
    // they will be displayed in the order they are listed here.
    $attribute_ids_to_keep = array(
        10,
        8,
        15,
    );

    foreach ( $attributes as &$attribute ) {
        if ( ! in_array( $attribute['attribute_id'], $attribute_ids_to_keep, true ) ) {
            // Remove the attribute from the filters.
            $attribute['is_enabled'] = false;

        } else {
            // Add the attribute to the filters.
            $attribute['is_enabled'] = true;

            // Order the filter.
            $attribute['weight'] = array_search( $attribute['attribute_id'], $attribute_ids_to_keep, true );
        }
    }

    return $attributes;
} );
```

This code will make sure only attributes with ids `10, 8, 15` are displayed as a filter option.

<div class="alert alert-info">The order of the ids in the array matters, and the attributes will be displayed as filters in the exact same order.</div>

There is no need to re-index your data to apply this change.








