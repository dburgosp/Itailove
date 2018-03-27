$(function(){
  $(".flo-core-style").each(function(){
    var template = $(this);
    var style = template.html();
    $("head").append(style);
    template.remove();
  });
  $( "<style>body *{outline: solid transparent;}body { opacity: 1; }</style>" ).appendTo( "head" );
});
