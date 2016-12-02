
<?php include 'header.php'; ?>

<section class="alg-main__section alg-main__section--appearance">
	<header class="alg-main__header">
		<h1>Customize the appearance of your search experience</h1>
		<p>Pick a color to make the search experience fit even better into your website!</p>
	</header>
	<div class="alg-resultscontainer">
		<div class="alg-comparison">
			<div class="alg-hitwrapper">
				<p class="alg-hitwrapper__result">REGULAR RESULT</p>
				<!-- IMPORTANT -->
				<!-- Remove nohover class for production from alg-hit element-->
				<article class="alg-hit nohover">
					<div class="alg-hit__content">
						<figure>
							<img src="http://rlv.zcache.com/i_code_developer_hoodie-r4a6b7efb45ca40568e6aea25a6725725_jg5fo_324.jpg">
							<div class="alg-hit__ribbon">
								SALE
							</div>
						</figure>
						<div class="alg-hit__details">
							<h2 class="alg-hit__title" itemprop="name headline">Ninja Silhouette</h2>
							<p class="alg-hit__description">CLOTHING, HOODIES</p>
							<p class="alg-hit__priceholder">
								<span class="alg-hit__previousprice">$50</span>
								<span class="alg-hit__currentprice">$35-45</span>
							</p>
						</div>
					</div>
				</article>
			</div>
			<div class="alg-hitwrapper">
				<p class="alg-hitwrapper__result">HOVER RESULT</p>
				<!-- IMPORTANT -->
				<!-- Remove forcehover class for production from alg-hit element-->
				<article class="alg-hit forcehover">
					<div class="alg-hit__content">
						<figure>
							<img src="http://rlv.zcache.com/i_code_developer_hoodie-r4a6b7efb45ca40568e6aea25a6725725_jg5fo_324.jpg">
							<div class="alg-hit__ribbon">
								SALE
							</div>
							<div class="alg-hit__overlay">
								<div class="alg-hit__actions">
									<a href="#" class="alg-cta--transparent alg-button--small">VIEW DETAILS</a>
									<a href="#" class="alg-cta--blue alg-button--small alg-button--themebutton">ADD TO CART</a>
								</div>
							</div>
						</figure>
						<div class="alg-hit__details">
							<h2 class="alg-hit__title" itemprop="name headline">Ninja Silhouette</h2>
							<p class="alg-hit__description">CLOTHING, HOODIES</p>
							<p class="alg-hit__priceholder">
								<span class="alg-hit__previousprice">$50</span>
								<span class="alg-hit__currentprice">$35-45</span>
							</p>
						</div>
					</div>
				</article>
			</div>
		</div>
		<form method="post">
			<div class="alg-resultcolors">
				<input type="text" value="<?php echo esc_html( $primary_color ); ?>" name="primary_color" class="color-picker" />
				<!--button class="alg-button--small">PICK COLOR</button-->
				<input type="hidden" name="submitted" value="true">
			</div>
			<button class="alg-button alg-button--green">SAVE CHANGES</button>
		</form>
	</div>
</section>

<script>
	jQuery(document).ready(function($){
		var options = {
			// you can declare a default color here,
			// or in the data-default-color attribute on the input
			defaultColor: '<?php echo esc_html( $primary_color ); ?>',
			// a callback to fire whenever the color changes to a valid color
			change: function(event, ui){
				setColor(ui.color.toString());
			},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {},
			// hide the color picker controls on load
			hide: true,
			// show a group of common colors beneath the square
			// or, supply an array of colors to customize further
			palettes: [
				'#050f2c',
				'#003666',
				'#00aeff',
				'#3369e7',
				'#8e43e7',
				'#b84592',
				'#ff4f81',
				'#ff6c5f',
				'#ffc168',
				'#2dde98',
				'#1cc7d0'
			]
		};

		$('.color-picker').wpColorPicker(options);

		var priceTags = document.querySelectorAll('.alg-hit__currentprice');
		var saleRibbon = document.querySelector('.alg-hit__ribbon');
		var button = document.querySelector('.alg-button--themebutton');

		function setColor(color){
			for(var i = 0; i < priceTags.length; i++){
				priceTags[i].style.color = color;
			}
			saleRibbon.style.backgroundColor = color;
			button.style.backgroundColor = color;
			button.style.border = "1px solid " + color;
		}
		setColor('<?php echo esc_html( $primary_color ); ?>');
	});
</script>

<?php include('footer.php'); ?>
