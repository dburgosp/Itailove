(function($){


	function initialize_field( $el ) {

		//$el.doStuff();
		var flo_backup_stylekit_app = angular.module('BackupStylekit',['jdFontselect']);

		angular.element(function() {
	      angular.bootstrap("[ng-controller=floBackupStylekitCtrl]", ['BackupStylekit']);
	    });


		flo_backup_stylekit_app.controller('floBackupStylekitCtrl', function($scope,$rootScope, $filter, $http) {

			$scope.flo_stylekits_backup = flo_stylekits_backup;
			//$scope.flo_stylekits_backup = $scope.flo_stylekits_backup.reverse();

			$scope.floBackupStylekit = function(color_options_prefix) {

				var backup_name = jQuery('.flo-backup__name-value').val();

				if(backup_name.length){
					jQuery('.backup-spinner').css('visibility','visible');

					var data = $.param({color_options_prefix:color_options_prefix,
										backup_name: backup_name,
					                    action:'flo_backup_stylekit' });

					var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}

					$http.post(ajaxurl,data,config)
					.success(function(response) {
            jQuery('.flo-backup-showname').fadeIn("slow");
            jQuery('.flo-backup__name-block').hide();
						console.log(response);
						if(response.status == 'success'){
							jQuery('.flo-backup__name-value').val('');

							$scope.response_message = response.message;
							// refresh the data ...
							$scope.flo_stylekits_backup = response.backup_data;
							console.log(response.backup_data);
						}else{
							$scope.response_message = response.message;
						}
					    // hide the spinner
					    jQuery('.backup-spinner').css('visibility','hidden');
					});
				}else{
					alert('Type please the name of the backup');
				}
			}

			$scope.floRestoreStylekit = function(stylekit_index){
				if(confirm('Are you sure you want to restore this backup?')){
					var data = $.param({stylekit_index:stylekit_index,
										action:'flo_restore_stylekit_backup' });

					var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}

					$http.post(ajaxurl,data,config)
					.success(function(response) {
						alert(response.message);
						location.reload();
					});
				}
			}
	    });
	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/

		acf.add_action('ready append', function( $el ){

			// search $el for fields of type 'FIELD_NAME'
			acf.get_fields({ type : 'flo_backup_stylekit'}, $el).each(function(){

				initialize_field( $(this) );

			});


		});


	} else {


		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM.
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="flo_backup_stylekit"]').each(function(){

				initialize_field( $(this) );

			});

		});


	}



})(jQuery);



function floBackupShowName(){
	jQuery('.flo-backup-showname').hide();
	jQuery('.flo-backup__name-block').fadeIn("slow");
	jQuery('.flo-backup__name-value').focus();
}

function floBackupStylekit(){
	var backup_name = jQuery('.flo-backup__name-value').val();
	if(backup_name.length){
		jQuery('.backup-spinner').css('visibility','visible');
	}else{
		alert('Type please the name of the backup');
	}
}
