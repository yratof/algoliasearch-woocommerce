<div class="wrap">
	<h1>WooCommerce</h1>
</div>

<?php if ( isset( $message ) ): ?>
	<div class="updated notice is-dismissible">
		<p><strong><?php echo $message; ?></strong></div>
<?php endif; ?>

<form method="post">
	<div>
		<label>
			<input type="checkbox" name="pages[]" value="category" <?php if ( in_array( 'category', $pages ) ) echo 'checked'; ?> >
			Category
		</label>
	</div>
	<div>
		<label>
			<input type="checkbox" name="pages[]" value="tag" <?php if ( in_array( 'tag', $pages ) ) echo 'checked'; ?> >
			Tag
		</label>
	</div>
	<div>
		<label>
			<input type="checkbox" name="pages[]" value="search" <?php if ( in_array( 'search', $pages ) ) echo 'checked'; ?> >
			Search
		</label>
	</div>
	
	<input type="hidden" name="update_pages" value="true">

	<input type="submit" value="Save" class="button button-primary button-large">
</form>
