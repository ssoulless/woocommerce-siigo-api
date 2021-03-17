<div class="wrap">
	<h1>Woocommerce Siigo API</h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'woocommerce_siigo_api_plugin_settings' );
			do_settings_sections( 'woocommerce_siigo_api_plugin' );
			submit_button();
		?>
	</form>
</div>