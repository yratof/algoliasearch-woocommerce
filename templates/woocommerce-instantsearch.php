	<script type="text/html" id="tmpl-instantsearch">
		<div id="ais-wrapper" class="alg-hits--1-col-xs
		 alg-hits--2-col-sm
		 alg-hits--3-col-md
		 alg-hits--3-col-lg
		 alg-hits--4-col-xl">

			<aside id="ais-facets">
				<div class="ais-facets__wrapper">
					<section class="ais-facets" id="facet-categories"></section>
					<div id="ais-wc-attributes"></div>
					<section class="ais-facets" id="facet-tags"></section>
					<section class="ais-facets" id="facet-price"></section>
					<section class="ais-facets" id="facet-price-ranges"></section>
				</div>
			</aside>

			<main id="ais-main">
				<div id="algolia-search-box">
					<svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"> <style> .st0 {fill:none;stroke:#2C2C38;stroke-width:2;stroke-miterlimit:10;} </style> <ellipse transform="rotate(-45 13.78 13.938)" class="st0" cx="13.8" cy="13.9" rx="10.8" ry="10.8"/> <path class="st0" d="M26.4 26.6l-4.9-4.9"/></svg>
					<svg class="clear-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"> <style> .st0 { fill:none;stroke:#2C2C38;stroke-width:2;stroke-miterlimit:10;} </style> <path class="st0" d="M25.4 25.6l-20-20M5.4 25.6l20-20"/></svg>
				</div>

				<div id="algolia-selectors">
					<div id="algolia-stats"></div>
					<div id="algolia-sort-by" class="algolia-filters"></div>
					<div id="algolia-mobile-filters" class="alg-show-on-xs alg-show-on-sm algolia-filters">
						<button>Filter by</button>
					</div>
				</div>

				<div id="algolia-hits"></div>
				<div id="algolia-pagination"></div>
			</main>
		</div>
	</script>

	<script type="text/html" id="tmpl-instantsearch-hit">

			<article class="alg-hit">
				<div class="alg-hit__content">
					<figure>
						<# if ( data.images.shop_catalog ) { #>
						<img src="{{ data.images.shop_catalog.url }}" alt="{{ data.post_title }}" title="{{ data.post_title }}" itemprop="image" />
						<# } #>

						<# if ( data.is_on_sale === true ) { #>
							<div class="alg-hit__ribbon">
								SALE
							</div>
						<# } #>
						<div class="alg-hit__overlay">
							<div class="alg-hit__actions">
								<a href="{{ data.permalink }}" class="alg-cta--transparent alg-button--small">VIEW DETAILS</a>
								<# if(data.product_type !== 'variable') { #>
								<a href="?add-to-cart={{ data.post_id }}" class="alg-cta--blue alg-button--small alg-button--themebutton">ADD TO CART</a>
								<# } #>
							</div>
						</div>
					</figure>
					<div class="alg-hit__details">
						<h2 class="alg-hit__title" itemprop="name headline">
							<a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a>
						</h2>
						<p class="alg-hit__description">
							<#
							var product_cats = [];
							if(data._highlightResult !== undefined && data._highlightResult.taxonomies !== undefined && data._highlightResult.taxonomies.product_cat !== undefined) {
								for (var index in data._highlightResult.taxonomies.product_cat) {
									product_cats.push(data._highlightResult.taxonomies.product_cat[index].value);
								}
							}
							product_cats = product_cats.join(', ').toUpperCase();
						#>
						{{{product_cats}}}
						</p>
						<p class="alg-hit__priceholder">
							<# if(data.is_on_sale === true) { #>
								<span class="alg-hit__previousprice">
									{{{algolia.woocommerce.currency_symbol}}}{{data.regular_price}}
								</span>
							<# } #>
							<span class="alg-hit__currentprice">
								{{{algolia.woocommerce.currency_symbol}}}{{data.price}}<# if(data.product_type === 'variable' && data.price !== data.max_price) { #>-{{data.max_price}}
								<# } #>
							</span>
						</p>
					</div>
				</div>
			</article>
	</script>

	<script type="text/html" id="tmpl-stats">
		<div class="alg-stats">
			We found {{ data.nbHits }} products
			<# if(data.query.length > 0) { #>
			matching "<span class="alg-primary-color">{{ data.query }}</span>"
			<# } #>

			in <span class="alg-primary-color">{{ data.processingTimeMS }} ms</span>

			<# if(algolia.powered_by_enabled === true) { #>
			with <span class="alg-powered-by">Algolia</span>
			<# } #>

		</div>

	</script>

	<script type="text/javascript">
		jQuery(function($) {
			var container = $(algolia.woocommerce.selector);

			if(container.length === 0 && $('.admin-bar').length > 0) {
				alert('You need to configure a valid selector in the "Zoning" tab of the "WooCommerce" settings inside the "Algolia" plugin.');
				return;
			}

			container.html(wp.template('instantsearch'));

			if($('#algolia-search-box').length === 0) {
				alert('Unable to find the node to add instantsearch.');
				return;
			}

			if (algolia.indices.posts_product === undefined && $('.admin-bar').length > 0) {
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

			/* Bind existing search inputs. */
			var $theme_search_inputs = $('input[name="s"]');
			$theme_search_inputs.on('keyup', handleSearchInputKeyUp);

			function handleSearchInputKeyUp(e) {
				if (e.keyCode === 13) {
					e.preventDefault();
					return;
				}

				var $target = $(e.currentTarget);
				search.helper.setQuery($target.val());
				search.helper.search();
			}

			/* Search box widget */
			if ( $theme_search_inputs.length === 0 ) {
				search.addWidget(
					instantsearch.widgets.searchBox({
						container: '#algolia-search-box',
						placeholder: 'Search Products, Categories...',
						wrapInput: false
					})
				);
			} else {
				$('#algolia-search-box').hide();
				search.addWidget({
					init: function() {},
					render: function(results) {
						/* Synchronize all search inputs. */
						$theme_search_inputs.val(results.state.query);
					}
				});
			}

			/* Stats widget */
			search.addWidget(
				instantsearch.widgets.stats({
					container: '#algolia-stats',
					templates: {
						body: wp.template('stats')
					}
				})
			);

			/* Hits widget */
			search.addWidget(
				instantsearch.widgets.hits({
					container: '#algolia-hits',
					hitsPerPage: algolia.woocommerce.products_per_page,
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

			/* Categories refinement widget */
			search.addWidget(
				instantsearch.widgets.hierarchicalMenu({
					container: '#facet-categories',
					separator: ' > ',
					sortBy: ['count:desc', 'name:asc'],
					attributes: ['taxonomies_hierarchical.product_cat.lvl0', 'taxonomies_hierarchical.product_cat.lvl1', 'taxonomies_hierarchical.product_cat.lvl2'],
					templates: {
						header: '<h4>Product categories</h4>'
					}
				})
			);

			var $attributes_container = $('#ais-wc-attributes');
			for ( var i in algolia.woocommerce.attributes ) {
				var attribute_name = algolia.woocommerce.attributes[i]['attribute_name'];
				var attribute_label = algolia.woocommerce.attributes[i]['attribute_label'];
				var attribute_type = algolia.woocommerce.attributes[i]['attribute_type'];

				if ( attribute_type !== 'select' ) {
					continue;
				}

				$attributes_container.append( '<section class="ais-facets" id="facet-attribute-' + attribute_name + '"></section>' );

				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#facet-attribute-' + attribute_name,
						attributeName: 'taxonomies.pa_' + attribute_name,
						operator: 'and',
						limit: 8,
						showMore: true,
						sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
						templates: {
							header: '<h4>Filter by ' + attribute_label + '</h4>'
						}
					})
				);
			}

			/* Tags refinement widget */
			search.addWidget(
				instantsearch.widgets.refinementList({
					container: '#facet-tags',
					attributeName: 'taxonomies.product_tag',
					operator: 'and',
					limit: 8,
					showMore: true,
					sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
					templates: {
						header: '<h4>Filter by tag</h4>'
					}
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

			/* Price ranges widget */
			/*search.addWidget(
				instantsearch.widgets.priceRanges({
					container: '#facet-price-ranges',
					attributeName: 'price',
					labels: {
						currency: algolia.woocommerce.currency_symbol,
						separator: 'to',
						button: 'Go'
					}
				})
			);*/

			search.addWidget({
				init: function() {
					$('.clear-search-icon').on('click', function() {
						search.helper.setQuery('');
						search.helper.search();
					});
				},
				render: function(results) {
					var clearIcon = $('.clear-search-icon');
					if(results.state.query.length === 0) {
						clearIcon.hide();
					} else {
						clearIcon.show();
					}
				}
			});

			search.addWidget({
				init: function(options) {
					if(window.location.hash.length > 0) {
						return;
					}

					// Get the initial from the query string "s" parameter if no hash is present.
					if (algolia.query.length > 0) {
						options.helper.setQuery(algolia.query);
					}

					// Set the current category if no anchor is already present.
					if(algolia.woocommerce.page === 'category') {
						options.helper.toggleRefine('taxonomies_hierarchical.product_cat.lvl0', algolia.woocommerce.category);
					}

					// Set the current tag if no anchor is already present.
					if(algolia.woocommerce.page === 'tag') {
						options.helper.toggleRefine('taxonomies.product_tag', algolia.woocommerce.tag);
					}
				}
			});

			/* Start */
			search.start();


			//-------------------------------
			// Handle swipe
			//-------------------------------
			var $facets = $('#ais-facets');
			var $wrapper = $('.ais-facets__wrapper');

			var start = 0;
			var current = 0;
			var cardPos = 0;
			var delta;
			var dragging = true;

			function onTouchStart(event){
				var event = event.originalEvent;
				start = event.pageX || event.touches[0].pageX;
			}

			function onTouchMove(event){
				var event = event.originalEvent;
				current = event.pageX || event.touches[0].pageX;
				cardPos = current - start;
				if(cardPos >= 0){
					$facets.css({
						"will-change": "transform",
						"transition": "none",
						"transform": "translate("+cardPos+"px,0)"
					});
				}
			}

			function onTouchEnd(event){
				if(cardPos >= 90){
					$facets.removeAttr("style");
					$facets.removeClass('ais-facets--visible');
				}
				$facets.removeAttr("style");
			}

			$facets.on('touchstart', onTouchStart);
			$facets.on('touchmove', onTouchMove);
			$facets.on('touchend', onTouchEnd);


			//-------------------------------
			//  Stop Handle swipe
			//-------------------------------


			$('.ais-facets__wrapper').on('click',function(e){
				e.stopPropagation();
			})

			$('#algolia-mobile-filters button').on('click',function(e) {
				e.stopPropagation();
				$facets.addClass('ais-facets--visible')
			});

			$wrapper.on('click',function(e){
				e.stopPropagation();
			});

			$(document).on('click',function(event){
				$facets.removeClass('ais-facets--visible');
			});

			$('#algolia-search-box input').select();

			/* Make the bottom of the product card all clickable */
			/* We do this in JS to not mess up the HTML. */
			$('#algolia-hits').on('click', '.alg-hit__details', function(e) {
				var $target = $(e.currentTarget);
				var link = $target.find('a:first');

				if(link.length === 1) {
					var href = link.attr('href');
					window.location = href;
				}
			});


			$('#algolia-stats').on('click', '.alg-powered-by', function() {
				window.location = 'https://www.algolia.com/?' +
					'utm_source=instantsearch_woocommerce&' +
					'utm_medium=website&' +
					'utm_content=' + location.hostname + '&' +
					'utm_campaign=poweredby';
			});

			/* Handle responsivness. */
			$(window).resize(update_container_class);

			function update_container_class() {
				var width = container.outerWidth();
				var containerClass = '';
				if ( width < 550 ) {
					containerClass = 'alg-container--xs';
				} else if( width < 768 ) {
					containerClass = 'alg-container--sm';
				} else if( width < 992 ) {
					containerClass = 'alg-container--md';
				} else if( width < 1240 ) {
					containerClass = "alg-container--lg";
				}	else {
					containerClass = 'alg-container--xl';
				}
				container.removeClass('alg-container--xs alg-container--sm alg-container--md alg-container--lg alg-container--xl');
				container.addClass(containerClass);
			}
			update_container_class();
		});
	</script>

<?php get_footer(); ?>
