<div class="wrap">
	<h1>WooCommerce</h1>
</div>

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
		var options = {
			// you can declare a default color here,
			// or in the data-default-color attribute on the input
			defaultColor: '<?php echo esc_html( $primary_color ); ?>',
			// a callback to fire whenever the color changes to a valid color
			change: function(event, ui){},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {},
			// hide the color picker controls on load
			hide: true,
			// show a group of common colors beneath the square
			// or, supply an array of colors to customize further
			palettes: true
		};

		$('.color-picker').wpColorPicker(options);
	});
</script>
