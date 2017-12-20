<?php


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
	} else if ( $product instanceof WC_Product_Grouped ) {
        $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
        $child_prices     = array();
        $children         = array_filter( array_map( 'wc_get_product', $product->get_children() ), 'wc_products_array_filter_visible_grouped' );

        foreach ( $children as $child ) {
            /* @var $child WC_Product */
            if ( '' !== $child->get_price() ) {
                $child_prices[] = 'incl' === $tax_display_mode ? wc_get_price_including_tax( $child ) : wc_get_price_excluding_tax( $child );
			}

			// Get child taxonomies to merge in the grouped product.
            $child_post = get_post( $child->get_id() );
            if ( ! $child_post instanceof WP_Post ) {
                continue;
            }

            $taxonomy_objects = get_object_taxonomies( $child_post->post_type, 'objects' );

            foreach ( $taxonomy_objects as $taxonomy ) {
                /* @var $taxonomy WP_Taxonomy */
                if ( $taxonomy->hierarchical ) {
                    // Skip hierarchical taxonomies.
                    // Product attributes "select" & "text" are non hierarchical.
                    continue;
                }
                $terms = wp_get_object_terms( $child->get_id(), $taxonomy->name );

                $terms = is_array( $terms ) ? $terms : array();

                $taxonomy_values = wp_list_pluck( $terms, 'name' );
                if ( empty( $taxonomy_values ) ) {
                    continue;
                }

                if ( isset( $attributes['taxonomies'][ $taxonomy->name ] ) ) {
                    $attributes['taxonomies'][ $taxonomy->name ] = array_merge( $attributes['taxonomies'][ $taxonomy->name ], $taxonomy_values );
                } else {
                    $attributes['taxonomies'][ $taxonomy->name ] = $taxonomy_values;
                }

                // Ensure there are no duplicate values.
                $attributes['taxonomies'][ $taxonomy->name ] = array_unique( $attributes['taxonomies'][ $taxonomy->name ] );
            }
        }

        if ( ! empty( $child_prices ) ) {
            $min_price = min( $child_prices );
            $max_price = max( $child_prices );
        } else {
            $min_price = '';
            $max_price = '';
		}
		
		$price = $min_price;
		$regular_price = $min_price;
		$sale_price = $min_price;
	} else {
		$price = $max_price = wc_get_price_to_display( $product );
		$regular_price = wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) );
		$sale_price = wc_get_price_to_display( $product, array( 'price' => $product->get_sale_price() ) );
	}

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
	$attributes['is_featured'] = $product->is_featured() ? 1 : 0;
	$attributes['sku'] = $product->get_sku();

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
	if ( 'search' === $product->get_catalog_visibility() ) {
		
		return true;
	}

	return $product->is_visible();
}

add_filter( 'algolia_should_index_post', 'aw_should_index_post', 10, 2 );
add_filter( 'algolia_should_index_searchable_post', 'aw_should_index_post', 10, 2 );


// Customize the default settings for the products index.
function aw_products_settings( array $settings ) {
	$custom_ranking = $settings['customRanking'];
	array_unshift( $custom_ranking, 'desc(is_featured)' );
	$custom_ranking = array_unique( $custom_ranking );

	$settings['customRanking'] = $custom_ranking;

	// Here we use the Algolia_Index_Replica just to get the correct ranking.
	// In the end we do not deal with a replica but with the master index here.
	$mapping = aw_get_sort_by_mapping();
	$master_index_replica = new Algolia_Index_Replica( $mapping['menu_order']['attribute'], $mapping['menu_order']['order'] );
	$settings['ranking'] = $master_index_replica->get_ranking();

	$settings['attributesToIndex'][] = 'unordered(sku)';
	$settings['attributesToIndex'] = array_unique( $settings['attributesToIndex'] );

	if ( ! isset( $settings['disableTypoToleranceOnAttributes'] ) ) {
		$settings['disableTypoToleranceOnAttributes'] = array();
	}

	$settings['disableTypoToleranceOnAttributes'][] = 'sku';
	$settings['disableTypoToleranceOnAttributes'] = array_unique( $settings['disableTypoToleranceOnAttributes'] );

	return $settings;
}

add_filter( 'algolia_posts_product_index_settings', 'aw_products_settings' );

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
	foreach ( $mapping as $key => $sort ) {
		if ( $key === 'menu_order' ) {
			// We are dealing with the master index.
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

add_filter( 'algolia_post_images_sizes', function( $sizes ) {
    $sizes[] = 'shop_catalog';

    return array_unique( $sizes );
});


add_filter( 'algolia_get_synced_indices_ids', function( $ids ) {
    $ids[] = 'posts_product';

    return array_unique( $ids );
} );
