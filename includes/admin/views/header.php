<div class="alg-wrap">
	<?php if ( isset( $message ) ): ?>
		<div class="updated notice is-dismissible">
		<p><strong><?php echo $message; ?></strong></div>
	<?php endif; ?>

	<aside class="alg-sidebar">
		<div class="alg-sidebar__brand">
			<div class="alg-sidebar__brandwrapper">
				<img src="<?php echo ALGOLIA_WOOCOMMERCE_URL; ?>assets/img/algolia-logo.png">
				<span>For WooCommerce</span>
			</div>
		</div>
		<ul class="alg-sidebar__list">
			<li class="alg-sidebar__link <?php if ( ! isset( $_GET['tab'] ) || $_GET['tab'] === 'pages'): ?>alg-sidebar__link--active<?php endif; ?>">
				<a href="admin.php?page=algolia-woocommerce">
					Pages
					<?php if ( aw_is_configured_pages() ): ?>
						<span class="alg-sidebar__state alg-sidebar__state--set">OK</span>
					<?php else: ?>
						<span class="alg-sidebar__state">NOT SET</span>
					<?php endif; ?>
				</a>
			</li>
			<li class="alg-sidebar__link <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'zoning'): ?>alg-sidebar__link--active<?php endif; ?>">
				<a href="admin.php?page=algolia-woocommerce&tab=zoning">
					Zoning
					<?php if ( aw_is_configured_zoning() ): ?>
						<span class="alg-sidebar__state alg-sidebar__state--set">OK</span>
					<?php else: ?>
						<span class="alg-sidebar__state">NOT SET</span>
					<?php endif; ?>
				</a>
			</li>
			<li class="alg-sidebar__link <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'appearance'): ?>alg-sidebar__link--active<?php endif; ?>">
				<a href="admin.php?page=algolia-woocommerce&tab=appearance">
					Appearance
					<?php if ( aw_is_configured_appearance() ): ?>
						<span class="alg-sidebar__state alg-sidebar__state--set">OK</span>
					<?php else: ?>
						<span class="alg-sidebar__state">NOT SET</span>
					<?php endif; ?>

				</a>
			</li>
		</ul>
	</aside>

	<main class="alg-main">
		<div class="alg-main__wrap">
