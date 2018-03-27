( function() {
	// Register plugin
	tinymce.create( 'tinymce.plugins.plugin_slug', {

		init: function( editor, url )  {

			// Add the Insert Gistpen button
			// floFormsPopup - this is the MCE plugun slug, it should be changed for other plugins
			editor.addButton( 'floFormsPopup', {
				//text: 'Insert Shortcode',
				icon: 'icons dashicons-before dashicons-feedback',
				//image: url+"/images/icon.png",
				tooltip: 'Insert Flo Form Shortcode',
				cmd: 'plugin_command'
			});

			// Called when we click the Insert Gistpen button
			editor.addCommand( 'plugin_command', function() {
				// Calls the pop-up modal
				editor.windowManager.open({
					// Modal settings
					title: 'Insert Flo Form Shortcode',
					width: 290,
					// minus head and foot of dialog box
					height: 170,
					inline: 1,
					id: 'plugin-slug-insert-dialog',
					buttons: [{
						text: 'Insert',
						id: 'plugin-slug-button-insert',
						class: 'insert',
						onclick: function( e ) {
							var cont;
							cont = jQuery("#flo-forms-select option:selected").val();
							var text = '[flo_form id='+"'"+cont+"'"+']';
							tinyMCE.activeEditor.execCommand( "mceInsertContent", false, text )
							editor.windowManager.close();
						}
					},
						{
							text: 'Cancel',
							id: 'plugin-slug-button-cancel',
							onclick: 'close'
						}]
				});

				appendInsertDialog();

			});
		}
	});
	tinymce.PluginManager.add( 'floFormsPopup', tinymce.plugins.plugin_slug );
	function appendInsertDialog () {

		// Get the form template from WordPress
		jQuery.post( ajaxurl, {
			action: 'plugin_slug_insert_dialog'
		}, function( response ) {
			template = response;
			var dialogBody = jQuery( '#plugin-slug-insert-dialog-body' ).append( '<div style="padding: 25px"><span>Select the Form shortcode</span></div><div style="padding: 25px;" id="flo-forms-select">'+response+'</div>');
			dialogBody.children( '.loading' ).remove();
			jQuery( '.spinner' ).hide();
		});
	}
})();
