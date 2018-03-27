(function($){


	function initialize_field( $el ) {


		var flo_quick_fonts_app = angular.module('floQuickFonts', ['jdFontselect']);

    angular.element(function() {
      angular.bootstrap("[ng-controller=floQuickFontsCtrl]", ['floQuickFonts']);
    });

		flo_quick_fonts_app.controller('floQuickFontsCtrl', function($scope,$rootScope, $filter) {

//console.log(quick_font);
			$scope.quick_font_select = quick_font_select;
			$scope.quick_font = quick_font;
			$scope.old_value = old_value;
			if(typeof all_quick_fonts_list != 'undefined'){
				$scope.fonts_list_quick = all_quick_fonts_list;
			}


			// init the Google fonts

				//$rootScope.quick_selected_fonts = quick_selected_fonts;

			jQuery.each(quick_font, function( index, font_val ) {
				if(font_val !== ''){
					$rootScope['google_font_'+index] = font_val.font_family;
				}else{
					$rootScope['google_font_'+index] = '"Open Sans", sans-serif, "google"';
				}


			});

			$scope.floQuickFontChange = function($key){

				// get the selected value
				var selected_font = jQuery('.quick_font_select_'+$key+' option:selected').val();


				if(selected_font.indexOf('google') > -1){
					// if the selected font is a google font we  will change the scope to
					// render the proper font
					$scope['google_font_'+$key] = selected_font;

				}else{
					// if it is not a google font, we change just the font-family style
					jQuery('.group_google_font.'+$key+' section').css('font-family',selected_font);
					jQuery('.group_google_font.'+$key+' .quick-font-unit').val(selected_font);


					// update the scope to make sure the selected value will be saved
					quick_font[$key]['font_family'] = all_quick_fonts_list[selected_font]['font_family'];
					quick_font[$key]['font_type'] = all_quick_fonts_list[selected_font]['font_type'];
					quick_font[$key]['font_url'] = all_quick_fonts_list[selected_font]['font_info'];


				}

			}

			$scope.floQuickUploadFont = function(key) {

				var typesPermitted = ['ttf', 'woff', 'woff2', 'svg', 'eot', 'otf'],
			        multiple_file_frame,
			        added_font_name;

		        multiple_file_frame = wp.media({
					frame:    'select',
		            multiple: true,
		        });

		        $scope.fonts_list = [];
		        $scope.custom_fonts = [];

		        multiple_file_frame.on( "select", function(){

		            var selection = multiple_file_frame.state().get( "selection").toJSON();

		            selection.forEach(function(el, index, array){

		                var name = el.filename;

		                var f = name.split(".");

		                var format = _.last( f );
		                format = format.toLowerCase();

		                if ( _.indexOf(typesPermitted, format ) == -1 ) {
		                	return true;
		                };

		                var name = _.last( el.url.split('/') ).split('.')[0];

		                var font = {
		                	'name': name,
		                	'format': format,
		                	'url': el.url
		                };



		                // add the new font to the fonts_list used for the dropdown
		                var new_font_list = {},
		                	new_quick_font_list = {};
	//console.log($scope.fonts_list);
		                if( !( name in $scope.fonts_list && $scope.fonts_list.length == 0 ) ){
		                	added_font_name = name;
		                	new_font_list = {
			                	'font_type': 'custom_font',
			                	'font_family': name,
			                	'font_url': {
			                		'0': {
			                			'url': el.url,
			                			'name': name,
			                			'format': format
			                		}
			                	}
			                };
			                new_quick_font_list = {
			                	'font_type': 'custom_font',
			                	'font_family': name,
			                	'font_info': {
			                		'0': {
			                			'url': el.url,
			                			'name': name,
			                			'format': format
			                		}
			                	}
			                };

			                $scope.fonts_list[name] = new_font_list;

			                // update the dropdown
			                $scope.fonts_list_quick[name] = new_font_list;

			                $rootScope['google_font_'+index] = new_font_list;

		                }else if(name in $scope.fonts_list){
		                	// append a new format of a existing font
		                	$scope.fonts_list[name].font_url[ Object.keys($scope.fonts_list[name].font_url).length ] = font;

		                }


		                $scope.custom_fonts.push( font );

		                //$scope.fonts_list = _.omit($scope.fonts_list, font.name);


		            });

		            //$rootScope['google_font_'+key] = added_font_name;

		            $(".quick-font-unit." + key ).val(added_font_name);

					$scope.quick_font[key] = {
						'font_type': 'custom_font',
						'font_family': $scope.fonts_list[added_font_name]['font_family'],
						'font_url': $scope.fonts_list[added_font_name]['font_url']
					};

					var font_face = floGetCustomFontface(added_font_name, $scope.fonts_list[added_font_name]['font_url'] );
					jQuery('.font-face-style').append(font_face);

					//$scope['google_font_'+key] = added_font_name;
					jQuery('.group_google_font.'+key+' section').css('font-family',added_font_name);
					jQuery('.group_google_font.'+key+' .quick-font-unit').val(added_font_name);

		            $scope.custom_fonts = _.uniq( $scope.custom_fonts, function( font){
		            	return font.url;
		            });


		            $scope.$apply();

		        });

		        multiple_file_frame.open();

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
			acf.get_fields({ type : 'flo_quick_typography'}, $el).each(function(){

			  initialize_field( $(this) );

			});

		});

	}



})(jQuery);
