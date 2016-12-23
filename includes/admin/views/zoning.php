<?php include 'header.php'; ?>

<section class="alg-main__section alg-main__section--zoning">
	<form method="post">
		<header class="alg-main__header">
			<h1>In what section of your website would you like to see the search?</h1>
			<p>
				Select the container by clicking where you would like to see your search experience.
				<br>The background will turn blue once you confirmed your selection by clicking.
				<br>We will inject a fully featured search experiences with filters where the background is blue.
			</p>
			<input type="hidden" name="submitted" value="true">
			<button class="alg-button alg-button--green">SAVE CHANGES</button>
		</header>
		<div class="alg-main__container alg-main__container--zoning">
			<div class="alg-zoning__container">
				<input id="algolia-selector" class="alg-zoninginput" type="text" name="selector" value="<?php echo esc_html( $selector ); ?>">
				<div class="alg-zoning__zoningtooltip">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.667 426.667"><path d="M192 298.667h42.667v42.667H192z"/><path d="M213.333 0C95.513 0 0 95.513 0 213.333s95.513 213.333 213.333 213.333 213.333-95.513 213.333-213.333S331.154 0 213.333 0zm0 388.053c-96.495 0-174.72-78.225-174.72-174.72s78.225-174.72 174.72-174.72c96.446.117 174.602 78.273 174.72 174.72 0 96.496-78.224 174.72-174.72 174.72z"/><path d="M296.32 150.4c-10.974-45.833-57.025-74.09-102.858-63.117-38.533 9.226-65.646 43.762-65.462 83.384h42.667c2.003-23.564 22.73-41.043 46.293-39.04s41.043 22.73 39.04 46.293c-4.358 21.204-23.38 36.17-45.013 35.413-10.486 0-18.987 8.5-18.987 18.987v45.013h42.667v-24.32c45.12-11.635 72.565-57.312 61.653-102.613z"/></svg>
					<div class="alg-zoning__tip">
					 <p>The DOM selector where your search will be injecting in.</p>
					</div>
				</div>
			</div>
			<div class="alg-main__chrome">
				<img class="alg-main__chromeimage" src="<?php echo ALGOLIA_WOOCOMMERCE_URL; ?>assets/img/chrome-browser.png">
				<iframe class="alg-main__iframe" src="<?php echo esc_url( $iframe_url ); ?>"></iframe>
			</div>
		</div>
	</form>
</section>

<?php include 'footer.php'; ?>
