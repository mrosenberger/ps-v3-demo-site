<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/main.js"></script>

<script>
  ;(function ( $, window, document, undefined ) {
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
</script>