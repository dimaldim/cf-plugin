<div class="wrap">
	<h1>DX Cloudflare Settings</h1>
	<div>
		<h4>Cloudflare status: <span class="dx-cf-status <?php echo $status['class']; ?>"><?php echo $status['status']; ?></span></h4>
	</div>
	<form method="post">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="cf_email"><?php _e( 'Email', 'dxcf' ) ?></label></th>
				<td><input name="cf_email" type="email" id="cf_email" value="<?php echo $api_email; ?>"
						   class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="cf_api_key"><?php _e( 'API Key', 'dxcf' ) ?></label></th>
				<td><input name="cf_api_key" type="text" id="cf_api_key" value="<?php echo $api_key; ?>"
						   class="regular-text"></td>
			</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'dxcf' ); ?>
		<?php submit_button( __( 'Save', 'dxcf' ) ); ?>
	</form>
</div>
