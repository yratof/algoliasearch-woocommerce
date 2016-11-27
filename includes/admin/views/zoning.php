<div class="wrap">
	<h1>WooCommerce</h1>
</div>

<?php include 'tabs.php'; ?>

<?php if ( isset( $message ) ): ?>
	<div class="updated notice is-dismissible">
		<p><strong><?php echo $message; ?></strong></div>
<?php endif; ?>

<div class="wrap">
	<form method="post">
		<div>
			<input type="text" value="<?php echo esc_html( $selector ); ?>" name="selector" id="algolia-selector" />
		</div>

		<input type="hidden" name="submitted" value="true">

		<input type="submit" value="Save" class="button button-primary button-large">
	</form>
</div>

<div class="wrap">
	<iframe src="<?php echo esc_url( $iframe_url ); ?>" id="selector-frame"></iframe>
</div>

<script>
	jQuery(document).ready(function($){

	});
</script>
