(function($){


	function initialize_field( $el ) {

		//$el.doStuff();
		$('.font-style-modal__field').on('click','.add-google-font',function(){
			floCloseModal();
			$('#acf-group_flo_typography .acf-flo-typography__tab-selectors a[href="#acf-flo-typography__tab--google-fonts"]').click();
			//console.log($('#acf-group_flo_typography .acf-flo-typography__tab-selectors a[href="#acf-flo-typography__tab--google-fonts"]').length);
		});

		$('.font-style-modal__field').on('click','.add-custom-font',function(){
			floCloseModal();
			$('#acf-group_flo_typography .acf-flo-typography__tab-selectors a[href="#acf-flo-typography__tab--custom-fonts"]').click();
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

			// search $el for fields of type 'typography'
			acf.get_fields({ type : 'typography'}, $el).each(function(){

				initialize_field( $(this) );

			});


			jQuery(document).keyup(function(e){
				if(e.keyCode === 27){ // on esc key press
			        // Close my modal window
			        if(jQuery('.font-style-modal:visible')){
			        	jQuery('.font-style-modal').fadeOut();
			        }
			    }
			});

			jQuery('.font-style-modal__tabs').tabs({show: 'fade', hide: 'fade'});

				if ( typeof jQuery('.acf-flo-typography__tabs')[0] != 'undefined' && !jQuery('.acf-flo-typography__tabs')[0].hasAttribute('data-active-tab-index'))
					jQuery('.acf-flo-typography__tabs').attr("data-active-tab-index", 0)
				;

			jQuery('.acf-flo-typography__tab-selector').on("click", function(){
					jQuery('.acf-flo-typography__tabs').attr("data-active-tab-index", jQuery(this).index())
			});

			var active_tab_index = function(){
				return jQuery('.acf-flo-typography__tabs').attr("data-active-tab-index");
			}

			jQuery('.acf-flo-typography__tabs').tabs(
		        {
		          active: active_tab_index()
		        }
		    );

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

			$(postbox).find('.field[data-field_type="typography"]').each(function(){

				initialize_field( $(this) );

			});

		});

	}



	/* global angular */ /* jshint indent: false */
	// angular.module('floFonts', ['jdFontselect']).constant('jdFontselectConfig', {
	//   	//googleApiKey: 'AIzaSyCqOjyQoMvmZf1mQecGc0qdwaRQ0V_mOsw'
	// })
	// .run(function($rootScope) {

	// 	if(selected_fonts ){
	// 		$rootScope.selected_fonts = selected_fonts;


	// 		$.each($rootScope.selected_fonts, function( index, font_val ) {
	// 			$rootScope[index] = font_val;
	// 		});

	// 	}
	//   // $rootScope.content = '"Open Sans", sans-serif, "google"';

	// });
	//angular.bootstrap(document, ['jdFontselectDemoApp']);


	var flo_app = angular.module('floFonts', ['jdFontselect', 'ui.slider']);
	flo_app.directive('onFinishRender', function ($timeout) {
	    return {
	        restrict: 'A',
	        link: function (scope, element, attr) {
	            if (scope.$last === true) {
	                scope.$evalAsync(attr.onFinishRender);
	            }
	        }
	    }
	});

	flo_app.controller('floFontsCtrl', function($scope,$rootScope, $filter) {

		// init the Google fonts
	    if(selected_fonts ){
			$rootScope.selected_fonts = selected_fonts;

			$.each($rootScope.selected_fonts, function( index, font_val ) {
				$rootScope[index] = font_val;
			});

		}
		$scope.selected_fonts_length = Object.keys(selected_fonts).length;

		// BOF Custom fonts Upload
		$scope.custom_fonts = fpt_upload_font;

		$scope.$watch('custom_fonts', function() {

			$scope.filteredCustom_fonts = $filter('json')($scope.custom_fonts);

			// $scope.filteredCustom_fonts = $scope.custom_fonts;
			$scope.grouped_fonts = _.groupBy($scope.custom_fonts, function( fontEl ){ return fontEl.name });
    	});

		/**
		 *
		 * Check if 'edit_font_style' parameter is passed via URL
		 * then we get that value and trigger the click on the
		 * edit link that has the data-edit equal with the URL 'edit_font_style' value
		 *
		 */
		$scope.flo_maybe_open_modal = function(){

			var maybe_open_modal = floGetParameterByName('edit_font_style');
			if(maybe_open_modal != null && maybe_open_modal.length && jQuery('[data-edit="'+maybe_open_modal+'"]').length){
				setTimeout(function(){

					// scroll down to the font style block
					jQuery('html,body').animate({
			            scrollTop: jQuery('[data-edit="'+maybe_open_modal+'"]').offset().top
			        }, 400);

			        // simulate click on the edit button to open the modal
					jQuery('[data-edit="'+maybe_open_modal+'"]').click();

					// click on the  tab which contains text Font Styles
					jQuery('.acf-tab-wrap.-left .acf-tab-group li a').each(function(){
						//console.log($(this).text());
						if($(this).text().indexOf('Font Styles') > -1){
							$(this).click();
						}
					});
				}, 30);

			}


		}

		$scope.floUploadFont = function() {

			var typesPermitted = ['ttf', 'woff', 'woff2', 'svg', 'eot', 'otf'],
		        multiple_file_frame;

	        multiple_file_frame = wp.media({
				frame:    'select',
	            multiple: true,
	        });

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
	                var new_font_list = {};
//console.log($scope.fonts_list);
	                if( !( name in $scope.fonts_list ) ){
	                	new_font_list = {
		                	'font_type': 'custom_font',
		                	'font_family': name,
		                	'font_info': {
		                		'0': {
		                			'url': el.url,
		                			'name': name,
		                			'format': format
		                		}
		                	}
		                }

		                $scope.fonts_list[name] = new_font_list;
	                }else{
	                	// append a new format of a existing font
	                	$scope.fonts_list[name].font_info[ $scope.fonts_list[name].font_info.length ] = font;
	                }


	                $scope.custom_fonts.push( font );

	                //$scope.fonts_list = _.omit($scope.fonts_list, font.name);

	            });

	            $scope.custom_fonts = _.uniq( $scope.custom_fonts, function( font){
	            	return font.url;
	            });

	            $scope.$apply();

	        });

	        multiple_file_frame.open();

		}

		$scope.floDeleteFont = function( font ) {

			$scope.custom_fonts = _($scope.custom_fonts).without( font );
			$scope.fonts_list = _.omit($scope.fonts_list, font.name); // remove the font from the dropdown as well

		}

		/**
		 * Font upload
		 * string name - font name
		 * array fonts - list of fonts
		 */
		$scope.floGetFontFace = function(name, fonts ) {

			return floGetCustomFontface(name, fonts );

		}

		/**
		 * Font Styles
		 * array font - font style properties
		 * string state - font state: default, link, link hover, link visited
		 */
		$scope.setStyleTypography = function( font, state) {
			return floSetStyleTypography( font, state );

		};

		$scope.setContrastBg = function( font, state) {
			if(typeof font[state] !== 'undefined' && typeof font[state]['font_color'] !== 'undefined' ){
				luminance = floGetLuminance(font[state].font_color);

				if(luminance > 180){
					return 'dark-bg';
				}

			}

			return
		};

		// BOF Font Styles
		$scope.popup = {}; // popup data
		$scope.font_styles = flo_font_styles;
		$scope.default_font_styles = $scope.font_styles.theme_defaults;
		$scope.custom_font_styles = $scope.font_styles.user_specified;
		$scope.original_default_font_styles = $scope.font_styles.original_theme_defaults;
//console.log($scope.default_font_styles);
		// When the user clicks the button, open the modal
 		$scope.floShowModal = function(font, name){
 			//$scope.popup = $scope.font_styles.theme_defaults.name;
 			if(typeof font.font_size_mobile === 'undefined'){
 				font.font_size_mobile = font.font_size;
 			}
 			//console.log(font);
 			$scope.popup = font;
 			$scope.popup.font_style_key = name;
 			//console.log($scope.popup);
 			jQuery('.popup-tabs').tabs({active: 0}); // set the first tab active when a new modal is openned


 			// make sure the selected color is the same as in scope
 			setTimeout(function(){ // delay a bit the action to make sure the data is initialized
 				jQuery( '.settings-color-field' ).each(function( index ) {
				  	var this_color,
				  		this_use_case = $(this).data('use_case');
				  	if(typeof font[this_use_case] !== 'undefined' && typeof font[this_use_case]['font_color'] !== 'undefined'){
				  		this_color = font[this_use_case]['font_color'];
				  	}else{
				  		this_color = '#0a0a0a';
				  	}

				  	// set the proper color for each
				  	jQuery(this).wpColorPicker('color',this_color);
				  	$scope.$apply();
				});
 			}, 300);

 			jQuery('.font-style-modal').fadeIn(); // show the modal

		}

		// init color picker
    	jQuery('.settings-color-field').wpColorPicker({

    		change: function(event, ui){
				// the use case tells us which mode we are editing: default, link, link hover, link visited
				var use_case = $(this).data('use_case');

				// manipulate the color from the scope
				$scope.$apply(function(){
					$scope.popup[use_case]['font_color'] = ui.color.toString();
				});

			}
		});


		$scope.fonts_list = all_fonts_list;

		$scope.fonts_selected_option = '"Arial, "Helvetica Neue", Helvetica, sans-serif"';
		//console.log($scope.fonts_list);

		// function that triggers on font select change
		$scope.floFontChange = function(){

			//console.log($scope.popup,$scope.fonts_list);

			// because when using ng-model="popup.font_family" we are able to sync only the font_family,
			// we need a way to update the font_url and the font_type as well
			// update the popup data with the data from the fonts_list
			$scope.popup.font_url = $scope.fonts_list[$scope.popup.font_family]['font_info'];
			$scope.popup.font_type = $scope.fonts_list[$scope.popup.font_family]['font_type'];
		}

		$scope.deleteGoogleFont = function(key){

			var delete_font_val = jQuery(' .'+key+ ' .content input').val();

			if(delete_font_val){ // if not false. It can be false in case no font was selected and the settings were saved
				//console.log($scope.fonts_list);
				$scope.fonts_list = _.omit($scope.fonts_list, delete_font_val); // remove the font from the dropdown as well
			}

			jQuery('.'+key).remove();

		}

		$scope.addFontStyle = function(){

			var option_key = Math.floor((Math.random() * 10000) + 1),  //random number between 1 and 10000
				new_font = {};

				new_font = {
					'font_style_name': 'Your custom font style name',
					'font_family': 'Arial, "Helvetica Neue", Helvetica, sans-serif',
					'font_type': 'system_font',
					'font_url':{
						'url':'',
						'name': 'Arial',
						'format':''
					},
					'font_size': 14,
					'font_size_mobile': 14,
					'word_spacing': 0,
					'letter_spacing': 0,
					'default': {
						'font_color':'#0a0a0a',
						'font_accent': {
								'bold': 0,
								'italic': 0,
								'underline': 0,
							},
						'font_case': 'none'
					},
					'hover_state': {
						'font_color':'#0a0a0a',
					},
					'active': {
				 		'font_color':'#0a0a0a',
				 	}
				};


			$scope.font_styles.user_specified['custom_font_style_'+option_key] = new_font;

		}

		$scope.deleteFontStyle = function(key){
			delete $scope.font_styles.user_specified[key];
		}

		/**
		 * reset the current font to default
		 */
		$scope.resetToDefaultFont = function(name){
			if(confirm('Are you sure you want to reset this font style to the default value ?')){
				$scope.popup = $scope.original_default_font_styles[name];
				$scope.default_font_styles[name] = $scope.original_default_font_styles[name];

				// make sure that the default value has the reset button
				$scope.default_font_styles[name].allow_reset = 1;
				$scope.popup.allow_reset = 1;
			}
		}

	});


})(jQuery);




function floGetCustomFontface(name, fonts ){
 //console.log(name, fonts, ' hopa');
	var fontface = '';

	fontface += '@font-face {';
	fontface += 'font-family: "'+ name + '"; ';

	var lines = [];

	var newFont = _.map(fonts, function(font) {

		var line = '';

		switch(font.format) {
		    case 'otf':
		        line = "url('" + font.url + "') format('opentype')";
		        break;
		    case 'svg':
		        line = "url('" + font.url + "') format('svg')";
		        break;
	        case 'ttf':
		        line = "url('" + font.url + "') format('truetype')";
		        break;
	        case 'woff':
		        line = "url('" + font.url + "') format('woff')";
		        break;
	        case 'woff2':
		        line = "url('" + font.url + "') format('woff2')";
		        break;
		    default:
		        //
		}

		lines.push(line);

		return font;

	});

	// Note! eot fonts are problematic. Review this or not use at all
	var eot = _.findWhere(fonts, {format: 'eot'});

	if ( typeof eot != 'undefined' ) {
		fontface += "src: url('" + eot.url + "');";
	}

	fontface += 'src:';

	fontface +=lines.join();
	fontface +=';';

	fontface += '}';

	return fontface;

}



/**
 * Font Styles
 * array font - font style properties
 * string state - font state: default, link, link hover, link visited
 */
function floSetStyleTypography( font, state ){
	var typography = '';

	if(typeof font['font_family'] !== 'undefined'){
		typography += 'font-family: ' + font['font_family']+'; ';
	}
	if(typeof font['font_size'] !== 'undefined'){
		// px to rem formula:
		// [pixel value] / 16 * [viewport width coefficient] vw
		typography += 'font-size: ' + font['font_size']*1.21/16+'vw ;';
	}
	if(typeof font[state] !== 'undefined' && typeof font[state]['font_color'] !== 'undefined' ){
		typography += 'color: ' + font[state]['font_color']+'; ';
	}
	if( typeof font[state] !== 'undefined' &&
		typeof font[state]['font_accent'] !== 'undefined'
		&& typeof font[state]['font_accent']['bold'] !== 'undefined'
		&& font[state]['font_accent']['bold'] ){

			typography += 'font-weight: bold; ';
	}
	if( typeof font[state] !== 'undefined' &&
		typeof font[state]['font_accent'] !== 'undefined' &&
		typeof font[state]['font_accent']['underline'] !== 'undefined' &&
		font[state]['font_accent']['underline']){

			typography += 'text-decoration: underline; ';
	}
	if( typeof font[state] !== 'undefined' &&
		typeof font[state]['font_accent'] !== 'undefined' &&
		typeof font[state]['font_accent']['italic'] !== 'undefined' &&
		font[state]['font_accent']['italic']){

			typography += 'font-style: italic; ';
	}
	if( typeof font[state] !== 'undefined' && typeof font[state]['font_case'] !== 'undefined' ){
		typography += 'text-transform: ' + font[state]['font_case']+'; ';
	}
	if(typeof font['word_spacing'] !== 'undefined'){
		typography += 'word-spacing: ' + font['word_spacing']+'em; ';
	}
	if(typeof font['letter_spacing'] !== 'undefined' ){
		typography += 'letter-spacing: ' + font['letter_spacing']+'em;';
	}
	if(typeof font['line_height'] !== 'undefined' ){
		typography += 'line-height: ' + font['line_height']+'em;';
	}

	return typography;
}


// When the user clicks on <span> (x), close the modal
function floCloseModal(){
    jQuery('.font-style-modal').fadeOut();
}

/* START: GOOGLE TAB */
  jQuery(function($){
    $(document).on("click", ".font-style-block__top-bar-item--open-gfontselect",function(){
      $(this).parent().parent().find(".jdfs-toggle").first().click();
    });
  });
/* END: GOOGLE TAB */


function floGetRGB(b){
	var a;
    if(b&&b.constructor==Array&&b.length==3)return b;
    if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))return[parseInt(a[1]),parseInt(a[2]),parseInt(a[3])];
    if(a=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(b))return[parseFloat(a[1])*2.55,parseFloat(a[2])*2.55,parseFloat(a[3])*2.55];
    if(a=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(b))return[parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],
16)];
    if(a=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(b))return[parseInt(a[1]+a[1],16),parseInt(a[2]+a[2],16),parseInt(a[3]+a[3],16)];
    return (typeof (colors) != "undefined")?colors[jQuery.trim(b).toLowerCase()]:null
}

function floGetLuminance(color) {
    var rgb = floGetRGB(color);
    if (!rgb) return null;
        return 0.2126 * rgb[0] + 0.7152 * rgb[1] + 0.0722 * rgb[2];
}


/**
 *
 * Get the url parameter value by name
 *
 */
function floGetParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
