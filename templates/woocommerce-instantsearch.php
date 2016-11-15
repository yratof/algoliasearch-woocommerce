<?php get_header(); ?>

<div id="algolia-search-box">
	<div id="algolia-stats"></div>
	<svg class="search-icon" width="25" height="25" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M24.828 31.657a16.76 16.76 0 0 1-7.992 2.015C7.538 33.672 0 26.134 0 16.836 0 7.538 7.538 0 16.836 0c9.298 0 16.836 7.538 16.836 16.836 0 3.22-.905 6.23-2.475 8.79.288.18.56.395.81.645l5.985 5.986A4.54 4.54 0 0 1 38 38.673a4.535 4.535 0 0 1-6.417-.007l-5.986-5.986a4.545 4.545 0 0 1-.77-1.023zm-7.992-4.046c5.95 0 10.775-4.823 10.775-10.774 0-5.95-4.823-10.775-10.774-10.775-5.95 0-10.775 4.825-10.775 10.776 0 5.95 4.825 10.775 10.776 10.775z" fill-rule="evenodd"></path></svg>
</div>
	<div id="ais-wrapper">

		<aside id="ais-facets">
			<section class="ais-facets" id="facet-price"></section>
			<section class="ais-facets" id="facet-categories"></section>
			<section class="ais-facets" id="facet-colors"></section>
		</aside>

		<main id="ais-main">
			<div id="algolia-hits"></div>
			<div id="algolia-pagination"></div>
		</main>
	</div>

	<script type="text/html" id="tmpl-instantsearch-hit">
		<article>
			<# if ( data.images.thumbnail ) { #>
			<div class="ais-hits--thumbnail">
				<a href="{{ data.permalink }}" title="{{ data.post_title }}">
					<img src="{{ data.images.shop_catalog.url }}" alt="{{ data.post_title }}" title="{{ data.post_title }}" itemprop="image" />
				</a>
			</div>
			<# } #>

			<div class="ais-hits--content">
				<h2 itemprop="name headline"><a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a></h2>

				<span class="price">
					<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">{{{algolia.woocommerce.currency_symbol}}}</span>{{data.display_price}}</span>
				</span>

			</div>
			<div class="ais-clearfix"></div>
		</article>
	</script>


	<script type="text/javascript">
		jQuery(function() {
			if(jQuery('#algolia-search-box').length > 0) {

				if (algolia.indices.posts_product === undefined && jQuery('.admin-bar').length > 0) {
					alert('It looks like you haven\'t indexed the posts_product index. Please head to the Indexing page of the Algolia Search plugin and index it.');
				}

				/* Instantiate instantsearch.js */
				var search = instantsearch({
					appId: algolia.application_id,
					apiKey: algolia.search_api_key,
					indexName: algolia.indices.posts_product.name,
					urlSync: {
						mapping: {'q': 's'},
						trackedParameters: ['query']
					},
					searchParameters: {
						facetingAfterDistinct: true
					},
					searchFunction: function(helper) {
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

				/* Search box widget */
				search.addWidget(
					instantsearch.widgets.searchBox({
						container: '#algolia-search-box',
						placeholder: 'Search Products...',
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
						hitsPerPage: 10,
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
							header: '<h3 class="widgettitle">Filter by price</h3>'
						},
						tooltips: {
							format: function(rawValue) {
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
							header: '<h3 class="widgettitle">Product categories</h3>'
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
							header: '<h3 class="widgettitle">Filter by color</h3>'
						}
					})
				);

				/* Start */
				search.start();

				jQuery('#algolia-search-box input').select();
			}
		});
	</script>

<?php get_footer(); ?>
