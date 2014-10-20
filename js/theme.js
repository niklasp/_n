( function($) {
	'use strict';
	var $container = $('.gallery-container');
      if (typeof $container !== 'undefined') { 
        //$container.imagesLoaded(function() {
          $container.masonry({
            // options
            itemSelector: '.item',
            gutter: 10,
            //isFitWidth: true,
            columnWidth: ".item:not(.large)",
            animate: true
          });  
        //});
      }

  $('.item').on('click',function(){
    var $this = $(this);
    if ($this.hasClass('large')) {
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
})(jQuery);