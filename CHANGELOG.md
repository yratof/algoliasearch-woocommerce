# 0.5.1
- Adapt design of the pagination

# 0.5.0
- Reduce price font size on mobile
- Make search statistics fit on mobile device
- Make sure slider price tooltips do not break to multiple lines
- Only replace page with instantsearch.js according to configuration
- Avoid flickering when using :first or :last in zoning selector
- Only avoid flickering on pages where instantsearch.js should replace the whole page
- Disable typo tolerance on SKUs
- Ensure rating stars display correctly even if aligned in center or right

# 0.4.0
- Add a filter hook to allow filtering attributes used in faceting
- Make the number of products displayed consistent across all the pages
- Add stars rating to the products display
- Make sure product block height is consistent even if the product has no category
- Fix upper bound price positioning on the range slider widget
- Make sure everything is always aligned left on the faceting sidebar
- Index product skus and make them searchable
- Remove javascript notification in frontend on pages where the instantsearch container didn't match anything
- Avoid the default theme being displayed on page initial load
- Index the fact that products are featured or not and use it in the custom ranking as first rule

# 0.3.0
- Reserve 2 lines for displaying the product title
- Update the default styles of the search
- Display 2 products by default on xs screens
- Initialize the search input values with the current query
- Hide lined-through price if on a variable product
- Add default margins to the search container
- Format the prices according to WooCommerce settings
- Display search box on category/tags and search pages by default
- Ensure filters are always displayed above everything else on mobile
- Ensure the filters sidebar respects it's size
- Display current refinements
- Fix different displays of sort by / filter by buttons
- Ensure the zoning iframe points to the correct URL when permalinks are using URL re-writing

# 0.0.2
- Make sure the admin doesn't break if the Algolia plugin for WordPress is not installed yet
