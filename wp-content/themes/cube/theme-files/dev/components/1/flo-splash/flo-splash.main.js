$(function(){

  var splash__$ = $(".flo-splash");
  var splash__animation_map = {
    "fade" : [
      "fade-in",
      "fade-out"
    ],

    "hinge-top": [
      "hinge-in-from-top",
      "hinge-out-from-top"
    ],
    "hinge-vertical": [
      "hinge-in-from-top",
      "hinge-out-from-bottom"
    ],
    "hinge-left": [
      "hinge-in-from-left",
      "hinge-out-from-left"
    ],
    "hinge-horizontal": [
      "hinge-in-from-left",
      "hinge-out-from-right"
    ],

    "slide-left": [
      "slide-in-left",
      "slide-out-left"
    ],
    "slide-horizontal": [
      "slide-in-left",
      "slide-out-right"
    ],
    "slide-up": [
      "slide-in-down",
      "slide-out-up"
    ],
    "slide-vertical": [
      "slide-in-down",
      "slide-out-down"
    ],

    "spin": [
      "spin-in",
      "spin-out-ccw"
    ]


  }

  var splash__animation_name = splash__$.attr("data-animation");

  // var splash__animation_name = "fade";

  // var splash__animation_name = "hinge-top";
  // var splash__animation_name = "hinge-vertical";
  // var splash__animation_name = "hinge-left";
  // var splash__animation_name = "hinge-horizontal";

  // var splash__animation_name = "slide-left";
  // var splash__animation_name = "slide-horizontal";
  // var splash__animation_name = "slide-up";
  // var splash__animation_name = "slide-vertical";

  // var splash__animation_name = "spin";

  var splash__show = function() {
    Foundation.Motion.animateIn(splash__$, splash__animation_map[splash__animation_name][0]);
  }
  var splash__hide = function() {
    Foundation.Motion.animateOut(splash__$, splash__animation_map[splash__animation_name][1]);
  }

  // Start: animate on page load and unload
    var document__is_ready = false;
    $(document).ready(function() {
      splash__hide();
      document__is_ready = true;
    });
    window.onpageshow = function() {
      if (document__is_ready) splash__hide();
    };
  // End: Body FadeIn

  // Start: Body FadeOut
    window.onbeforeunload = function () {
      splash__show();
    };
  //End: Load/Unload Animation

});
