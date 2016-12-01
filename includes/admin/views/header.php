

<div class="alg-wrap">
	<?php if ( isset( $message ) ): ?>
		<div class="updated notice is-dismissible">
		<p><strong><?php echo $message; ?></strong></div>
	<?php endif; ?>

	<aside class="alg-sidebar">
		<div class="alg-sidebar__brand">
			<div class="alg-sidebar__brandwrapper">
				<img src="/wp-content/plugins/algoliasearch-woocommerce/assets/img/algolia-logo.png">
				<span>For WooCommerce</span>
			</div>
		</div>
		<ul class="alg-sidebar__list">
			<li class="alg-sidebar__link <?php if ( ! isset( $_GET['tab'] ) || $_GET['tab'] === 'pages'): ?>alg-sidebar__link--active<?php endif; ?>">
				<a href="admin.php?page=algolia-woocommerce">
					Pages
					<span class="alg-sidebar__state">NOT SET</span>
				</a>
			</li>
			<li class="alg-sidebar__link alg-sidebar__link--set <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'zoning'): ?>alg-sidebar__link--active<?php endif; ?>">
				<a href="admin.php?page=algolia-woocommerce&tab=zoning">
					Zoning
					<span class="alg-sidebar__state">OK</span>
				</a>
			</li>
			<li class="alg-sidebar__link <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'appearance'): ?>alg-sidebar__link--active<?php endif; ?>">
				<a href="admin.php?page=algolia-woocommerce&tab=appearance">
					Appearance
					<span class="alg-sidebar__state">NOT SET</span>
				</a>
			</li>
		</ul>
	</aside>

	<main class="alg-main">
		<div class="alg-main__wrap">
