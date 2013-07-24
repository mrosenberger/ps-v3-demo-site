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