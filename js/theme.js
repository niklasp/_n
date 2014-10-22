( function($) {
	'use strict';
	var $container = $('.gallery-container');
      if (typeof $container !== 'undefined') { 
        $container.imagesLoaded(function() {
          $container.masonry({
            // options
            itemSelector: '.item',
            gutter: 10,
            //isFitWidth: true,
            columnWidth: ".item:not(.large)",
            animate: true
          });  
        });
      }

  $('.gallery-container .item').on('click',function(){
    var $this = $(this);
    if ($this.hasClass('large') || !$this.parent().hasClass('gallery-container-expand')) {
      var $myurl = $(this).data('url');
      window.location.href = $myurl;
    } else {
      $this.append('<div class="loading"></div>');
    
      var $img = $this.children('img');
      $img.attr("src", $img.data('large-url'));
      
          $this.children('.loading').remove();
          $('.item').removeClass('large');
          $this.addClass('large');
          $container.masonry();
      
    }
  });

  //flipbook
  
  var $flipbook = $('.flipbook');
  
  if ($flipbook.length) {
      var flip_width = $('.flipcontainer').data("width"),
          flip_height = $('.flipcontainer').data("height"),
          view_height = $(window).height() - 120,
          flipport_width = view_height/flip_height*flip_width,
          calc_height = flipport_width/flip_width*flip_height;

      
      $('.flipbook-viewport .double').css("width", flipport_width);
      $('.flipbook-viewport .double').css("height", view_height);
      $('.flipbook-viewport .page').css("width", flipport_width/2); 

      $('.flipbook .double').scissor();
      $flipbook.turn({
        page: 2,
        autoCenter: true
      });
      $flipbook.turn("size",flipport_width,view_height);

  }
  
})(jQuery);