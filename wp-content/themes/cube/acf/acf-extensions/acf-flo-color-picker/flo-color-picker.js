(function($){

	acf.fields.flo_color_picker = acf.field.extend({

		type: 'flo_color_picker',
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
				clear: change_hidden,
        palettes: this.$field.find(".acf-flo-color-picker__custom-color-picker").attr("data-palette").split(",")

			}


 			// filter
 			var args = acf.apply_filters('color_picker_args', args, this.$field);


 			// iris
			$input.wpColorPicker(args);

      /* START: SHOW HIDE COLOR PICKER */
        var $question_checkbox = this.$field.find(".acf-flo-color-picker__custom-color-checkbox");
        var $question_text = this.$field.find(".acf-flo-color-picker__custom-color-text");
        var $picker_wrap = this.$field.find(".acf-flo-color-picker__custom-color-picker");
        var $current_color = this.$field.find(".acf-flo-color-picker__current-color");
        var current_color = $current_color.attr("data-color");


        this.$field.find(".wp-picker-container").on("click", function(){
          var picker = $(this);
          $(".acf-flo-color-picker__custom-color-picker").css({
            "z-index": 100
          });
          setTimeout(function () {
            picker.parent().css({
              "z-index": 101
            });
          }, 10);
        });

        $question_checkbox.on("change", function(){
          if (this.checked) {
            $picker_wrap.fadeIn("fast");
            $current_color.hide("fast");
          } else {
            $picker_wrap.fadeOut("fast");
            $current_color.show("fast");
          }
        });


      /* END: SHOW HIDE COLOR PICKER */

		}

	});

})(jQuery);
