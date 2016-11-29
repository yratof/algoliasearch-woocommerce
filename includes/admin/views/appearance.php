
<?php include 'header.php'; ?>

<?php if ( isset( $message ) ): ?>
	<div class="updated notice is-dismissible">
		<p><strong><?php echo $message; ?></strong></div>
<?php endif; ?>

<section class="alg-main__section alg-main__section--appearance">
	<header class="alg-main__header">
		<h1>Customize result appearance</h1>
		<p>Lorem ipsum dolor sit amet et quantuum mergitur. Lorem ipsum dolor sit amet et quantuum mergitur.</p>
	</header>
	<div class="alg-resultscontainer">
		<div class="alg-comparison">
			<div class="alg-hitwrapper">
				<p class="alg-hitwrapper__result">REGULAR RESULT</p>
				<!-- IMPORTANT -->
				<!-- Remove nohover class for production from alg-hit element-->
				<div class="alg-hit nohover">
					<figure>
						<img src="http://rlv.zcache.com/i_code_developer_hoodie-r4a6b7efb45ca40568e6aea25a6725725_jg5fo_324.jpg">
						<div class="alg-hit__ribbon">
							SALE
						</div>
					</figure>
					<div class="alg-hit__details">
						<p class="alg-hit__title">Ninja Silhouette</p>
						<p class="alg-hit__description">CLOTHING, HOODIES</p>
						<p class="alg-hit__priceholder">
							<span class="alg-hit__previousprice">$50</span>
							<span class="alg-hit__currentprice">$35-45</span>
						</p>
					</div>
				</div>
			</div>
			<div class="alg-hitwrapper">
				<p class="alg-hitwrapper__result">HOVER RESULT</p>
				<!-- IMPORTANT -->
				<!-- Remove forcehover class for production from alg-hit element-->
				<div class="alg-hit forcehover">
					<figure>
						<img src="http://rlv.zcache.com/i_code_developer_hoodie-r4a6b7efb45ca40568e6aea25a6725725_jg5fo_324.jpg">
						<div class="alg-hit__ribbon">
							SALE
						</div>
						<div class="alg-hit__overlay">
							<div class="alg-hit__actions">
								<button class="alg-button--small">VIEW DETAILS</button>
								<button class="alg-button--small alg-button--themebutton">ADD TO CART</button>
							</div>
						</div>
					</figure>
					<div class="alg-hit__details">
						<p class="alg-hit__title">Ninja Silhouette</p>
						<p class="alg-hit__description">CLOTHING, HOODIES</p>
						<p class="alg-hit__priceholder">
							<span class="alg-hit__previousprice">$50</span>
							<span class="alg-hit__currentprice">$35-45</span>
						</p>
					</div>
				</div>
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
				setColor(event.target.value);
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
