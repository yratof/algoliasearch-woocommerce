	<script type="text/html" id="tmpl-instantsearch">
		<div id="ais-wrapper" class="alg-hits--2-col-xs
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
					<svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"> <style> .st01 {fill:none;stroke:#bfc7d8;stroke-width:2;stroke-miterlimit:10;} </style> <ellipse transform="rotate(-45 13.78 13.938)" class="st01" cx="13.8" cy="13.9" rx="10.8" ry="10.8"/> <path class="st01" d="M26.4 26.6l-4.9-4.9"/></svg>
					<svg class="clear-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"> <style> .st0 { fill:none;stroke:#2C2C38;stroke-width:2;stroke-miterlimit:10;} </style> <path class="st0" d="M25.4 25.6l-20-20M5.4 25.6l20-20"/></svg>
				</div>

				<div id="algolia-selectors">
					<div id="algolia-stats"></div>
					<div id="algolia-sort-by" class="algolia-filters"></div>
					<div id="algolia-mobile-filters" class="alg-show-on-xs alg-show-on-sm algolia-filters">
						<button>{{ algolia.woocommerce.i18n.filter_by }}</button>
					</div>
				</div>

				<div id="alg-current-refinements"></div>

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
								{{ algolia.woocommerce.i18n.sale }}
							</div>
						<# } #>
						<div class="alg-hit__overlay">
							<div class="alg-hit__actions">
								<a href="{{ data.permalink }}" class="alg-cta--transparent alg-button--small">{{ algolia.woocommerce.i18n.view_details }}</a>
								<# if(data.product_type !== 'variable' && data.product_type !== 'grouped') { #>
								<a href="?add-to-cart={{ data.post_id }}" class="alg-cta--blue alg-button--small alg-button--themebutton">{{ algolia.woocommerce.i18n.add_to_cart }}</a>
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
							product_cats = product_cats.join(', ');
						#>
						{{{product_cats}}} &nbsp;
						</p>



						<p class="alg-hit__priceholder">
							<# if(data.is_on_sale === true && data.product_type !== 'variable' && data.product_type !== 'grouped') { #>
								<span class="alg-hit__previousprice">
									{{data.formatted_regular_price}}
								</span>
							<# } #>
							<span class="alg-hit__currentprice">
								{{data.formatted_price}}<# if((data.product_type === 'variable' || data.product_type === 'grouped') && data.price !== data.max_price) { #>-{{data.formatted_max_price}}
								<# } #>
							</span>
						</p>
						<# var rating_percentage = Math.round(data.average_rating * 2 * 10); #>

						<div class="alg-stars">
							<# if(data.rating_count > 0) { #>
							&#x2606&#x2606&#x2606&#x2606&#x2606
								<span class="alg-rating" style="width:{{rating_percentage}}%;">&#x2605&#x2605&#x2605&#x2605&#x2605</span>
							<# } else { #>
								&nbsp;
							<# } #>
						</div>

					</div>
				</div>
			</article>
	</script>

	<script type="text/html" id="tmpl-stats">
		<div class="alg-stats">
			<span class="alg-hide-on-xs">{{ algolia.woocommerce.i18n.we_found }}</span>
			{{ data.nbHits }} {{ algolia.woocommerce.i18n.products }}
			<# if(data.query.length > 0) { #>
			{{ algolia.woocommerce.i18n.matching }} "<span class="alg-primary-color">{{ data.query }}</span>"
			<# } #>

			{{ algolia.woocommerce.i18n.in }} <span class="alg-primary-color">{{ data.processingTimeMS }} ms</span>

			<# if(algolia.powered_by_enabled === true) { #>
			{{ algolia.woocommerce.i18n.with }} <span class="alg-powered-by">Algolia</span>
			<# } #>

		</div>

	</script>

	<script type="text/javascript">
		jQuery(function($) {
			var container = $(algolia.woocommerce.selector);

			if(container.length === 0 && $('.admin-bar').length > 0) {
				alert(algolia.woocommerce.i18n.invalid_selector_notice);
				return;
			}

			if (algolia.woocommerce.replace_page === false) {
				/* Search will not be displayed by default so we need to keep a reference to the original content. */
				var search_container = container.clone().html(wp.template('instantsearch'));
				search_container.hide();
				search_container.insertAfter(container);
			} else {
				var search_container = container.html(wp.template('instantsearch'));
			}


			if($('#algolia-search-box').length === 0) {
				console.log('Unable to find the node to add instantsearch.');
				return;
			}

			if (algolia.indices.posts_product === undefined && $('.admin-bar').length > 0) {
				alert(algolia.woocommerce.i18n.products_not_indexed_notice);
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

                    if (search.helper.state.query.length > 0 || algolia.woocommerce.replace_page !== false) {
                        helper.search();
                    } else {
                        search_container.hide();
                        container.show();
                        update_container_class();
                    }
                }
			});

			var sort_by_indices = [];

			for(var i in algolia.woocommerce.sort_by) {
				sort_by_indices.push({
					'name': algolia.woocommerce.sort_by[i].index_name,
					'label': algolia.woocommerce.sort_by[i].display_name
				});
			}

			if (sort_by_indices.length > 1) {
				search.addWidget(
					instantsearch.widgets.sortBySelector({
						container: '#algolia-sort-by',
						indices: sort_by_indices
					})
				);
			}

			search.addWidget({
				init: function(options) {
					if(window.location.hash.length > 0) {
						return;
					}

					/* Get the initial from the query string "s" parameter if no hash is present. */
					if (algolia.query.length > 0) {
						options.helper.setQuery(algolia.query);
					}

					/* Set the current category if no anchor is already present. */
					if(algolia.woocommerce.page === 'category') {
						options.helper.toggleRefine('taxonomies_hierarchical.product_cat.lvl0', algolia.woocommerce.category);
					}

					/* Set the current tag if no anchor is already present. */
					if(algolia.woocommerce.page === 'tag') {
						options.helper.toggleRefine('taxonomies.product_tag', algolia.woocommerce.tag);
					}
				}
			});

			/* Bind existing search inputs. */
			var $theme_search_inputs = $('input[name="s"]');
			$theme_search_inputs.on('keyup', handleSearchInputKeyUp);
			$theme_search_inputs.attr('autocomplete', 'off');

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
			if ( $theme_search_inputs.length === 0 || algolia.woocommerce.replace_page === true ) {
				search.addWidget(
					instantsearch.widgets.searchBox({
						container: '#algolia-search-box',
						placeholder: algolia.woocommerce.i18n.search_input_placeholder,
						wrapInput: false
					})
				);
			} else {
				$('#algolia-search-box').hide();
			}
			if ( $theme_search_inputs.length > 0 ) {
				search.addWidget({
					init: function() {
						$theme_search_inputs.val(search.helper.state.query);
					},
					render: function(results) {
						if(algolia.woocommerce.replace_page === false) {
							if(results.state.query.length > 0) {
								container.hide();
								search_container.show();
								update_container_class();
							} else {
								search_container.hide();
								container.show();
								update_container_class();
							}
						}
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
						empty: algolia.woocommerce.i18n.no_results_template,
						item: wp.template('instantsearch-hit')
					},
					transformData: {
						item: function(data) {
							data.formatted_price = format_price(data.price);
							data.formatted_max_price = format_price(data.max_price);
							data.formatted_regular_price = format_price(data.regular_price);

							return data;
						}
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
						header: '<h4>' + algolia.woocommerce.i18n.product_categories + '</h4>'
					}
				})
			);

			var $attributes_container = $('#ais-wc-attributes');
			for ( var i in algolia.woocommerce.attributes_for_faceting ) {
				var attribute_name = algolia.woocommerce.attributes_for_faceting[i]['attribute_name'];
				var attribute_label = algolia.woocommerce.attributes_for_faceting[i]['attribute_label'];
				var attribute_type = algolia.woocommerce.attributes_for_faceting[i]['attribute_type'];
				var attribute_operator = algolia.woocommerce.attributes_for_faceting[i]['operator'];
				var attribute_limit = algolia.woocommerce.attributes_for_faceting[i]['limit'];
				var attribute_show_more = algolia.woocommerce.attributes_for_faceting[i]['show_more'];
				var attribute_sort_by = algolia.woocommerce.attributes_for_faceting[i]['sort_by'];

				$attributes_container.append( '<section class="ais-facets" id="facet-attribute-' + attribute_name + '"></section>' );

				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#facet-attribute-' + attribute_name,
						attributeName: 'taxonomies.pa_' + attribute_name,
						operator: attribute_operator,
						limit: attribute_limit,
						showMore: attribute_show_more,
						sortBy: attribute_sort_by,
						templates: {
							header: '<h4>' + algolia.woocommerce.i18n.filter_by + ' ' + attribute_label + '</h4>'
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
						header: '<h4>' + algolia.woocommerce.i18n.filter_by_tag + '</h4>'
					}
				})
			);

			/* Price range slider refinement widget */
			search.addWidget(
				instantsearch.widgets.rangeSlider({
					container: '#facet-price',
					attributeName: 'price',
					templates: {
						header: '<h4>' + algolia.woocommerce.i18n.filter_by_price + '</h4>'
					},
					tooltips: {
						format: function (rawValue) {
							return format_price(rawValue);
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

			search.addWidget(
				instantsearch.widgets.currentRefinedValues({
					container: '#alg-current-refinements',
					clearAll: 'after',
					templates: {
						item: '<div class="ais-current-value">{{name}}<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.971 47.971"><path fill="currentColor" d="M28.228 23.986L47.092 5.122c1.172-1.17 1.172-3.07 0-4.242-1.172-1.172-3.07-1.172-4.242 0L23.986 19.744 5.12.88C3.95-.292 2.05-.292.88.88-.294 2.05-.294 3.95.88 5.122l18.864 18.864L.88 42.85c-1.173 1.17-1.173 3.07 0 4.242.585.585 1.353.878 2.12.878s1.535-.293 2.12-.88l18.866-18.863L42.85 47.09c.586.587 1.354.88 2.12.88s1.536-.293 2.122-.88c1.172-1.17 1.172-3.07 0-4.24L28.228 23.985z"/></svg></div>'
					}
				})
			);

			/* Start */
			search.start();
			if (algolia.woocommerce.replace_page === true || window.location.hash.length > 0) {
				container.show();
			}

			/* Handle swipe */
			var $facets = $('#ais-facets');
			var $wrapper = $('.ais-facets__wrapper');

			var start = 0;
			var current = 0;
			var cardPos = 0;
			var delta;
			var dragging = true;
 			var BCR = $facets[0].getBoundingClientRect();

			function onTouchStart(event){
				var event = event.originalEvent;
				start = event.pageX || event.touches[0].pageX;
			}

			function onTouchMove(event){
				var event = event.originalEvent;
				current = event.pageX || event.touches[0].pageX;
				cardPos = current - start;
				var opacity = 1 - (cardPos / BCR.width);

				if(cardPos >= 0){
					$facets.css({
						"will-change": "transform",
						"transition": "none",
						"transform": "translate("+cardPos+"px,0)",
						"opacity": opacity
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

			var overflow;

			$('.ais-facets__wrapper').on('click',function(e){
				e.stopPropagation();
			});

			$('#algolia-mobile-filters button').on('click',function(e) {
				e.stopPropagation();
				overflow = document.body.style.overflow;
				$facets.addClass('ais-facets--visible');
				document.body.style.overflow = "hidden";
			});

			$wrapper.on('click',function(e){
				e.stopPropagation();
			});

			$(document).on('click',function(event){
				$facets.removeClass('ais-facets--visible');
				document.body.style.overflow = overflow;
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
				var width = search_container.outerWidth();
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
				search_container.removeClass('alg-container--xs alg-container--sm alg-container--md alg-container--lg alg-container--xl');
				search_container.addClass(containerClass);
			}
			update_container_class();
		});

		/* Format price */
		function format_price(number) {
			var decimals = algolia.woocommerce.number_decimals;
			var dec_point = algolia.woocommerce.decimal_separator;
			var thousands_sep = algolia.woocommerce.thousands_separator;
			var currency_symbol = algolia.woocommerce.currency_symbol;
			var currency_position = algolia.woocommerce.currency_position;
			
			var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				toFixedFix = function (n, prec) {
					// Fix for IE parseFloat(0.55).toFixed(0) = 0;
					var k = Math.pow(10, prec);
					return Math.round(n * k) / k;
				},
				s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
			if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			}
			if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
			}
			var formatted = s.join(dec);

			if(currency_position === 'left') {
				return currency_symbol + formatted;
			}
			if(currency_position === 'left_space') {
				return currency_symbol + " " + formatted;
			}
			if(currency_position === 'right') {
				return formatted + currency_symbol;
			}

			return formatted + " " + currency_symbol;
		}

	</script>

<?php get_footer(); ?>
