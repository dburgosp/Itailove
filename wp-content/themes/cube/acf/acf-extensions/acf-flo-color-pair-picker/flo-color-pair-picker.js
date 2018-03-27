(function($){

	acf.fields.flo_color_pair_picker = acf.field.extend({

    type: 'flo_color_pair_picker',
		$input: null,
		$hidden: null,

		actions: {
			'ready':	'initialize',
			'append':	'initialize'
		},

		focus: function(){

			this.$input = this.$field.find('input[type="text"]');
			this.$hidden = this.$field.find('input[type="hidden"]');

		},

		initialize: function(){
      var b = "flo-color-pair-picker";
      var dotb = "." + b;
      var $b = $(dotb);

			// reference
			var $input = this.$input,
				$hidden = this.$hidden;


			// trigger change function
			var change_hidden = function(){

				// timeout is required to ensure the $input val is correct
				setTimeout(function(){

					acf.val( $hidden, $input.val() );

				}, 1);

			}


			// args
			var args = {

				defaultColor: false,
				palettes: true,
				hide: true,
				change: change_hidden,
				clear: change_hidden

			}


 			// filter
 			var args = acf.apply_filters('color_picker_args', args, this.$field);


 			// iris
			this.$input.wpColorPicker(args);

      /* START: SWATCHES */
        var swatch_selected_class = b + "__swatch--selected";
        var swatches = $b.find(dotb + "__swatch");

        swatches.click(function(){
          var current_swatch = $(this);
          swatches.removeClass(swatch_selected_class);
          setTimeout(function () {
            current_swatch.addClass(swatch_selected_class);
          }, 10);
        });

        var custom_swatch = $b.find(dotb + "__swatch--custom");
        var custom_colors = $b.find(dotb + "__custom-colors");
        var custom_colors_active_class = b + "__custom-colors--active";
        swatches.click(function(){
          var swatch = $(this);
          setTimeout(function () {
            if (
              swatch.hasClass(b + "__swatch--custom")
              && swatch.hasClass(swatch_selected_class)
            ) {
              custom_colors.slideDown("slow");
              custom_colors.addClass(custom_colors_active_class);
            } else {
              custom_colors.slideUp("slow");
              custom_colors.removeClass(custom_colors_active_class);
            }
          }, 20);
        });
      /* END: SWATCHES */
		}

	});

})(jQuery);
