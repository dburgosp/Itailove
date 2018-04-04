(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );


function FloSaveToDisk(fileURL, fileName) {
    //alert("yes i m working");
    // for non-IE
    if (!window.ActiveXObject) {
        var save = document.createElement('a');
        save.href = fileURL;
        save.target = '_blank';
        save.download = fileName || 'unknown';

        var evt = new MouseEvent('click', {
            'view': window,
            'bubbles': true,
            'cancelable': false
        });
        save.dispatchEvent(evt);

        (window.URL || window.webkitURL).revokeObjectURL(save.href);
    }

    // for IE < 11
    else if ( !! window.ActiveXObject && document.execCommand)     {
        var _window = window.open(fileURL, '_blank');
        _window.document.close();
        _window.document.execCommand('SaveAs', true, fileName || fileURL)
        _window.close();
    }
} 

function flo_backup_db(){

	jQuery('.flo-save-bckup .spinner').css('visibility','visible');
 	jQuery.ajax({
		url: ajaxurl,
		data: '&action=flo_db_backup',
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			if(json.response && json.response == 'success'){
				
				jQuery('#flo_back_up').attr('href',json.backup_url).click();
				FloSaveToDisk(json.backup_url, 'db-backup.sql')
				//console.log(json.backup_url);
			}else if(json.response && json.response != 'success'){
				alert(json.response);
			}

			jQuery('.flo-save-bckup .spinner').css('visibility','hidden');
		},
		error: function (xhr) {
            console.log(xhr);
            if( xhr.responseText){
            	var custom_error_msg = '<br/><b><i>Unfortunatelly we can not create a backup at the moment. Try creating a database backup using PhpMyAdmin or a plugin like Backupbuddy.</i></b><br/>';
            	jQuery('.log-response-messages').html(custom_error_msg + xhr.responseText);
            }

            jQuery('.flo-save-bckup .spinner').css('visibility','hidden');
        }
	});
}


function flo_launch_test_drive_site(){

	if(confirm('PLEASE READ! \n\nPressing OK will publicly launch your new site. This means that it will overwrite your current site. DO NOT publish until you have finalized customizing your new site. Also please remember that if you’ve created any new posts on your old site, you should replicate these exactly in your new site.')){
		jQuery.ajax({
			url: ajaxurl,
			data: '&action=flo_launch_test_drive_site',
			type: 'POST',
			dataType: "json",
			cache: false,
			success: function (json) {

				if(json.response && json.response == 'success'){

					location.reload(); // reload the page after the cookie is deleted

				}

				//jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
			},
			error: function (xhr) {
	            console.log(xhr);
	            //jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
	        }
		});
	}
}

/**
 *
 * disable the test drive mode by removing the cookie
 *
 */
function flo_disable_test_drive_mode(){

	jQuery.ajax({
		url: ajaxurl,
		data: '&action=flo_disable_test_drive_mode',
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			if(json.response && json.response == 'success'){

				location.reload(); // reload the page after the cookie is deleted

			}

			//jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
		},
		error: function (xhr) {
            console.log(xhr);
            //jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
        }
	});

}

function disable_disk_check(){
	jQuery.ajax({
		url: ajaxurl,
		data: '&action=disable_disk_check',
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			if(json.response && json.response == 'success'){
				location.reload();
			}
		},
		error: function (xhr) {
            console.log(xhr);
        }
	});
}

/**
 *
 * function to delete a cookie
 *
 */
var flo_delete_cookie = function(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

function flo_check_if_wp_config_is_writable(){
	jQuery.ajax({
		url: ajaxurl,
		data: '&action=flo_is_config_writable',
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			if( json.response === 'success'){
				var db_tables;
				jQuery('.start-test-mode').prop('disabled', true);
				db_tables = flo_get_db_tables(); // start cloning the tables

			}else{
				jQuery('.log-error-messages').html(json.response); // usually this is the mesage that tells that the config file is not writable
			}
		},
		error: function (xhr) {
			if(xhr.statusText){
				console.log(xhr.statusText);
			}else{
				console.log(xhr);
			}
            
            //jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
        }
	});
}

function flo_delete_current_test_site(){

	if(confirm('By approving this action the current test site will be deleted and all the changes made on the test site will be lost. Proceed with this action only if you really need to delete the test site.')){

		jQuery('.flo-start-over .spinner').css('visibility','visible');
		jQuery('.flo-start-over .log-messages').hide();

		jQuery.ajax({
			url: ajaxurl,
			data: '&action=flo_delete_test_site',
			type: 'POST',
			dataType: "json",
			cache: false,
			success: function (json) {
				jQuery('.flo-start-over .log-messages').html(json.response);
				jQuery('.flo-start-over .log-messages').show();

				jQuery('.flo-start-over .spinner').css('visibility','hidden');


				setTimeout(function(){ 
					location.reload(); // reload the page after 3 sec
				}, 3000);

			},
			error: function (xhr) {
				if(xhr.statusText){
					console.log(xhr.statusText);
				}else{
					console.log(xhr);
				}
				jQuery('.flo-start-over .spinner').css('visibility','hidden');
	        }
		});
	}
	
}

function flo_enable_test_drive(){
	flo_check_if_wp_config_is_writable();
}

function flo_get_db_tables(){
	
	jQuery('.flo-start-test-mode .spinner').css('visibility','visible');

	jQuery.ajax({
		url: ajaxurl,
		data: '&action=flo_get_db_tables',
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			if(json.response && json.response == 'success'){
//console.log(json.table_fields);
				if(json.tables){
					
					//jQuery.each(json.tables, function(i, item) {
					    //console.log(json.table_fields[item]);
					    flo_insert_into_tables( json.tables, json.original_tables, json.table_fields, json.nr_iterations, 0);
					//});
				}
				
			}else if( json.response && 'tables are created already' == json.response){
				// just change the cookie
				flo_enable_test_drive_mode();
			}
		},
		error: function (xhr) {
			if(xhr.statusText){
				console.log(xhr.statusText);
			}else{
				console.log(xhr);
			}
            
            //jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
        }
	});
}

function flo_enable_test_drive_mode(){
	jQuery.ajax({
		url: ajaxurl,
		data: '&action=enable_test_drive_mode_cookie',
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			location.reload();
		},
		error: function (xhr) {
			if(xhr.statusText){
				console.log(xhr.statusText);
			}else{
				console.log(xhr);
			}

        }
	});
}

function flo_insert_into_tables( tables, original_tables, table_row_structure, total_requests, iteration_number, table_index){

	var request_nr;
	jQuery('.flo-start-test-mode .spinner').css('visibility','visible');
	jQuery('.flo-start-test-mode .spinner').

	iteration_number = typeof iteration_number !== 'undefined' ? iteration_number : 0; // default value for iteration_number
	table_index = typeof table_index !== 'undefined' ? table_index : 0; // default value for table_index

	if(tables.length > table_index){ // make sure we are working 

		table_name = tables[table_index];
		original_table_name = original_tables[table_index];
		table_row = table_row_structure[table_name];

		//console.log(table_name,table_row);

		request_nr = parseInt(jQuery('.log-messages .request-nr').text());

		jQuery.ajax({
			url: ajaxurl,
			data: '&action=flo_insert_into_tables&table_name='+table_name+'&original_table_name='+original_table_name+'&table_row='+table_row+'&iteration_number='+iteration_number,
			type: 'POST',
			dataType: "json",
			cache: false,
			success: function (json) {

				jQuery('.log-messages').show();

				jQuery('.log-messages .request-nr').text(request_nr+1);
				jQuery('.log-messages .total-request').text(total_requests);


				jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');

				if(json.message && json.message == 'success'){
					if(json.table_done && json.table_done == 1){
						table_index = table_index + 1; // move to the next table
						iteration_number = 0; // reset the iteration number

						// start working with the next table
						flo_insert_into_tables( tables, original_tables, table_row_structure, total_requests, iteration_number, table_index );
					}else{
						iteration_number = iteration_number + 1; //reset the iteration number

						// working with the same table, but the 
						flo_insert_into_tables( tables, original_tables, table_row_structure, total_requests, iteration_number, table_index );
					}

				}else{
					console.log(json.message);
					jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');

				}
				
			},
			error: function (xhr) {
				if(xhr.statusText){
					console.log(xhr.statusText);
				}else{
					console.log(xhr);
				}
				jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');

	        }
		});

	}else{
		console.log('all tables were cloned');
		// we are done with inserting into tables
		// activate the demo site
		// - create option, create coockie, change the prefix in the wp-config


		jQuery.ajax({
			url: ajaxurl,
			data: '&action=flo_prepare_for_launch',
			type: 'POST',
			dataType: "json",
			cache: false,
			success: function (json) {
				console.log(json);
				console.log('The site was launched succesfully');
				location.reload(); // reload the page after the cookie is deleted
			},
			error: function (xhr) {
				if(xhr.statusText){
					console.log(xhr.statusText);
				}else{
					console.log(xhr);
				}
	            
	            jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');
	        }
		});
		jQuery('.flo-start-test-mode .spinner').css('visibility','hidden');

	}

}