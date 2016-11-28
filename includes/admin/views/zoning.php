<?php include 'header.php'; ?>

<section class="alg-main__section alg-main__section--zoning">
	<form method="post">
		<header class="alg-main__header">
			<h1>Zoning position of search bar</h1>
			<p>Lorem ipsum dolor sit amet et quantuum mergitur. Lorem ipsum dolor sit amet et quantuum mergitur.</p>
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
