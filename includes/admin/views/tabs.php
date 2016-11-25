<ul>
	<li><a href="admin.php?page=algolia-woocommerce" <?php if ( ! isset( $_GET['tab'] ) || $_GET['tab'] === 'pages'): ?>class="active"<?php endif; ?> >Pages</a></li>
	<li><a href="admin.php?page=algolia-woocommerce&tab=zoning" <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'zoning'): ?>class="active"<?php endif; ?> >Zoning</a></li>
	<li><a href="admin.php?page=algolia-woocommerce&tab=appearance" <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'appearance'): ?>class="active"<?php endif; ?> >Appearance</a></li>
</ul>
