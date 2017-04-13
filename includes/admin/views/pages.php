<?php include 'header.php'; ?>

<section class="alg-main__section alg-main__section--pages">
	<header class="alg-main__header">
		<h1>What pages would you like to be powered by Algolia?</h1>
		<p>The selected page content will be replaced with an instant search experience.</p>
	</header>
	<form method="post">
		<div class="alg-main__container alg-main__container--pages">
			<article class="alg-pageselect alg-pageselect--blue">
				<svg class="alg-pageselect__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 171.3 134.3">
					<style>
						.st0 {
							fill: none;
							stroke: #32C0F9;
							stroke-miterlimit: 10;
						}
					</style>
					<path class="st0" d="M104.8 104.3H28.6c-1.3 0-2.3-1-2.3-2.3V21.6c0-1.3 1-2.3 2.3-2.3h30.8c.7 0 1.4.3 1.8.9l7 9.2c.4.6 1.1.9 1.8.9h59c1.3 0 2.3 1 2.3 2.3v31.2" />
					<circle class="st0" cx="132.8" cy="93.8" r="19.5" />
					<path class="st0" d="M146.7 107.8l6.7 6.7" />
				</svg>
				<div class="alg-pageselect__content">
					<h2>Category pages</h2>
					<p>The pages that list the products falling under a given category. The active category will be pre-refined in the search.</p>
					<div class="alg-pageselect__button">
						<input id="category" type="checkbox" name="pages[]" value="category" <?php if ( in_array( 'category', $pages ) ) echo 'checked'; ?> >
						<label for="category">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.8 16.8" width="14" height="14">
								<style>
									.alg-checkmarkstyles {
										stroke-miterlimit: 10;
										fill:transparent;
									}
								</style>
								<path class="alg-checkmarkstyles checkmark__frame" stroke="currentColor" d="M14.9 6.9v6.6c0 .9-.7 1.6-1.6 1.6H3.4c-.9 0-1.6-.7-1.6-1.6V3.7c0-.9.7-1.7 1.6-1.7h7.4" />
								<path class="alg-checkmarkstyles checkmark__mark" stroke="currentColor" d="M4.3 9.3l2.6 2.6 7.7-9.5" />
							</svg>
							CATEGORY PAGE</label>
						</div>
				</div>
			</article>
			<div class="alg-pageselect__gutter"></div>
			<article class="alg-pageselect alg-pageselect--purple">
				<svg class="alg-pageselect__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 171.3 134.3">
					<style>
						.st01 {
							fill: none;
							stroke: #8023E2;
							stroke-miterlimit: 10;
						}
					</style>
					<path class="st01" d="M101.3 102.8l-13.1 13.1c-.8.8-2.1.8-2.9 0L29 59.6c-.4-.4-.6-.9-.6-1.4l.3-33.2c0-1.1.9-2 2-2l33.3-.4c.5 0 1.1.2 1.4.6L113 70.8" />
					<ellipse transform="rotate(-45 47.955 42.418)" class="st01" cx="48" cy="42.4" rx="7.5" ry="7.5" />
					<circle class="st01" cx="130" cy="95.4" r="19.5" />
					<path class="st01" d="M143.9 109.4l6.7 6.7" />
				</svg>
				<div class="alg-pageselect__content">
					<h2>Tag pages</h2>
					<p>The pages that list the products falling under a given tag.</p>
					<div class="alg-pageselect__button">
						<input id="tag" type="checkbox" name="pages[]" value="tag" <?php if ( in_array( 'tag', $pages ) ) echo 'checked'; ?>>
						<label for="tag">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.8 16.8" width="14" height="14">
								<style>
									.alg-checkmarkstyles {
										stroke-miterlimit: 10;
										fill:transparent;
									}
								</style>
								<path class="alg-checkmarkstyles checkmark__frame" stroke="currentColor" d="M14.9 6.9v6.6c0 .9-.7 1.6-1.6 1.6H3.4c-.9 0-1.6-.7-1.6-1.6V3.7c0-.9.7-1.7 1.6-1.7h7.4" />
								<path class="alg-checkmarkstyles checkmark__mark" stroke="currentColor" d="M4.3 9.3l2.6 2.6 7.7-9.5" />
							</svg>
							TAG PAGE</label>
						</div>
				</div>
			</article>
			<div class="alg-pageselect__gutter"></div>
			<article class="alg-pageselect alg-pageselect--red">
				<svg class="alg-pageselect__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 171.3 134.3">
					<style>
						.st02 {
							fill: none;
							stroke: #FF3167;
							stroke-miterlimit: 10;
						}
					</style>
					<path class="st02" d="M116 112.3H25.5v-89c0-2.9 2.4-5.3 5.3-5.3h104.6c2.9 0 5.3 2.4 5.3 5.3v44.6M7.4 112.3H26M26 101.4h87.8" />
					<circle class="st02" cx="144.4" cy="98.5" r="19.5" />
					<path class="st02" d="M158.3 112.4l6.7 6.7" />
				</svg>
				<div class="alg-pageselect__content">
					<h2>Search page</h2>
					<p>Overrides the default WooCommerce search page with an instant search experience powered by Algolia.</p>
					<div class="alg-pageselect__button">
						<input id="search" type="checkbox" name="pages[]" value="search" <?php if ( in_array( 'search', $pages ) ) echo 'checked'; ?>>
						<label for="search">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.8 16.8" width="14" height="14">
								<style>
									.alg-checkmarkstyles {
										stroke-miterlimit: 10;
										fill:transparent;
									}
								</style>
								<path class="alg-checkmarkstyles checkmark__frame" stroke="currentColor" d="M14.9 6.9v6.6c0 .9-.7 1.6-1.6 1.6H3.4c-.9 0-1.6-.7-1.6-1.6V3.7c0-.9.7-1.7 1.6-1.7h7.4" />
								<path class="alg-checkmarkstyles checkmark__mark" stroke="currentColor" d="M4.3 9.3l2.6 2.6 7.7-9.5" />
							</svg>
							SEARCH PAGE</label>
					</div>
				</div>
			</article>
		</div>
		<input type="hidden" name="submitted" value="true">
		<button class="alg-button alg-button--green" type="submit">SAVE CHANGES</button>
	</form>
</section>

<?php include('footer.php'); ?>
