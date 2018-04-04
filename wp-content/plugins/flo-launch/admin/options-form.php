<div class="flo-test-drive-settings">
	<div class="options-header">
		<div class="title"><?php _e('Flo Launch','flotheme'); ?></div>
		<div class="title-msg description">
			<?php
				if (!get_option('flo_test_drive_tables_created') && !isset($_COOKIE['flo_custom_table_prefix'])){
					_e('* No need for Maintenance Mode, test your new site, while keeping your current site live.','flotheme');
				}

				if (isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) ){
					echo sprintf(__('The Test Drive Mode is %s enabled %s in this browser','flotheme'),'<span class="gold">','</span>' );
				}
			?>
		</div>

		<?php if (isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix'])): ?>
			<div class="test-drive-enabled-instructions dashicons dashicons-info description">
				<?php
					_e("You can activate a different theme and plugins and start
			working on something amazing, while your users will still see your original site.
			In case you want to check your original site, you can disable the Test Drive mode or use another browser.
			If you are going to make the test site your main site in the future, please do not add any new content to
			your original public site because the content is not synchronized between the live and test site.",'flotheme');
				?>
			</div>
		<?php endif ?>
	</div>
	<?php if (get_option('flo_test_test_drive_site_launched')): ?>
		<div class="site-launched-msg">
			<?php _e('Your website was succesfully launched!','flotheme'); ?>
			<br/>
			<span class="description"><?php _e('If you need to use the test mode one more time, please use the buttons below. But please take into consideration<br/> that each time after the site is launched and then the test mode is enabled again, it will create a <br/>new coppy of the Data Base. And doing that many times, will fill your database with a lot of trash.','flotheme'); ?></span>
		</div>
		<!-- ask the user if he wants to test drive aone more time -->
		<!-- and create new default and test prefixes using some random numbers -->
		<!-- and delete flo_test_test_drive_site_launched option -->
	<?php endif ?>
		<ul>
			<?php if (!get_option('flo_test_drive_tables_created') && !isset($_COOKIE['flo_custom_table_prefix'])): ?>
				<li class="flo-backup">
					<span class="icon backup"></span>
					<span class="step-title">
					<?php _e('Create A Database backup','flotheme'); ?>
					</span>
					<span class="description">
						<?php _e('We strongly recommend to make a database backup and save it somewhere.<br/> If you have a backup already, you can skip this step.','flotheme') ?>
					</span>

					<span class="flo-save-bckup btn-wraper">
						<button type="button" class="run-backup" onclick="flo_backup_db();"><?php _e('Create Database backup','flotheme') ?></button>
						<span class="spinner"></span>
					</span>
					<a href=""  id="flo_back_up" hidden download></a>

					<div class="log-response-messages"></div>

				</li>
			<?php endif ?>
			<!-- if tables were not created  -->
			<?php //var_dump(isset($_COOKIE['flo_custom_table_prefix'])); ?>
			<?php if ( (!get_option('flo_test_drive_tables_created') && !isset($_COOKIE['flo_custom_table_prefix'])) || ( get_option('flo_test_drive_tables_created') && !(isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) )) ): ?>
				<li class="flo-enable">
					<span class="icon enable"></span>
					<span class="step-title">
						<?php _e('Enable test mode','flotheme'); ?>
					</span>
					<span class="description">
						<?php _e('It may take a while to create the temporary database.','flotheme') ?>
					</span>
					<span class="flo-start-test-mode btn-wraper">
						<button type="button" class="start-test-mode" onclick="flo_enable_test_drive();"><?php _e('Enable test mode','flotheme') ?></button>
						<span class="spinner"></span>
					</span>
					<div class="log-messages">
						<span class="request-nr">0</span> <!-- add here the request nr -->
						of 
						<span class="total-request"></span> <!-- add here the request nr -->
						<span class="pain-text"> requests completed</span>

					</div>
					<div class="log-error-messages"></div>
					
				</li>
				
			<?php endif ?>
			<?php if ( get_option('flo_test_drive_tables_created') && !(isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) ) ): ?>
				<li class="flo-start-over">
					<span class="icon start-over dashicons-update"></span>
					<span class="step-title">
						<?php _e('Start over','flotheme'); ?>
					</span>
					<span class="description">
						<?php _e('This will delete the currect test site. And you will be able to create a new one and start over.','flotheme') ?>
					</span>
					<span class="flo-start-over btn-wraper">
						<button type="button" class="start-over" onclick="flo_delete_current_test_site();"><?php _e('Delete the test site','flotheme') ?></button>
						<span class="spinner"></span>
					</span>
					<div class="log-messages"></div>
				</li>
			<?php endif ?>	
			<?php if (get_option('flo_test_drive_tables_created') && !(isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) )): ?>
				<!-- <li>
					<?php _e('Delete the test database tables','flotheme') ?>
					<button type="button" class="start-test-mode" onclick="flo_delete_test_tables();"><?php _e('Delete the test database tables','flotheme') ?></button>
				</li> -->
			<?php endif ?>

			<?php if (isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) ): ?>
				<li class="flo-disable">
					<span class="icon backup"></span>
					<span class="step-title">
						<?php _e('Disable the test drive mode','flotheme'); ?>
					</span>
					<button type="button" class="stop-test-mode" onclick="flo_disable_test_drive_mode();"><?php _e('Disable test mode','flotheme') ?></button>
					<span class="spinner"></span>
				</li>

				<li class="flo-launch">
					<span class="icon publish"></span>
					<span class="step-title">
						<?php _e('Launch the test drive site','flotheme'); ?>
					</span>
					<span class="flo-launch-test-site btn-wraper">
						<button type="button" class="launch" onclick="flo_launch_test_drive_site();"><?php _e('Publish the Test site','flotheme') ?></button>
						<span class="spinner"></span>
					</span>
				</li>
			<?php endif ?>
			
		</ul>
	

	
</div>