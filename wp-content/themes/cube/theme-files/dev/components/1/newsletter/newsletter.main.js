$(function(){

  var $form = $(".flo-footer__newsletter-form");
  if ($form.length) {
    // Start: Validation
      $form.parsley();
    // End: Validation

    // Start: Mailchimp Subscription
      var
      embed_code =
        unescape(
          $form.parent().find(".embed_code").text()
        ),
      $embed_code = $("<div>").html(embed_code),
      embed_form_action
      ;

      if(typeof $embed_code.find("form").attr("action") != 'undefined'){
        embed_form_action = $embed_code.find("form").attr("action").replace(/\\"/g, '')
        $form.attr("action", embed_form_action);
      }
      
    // End: Mailchimp Subscription`
  }
});
