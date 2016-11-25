<div class="wrap">
	<h1>WooCommerce</h1>
</div>

<?php include 'tabs.php'; ?>

<?php if ( isset( $message ) ): ?>
	<div class="updated notice is-dismissible">
		<p><strong><?php echo $message; ?></strong></div>
<?php endif; ?>

<form method="post">
	<div>
		<input type="text" value="<?php echo esc_html( $primary_color ); ?>" name="primary_color" class="color-picker" />
	</div>
	
	<input type="hidden" name="submitted" value="true">

	<input type="submit" value="Save" class="button button-primary button-large">
</form>

<script>
	jQuery(document).ready(function($){

	});
</script>
