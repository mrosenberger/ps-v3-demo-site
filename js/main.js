(function ( $, window, document, undefined ) {
    var toggleChildren = function(event, hideSelector, showSelector){
        var $container = $(event.target).parents(".more-less:first");
        $container.find(hideSelector).hide();
        $container.find(showSelector).show();
    };
    $(".read-more").click(function(event){
        event.preventDefault();
        toggleChildren(event, '.less', '.more');
    });
    $(".read-less").click(function(event){
        event.preventDefault();
        toggleChildren(event, '.more', '.less');
    });
})( jQuery, window, document );

$( document ).ready(function() {
  $('.img-hover-zoom').popover({
    html: true,
    trigger: 'hover',
    placement: 'right',
    content: function () {
      return '<img class="img-hover-zoom" style="max-width:500px;" src="' +$(this)[0].src + '" />';
    }
  });
});