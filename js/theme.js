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
})(jQuery);