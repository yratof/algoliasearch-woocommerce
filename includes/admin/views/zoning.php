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
			<input id="algolia-selector" class="alg-zoninginput" type="text" name="selector" value="<?php echo esc_html( $selector ); ?>">
			<div class="alg-main__chrome">
				<img class="alg-main__chromeimage" src="/wp-content/plugins/algoliasearch-woocommerce/assets/img/chrome-browser.png">
				<iframe class="alg-main__iframe" src="<?php echo esc_url( $iframe_url ); ?>"></iframe>
			</div>
		</div>
	</form>
</section>

<?php include 'footer.php'; ?>
