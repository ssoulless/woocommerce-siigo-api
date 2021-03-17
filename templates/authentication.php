<div class="wrap">
	<h1><?php echo __('Autenticación Siigo', 'woocommerce_siigo_api'); ?></h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'woocommerce_siigo_api_plugin_authentication' );
			do_settings_sections( 'woocommerce_siigo_api_authentication' );
			submit_button();
		?>
	</form>
</div>