	<script type="text/html" id="tmpl-instantsearch">
		<div id="ais-wrapper">

			<aside id="ais-facets">
				<section class="ais-facets" id="facet-price"></section>
				<section class="ais-facets" id="facet-categories"></section>
				<section class="ais-facets" id="facet-colors"></section>
			</aside>

			<main id="ais-main">
				<div id="algolia-search-box">
					<div id="algolia-stats"></div>

					<svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"> <style> .st0 {fill:none;stroke:#2C2C38;stroke-width:2;stroke-miterlimit:10;} </style> <ellipse transform="rotate(-45 13.78 13.938)" class="st0" cx="13.8" cy="13.9" rx="10.8" ry="10.8"/> <path class="st0" d="M26.4 26.6l-4.9-4.9"/> </svg>

				</div>
				<div id="algolia-sort-by"></div>
				<div id="algolia-hits"></div>
				<div id="algolia-pagination"></div>
			</main>
		</div>
	</script>

	<script type="text/html" id="tmpl-instantsearch-hit">
		<article>
			<# if ( data.images.shop_catalog ) { #>
			<div class="ais-hits--thumbnail">
				<a href="{{ data.permalink }}" title="{{ data.post_title }}">
					<img src="{{ data.images.shop_catalog.url }}" alt="{{ data.post_title }}" title="{{ data.post_title }}" itemprop="image" />
				</a>
			</div>
			<# } #>

			<div class="ais-hits--content">
				<h2 itemprop="name headline"><a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a></h2>

				<div class="ais-hits--categories">
					in
					<#
						var product_cats = [];
						for (var index in data._highlightResult.taxonomies.product_cat) {
							product_cats.push(data._highlightResult.taxonomies.product_cat[index].value);
						}
						product_cats = product_cats.join(', ');
					#>
					{{{product_cats}}}
				</div>
				<span class="price">
					<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">{{{algolia.woocommerce.currency_symbol}}}</span>{{data.price}}</span>
				</span>

			</div>
			<div class="ais-clearfix"></div>
		</article>
	</script>

	<script type="text/javascript">

		jQuery(function() {
			var container = jQuery(algolia.woocommerce.selector);

			if(container.length === 0 && jQuery('.admin-bar').length > 0) {
				alert('You need to configure a valid selector in the "Zoning" tab of the "WooCommerce" settings inside the "Algolia" plugin.');
				return;
			}

			container.html(wp.template('instantsearch'));

			if(jQuery('#algolia-search-box').length === 0) {
				alert('Unable to find the node to add instantsearch.');
				return;
			}

			if (algolia.indices.posts_product === undefined && jQuery('.admin-bar').length > 0) {
				alert('It looks like you haven\'t indexed the posts_product index. Please head to the Indexing page of the Algolia Search plugin and index it.');
			}

			/* Instantiate instantsearch.js */
			var search = instantsearch({
				appId: algolia.application_id,
				apiKey: algolia.search_api_key,
				indexName: algolia.woocommerce.default_index_name,
				urlSync: {
					useHash: true
				},
				searchParameters: {
					facetingAfterDistinct: true
				},
				searchFunction: function (helper) {
					/* helper does a setPage(0) on almost every method call */
					/* see https://github.com/algolia/algoliasearch-helper-js/blob/7d9917135d4192bfbba1827fd9fbcfef61b8dd69/src/algoliasearch.helper.js#L645 */
					/* and https://github.com/algolia/algoliasearch-helper-js/issues/121 */
					var savedPage = helper.state.page;
					if (search.helper.state.query === '') {
						search.helper.setQueryParameter('distinct', false);
						search.helper.setQueryParameter('filters', 'record_index=0');
					} else {
						search.helper.setQueryParameter('distinct', true);
						search.helper.setQueryParameter('filters', '');
					}
					search.helper.setPage(savedPage);
					helper.search();
				}
			});

			var sort_by_indices = [];

			for(var i in algolia.woocommerce.sort_by) {
				sort_by_indices.push({
					'name': algolia.woocommerce.sort_by[i].index_name,
					'label': algolia.woocommerce.sort_by[i].display_name
				});
			}

			search.addWidget(
				instantsearch.widgets.sortBySelector({
					container: '#algolia-sort-by',
					indices: sort_by_indices
				})
			);

			/* Search box widget */
			search.addWidget(
				instantsearch.widgets.searchBox({
					container: '#algolia-search-box',
					placeholder: 'Search Products, Categories...',
					wrapInput: false,
					poweredBy: algolia.powered_by_enabled
				})
			);

			/* Stats widget */
			search.addWidget(
				instantsearch.widgets.stats({
					container: '#algolia-stats'
				})
			);

			/* Hits widget */
			search.addWidget(
				instantsearch.widgets.hits({
					container: '#algolia-hits',
					hitsPerPage: 9,
					templates: {
						empty: 'No results were found for "<strong>{{query}}</strong>".',
						item: wp.template('instantsearch-hit')
					}
				})
			);

			/* Pagination widget */
			search.addWidget(
				instantsearch.widgets.pagination({
					container: '#algolia-pagination'
				})
			);

			/* Price range slider refinement widget */
			search.addWidget(
				instantsearch.widgets.rangeSlider({
					container: '#facet-price',
					attributeName: 'price',
					templates: {
						header: '<h4>Filter by price</h4>'
					},
					tooltips: {
						format: function (rawValue) {
							return algolia.woocommerce.currency_symbol + Math.round(rawValue).toLocaleString();
						}
					}
				})
			);

			/* Categories refinement widget */
			search.addWidget(
				instantsearch.widgets.hierarchicalMenu({
					container: '#facet-categories',
					separator: ' > ',
					sortBy: ['count'],
					attributes: ['taxonomies_hierarchical.product_cat.lvl0', 'taxonomies_hierarchical.product_cat.lvl1', 'taxonomies_hierarchical.product_cat.lvl2'],
					templates: {
						header: '<h4>Product categories</h4>'
					}
				})
			);

			/* Tags refinement widget */
			search.addWidget(
				instantsearch.widgets.refinementList({
					container: '#facet-colors',
					attributeName: 'taxonomies.pa_color',
					operator: 'and',
					limit: 15,
					sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
					templates: {
						header: '<h4>Filter by color</h4>'
					}
				})
			);

			function joinHighlightedArray(items) {
				var values = [];

				for (var index in items) {
					values.push(items[index].value);
				}

				return values.join(', ');
			}

			/* Start */
			search.start();

			jQuery('#algolia-search-box input').select();
		});
	</script>

<?php get_footer(); ?>
