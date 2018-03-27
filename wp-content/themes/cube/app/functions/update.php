<?php
flo_delete_updater_cache();

/**
 * Register Theme Key 
 */
function flo_init_updater() {

	register_setting('flotheme-updater', 'flotheme_theme_key', 'flo_updater_theme_key_sanitizer');

}
add_action('admin_init', 'flo_init_updater');

function flo_check_for_update() {

	if (isset($_GET['page']) && $_GET['page'] == 'flotheme_updater' && isset($_POST['check_for_update']) && $_POST['check_for_update']) {
		flo_delete_updater_cache();
		wp_redirect(admin_url('admin.php?page=flotheme_updater'));
	}
}
add_action('admin_init', 'flo_check_for_update');

/**
 * Sanitize theme key and drop theme updater.
 * 
 * @param string $val
 * @return string
 */
function flo_updater_theme_key_sanitizer($val) {
	flo_delete_updater_cache();

	// send sequest to the FLO Stats API
	flo_stats_request('PATCH', $activation_key = trim($val));

	return trim($val);
}

/**
 * Get filtered theme key
 * 
 * @return string
 */
function flo_get_theme_key() {
	return trim(get_option('flotheme_theme_key'));
}

/**
 * Delete theme updater cache and load new one 
 */
function flo_delete_updater_cache() {
	set_site_transient('update_themes', null);	
}

/**
 * Updater's page 
 */
function flotheme_updater_page() {
?>
	<?php if ( isset( $_GET['settings-updated'] ) ): ?>
		<div class='updated'><p><?php _e('Flotheme key settings updated successfully.', 'flotheme') ?></p></div>
	<?php endif;?>	
		
	<div class="wrap">
		<div id="icon-flotheme" class="icon32"><br/></div>
		<h2><?php _e('Theme Updater', 'flotheme')?></h2>
			<div class="flo-help">
				<h3>Current theme version: <?php echo FLOTHEME_THEME_VERSION ?></h3>
				<form action="options.php" method="post">
					<?php settings_fields('flotheme-updater') ?>
					<?php do_settings_sections('default'); ?>
					<table class="form-table">
						<tbody>
							<tr>
								<th><?php _e('Order ID', 'flotheme') ?></th>
								<td>
									<input type="text" class="regular-text" name="flotheme_theme_key" value="<?php echo flo_get_theme_key(); ?>">
									<span class="description"><?php _e('Enter your purchase order number to receive theme updates', 'flotheme'); ?></span>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<input name="submit" class="button-primary" value="Save" type="submit">
					</p>
				</form>

				<div class="change-log-content">
	            	<h3><?php _e('Change log','flotheme'); ?>:</h3>
	                <?php
	                	$theme_data = wp_get_theme();

	                	$theme_name = $theme_data->Name;

	                	// in case the theme data does not return the correct product name to 
	                	// retrieve the necessary change log, 
	                	// use the filter below to change that
	                	$theme_name = apply_filters('flo_changelog_product_name',$theme_name);
	                	
	                	echo flo_get_change_log($theme_name); 
	                ?>
	            </div>
			</div>
		</div>
	<?php
}

