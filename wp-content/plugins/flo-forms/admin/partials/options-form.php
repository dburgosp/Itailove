<div class="flo-forms-settings">
	<div class="options-header">
		<div class="title"><?php _e('Flo Forms Settings','flo-forms'); ?></div>
	</div>
	<form method="post" action="options.php">
        <?php wp_nonce_field('update-options') ?>

        <div class="options-row">
        	<h4><?php _e('Enable email reminder if there are unread Entries?','flo-forms'); ?></h4>
    		<label>
        		<input type="radio" name="flo_forms_options[enable_email_reminder]" <?php checked( $forms_options['enable_email_reminder'], '1' ); ?> value="1">
        		<?php _e('Yes','flo-forms'); ?>
    		</label>
    		<label>
        		<input type="radio" name="flo_forms_options[enable_email_reminder]" <?php checked( $forms_options['enable_email_reminder'], '0' ); ?> value="0">
        		<?php _e('No','flo-forms'); ?>
    		</label>
    	</div>
		<div class="options-row">
			<h4><?php _e('Email as plain text instead html','flo-forms'); ?></h4>
			<label>
				<input type="radio" name="flo_forms_options[text_email]" <?php checked( $forms_options['text_email'], '1' ); ?> value="1">
				<?php _e('Yes','flo-forms'); ?>
			</label>
			<label>
				<input type="radio" name="flo_forms_options[text_email]" <?php checked( $forms_options['text_email'], '0' ); ?> value="0">
				<?php _e('No','flo-forms'); ?>
			</label>
		</div>
        <div class="options-row">
            <h4><?php _e('Enable "Reply-to" email header?','flo-forms'); ?></h4>

            <label>
                <input type="radio" name="flo_forms_options[reply_to_header]" <?php checked( $forms_options['reply_to_header'], '1' ); ?> value="1">
                <?php _e('Yes','flo-forms'); ?>
            </label>
            
            <label>
                <input type="radio" name="flo_forms_options[reply_to_header]" <?php checked( $forms_options['reply_to_header'], '0' ); ?> value="0">
                <?php _e('No','flo-forms'); ?>
            </label>
            <br/>
            <span class="tab-title hint">
                <?php
                    _e('Note: This header enables the replay-to feature. But on some hosting services this feature is not available, so if you have issues getting emails, try to disabling this.','flo-forms');
                ?>

            </span>
            <br/>
        </div>
    	<div class="options-row">
    		<label>
				<h4><span><?php _e('How old should the Entries be to receive the reminder via email?','flo-forms'); ?> </span></h4>
				<select class="link-to" data-setting="link" name="flo_forms_options[entries_days_old_reminder]">

				<?php for ($i=1;$i<11;$i++): ?>
					<option value="<?php echo $i ?>" <?php selected( $forms_options['entries_days_old_reminder'], $i ); ?> ><?php echo sprintf(_n('%d Day','%d Days',$i,'flo-forms'), $i); ?></option>
				<?php endfor ?>
				</select>
			</label>
    	</div>
    	<div class="options-row">
    		<label>
    			<h4><span><?php _e('Enter the email address the reminder will be sent to:','flo-forms'); ?> </span></h4>
    		</label>
    		<input type="text" name="flo_forms_options[send_to_email]" value="<?php echo $forms_options['send_to_email'] ?>">
    	</div>
        <div class="options-row">
            <h4><?php _e('Enable google captcha','flo-forms'); ?></h4>

            <label>
                <input onchange="displayCaptchaOptions()" type="radio" class="captcha-true" name="flo_forms_options[enable-captcha]" <?php $checked = isset($forms_options['enable-captcha'])? checked( $forms_options['enable-captcha'], '1' ):''; echo $checked; ?> value="1">
                <?php _e('Yes','flo-forms'); ?>
            </label>

            <label>
                <input onchange="displayCaptchaOptions()" type="radio" name="flo_forms_options[enable-captcha]" <?php $checked = isset($forms_options['enable-captcha'])? checked( $forms_options['enable-captcha'], '0' ):'checked'; echo $checked; ?> value="0">
                <?php _e('No','flo-forms'); ?>
            </label>
        </div>
        <div class="options-row captcha-row" style="padding-top: 20px;">
            <span><a target="_blank" href="https://www.google.com/recaptcha/admin">Access to generate your personal pair of keys</a></span>

            <label>
                <h4><span><?php _e('Google Recaptcha site key:','flo-forms'); ?> </span></h4>
            </label>
            <input type="text" name="flo_forms_options[g_site_key]" value="<?php $site_key =  isset($forms_options['g_site_key'])? $forms_options['g_site_key']:''; echo $site_key ?>">

            <label>
                <h4><span><?php _e('Google Recaptcha secret key:','flo-forms'); ?> </span></h4>
            </label>
            <input type="text" name="flo_forms_options[g_secret_key]" value="<?php $secret_key = isset($forms_options['g_secret_key'])? $forms_options['g_secret_key']: ''; echo $secret_key; ?>">
        </div>
    	<p class="for-submit"><input type="submit" class="button button-primary button-large " name="Submit" value="<?php _e('Save Options','flo-forms'); ?>" /></p>
        <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="flo_forms_options" />
	</form>
</div>
