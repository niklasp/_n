( function($) {
	'use strict';

  function content_padding() {
    //set the content padding according to the menu height
    var $header = $('#masthead');
    var $header_height = $header.height();
    $('#content').css("padding-top", $header_height);    
  }

  content_padding();

  $(window).on('resize', function() {
    content_padding();
  });

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
      $('.item img', $container).addClass('not-loaded');
      
      $('.item img.not-loaded').lazyload({
          effect: 'fadeIn',
          load: function() {
              // Disable trigger on this image
      
              $(this).removeClass("not-loaded");
              $container.masonry();
          }
      });
      $('.item img.not-loaded').trigger('scroll');            
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

  var $bxslider = $('.bxslider');
  if ($bxslider.length) {
      $bxslider.css('min-height','300px');
      var $spinner = $('<div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>');
      var $bxinstance = $bxslider.bxSlider({
        adaptiveHeight: true,
        slideWidth: 600,
        mode: 'fade',
        pagerCustom: '#bx-pager',
        onSliderLoad: function(){
          $('li', $bxslider).css('visibility', 'visible');
        },
        onSlideBefore: function($slideElement, oldIndex, newIndex){
            var $lazy = $slideElement.find(".lazy");
                        
            $lazy.css('visibility','hidden');
            $slideElement.append($spinner);            
            imagesLoaded($lazy, function() {$spinner.remove();$lazy.css('visibility','visible');});
            var $load = $lazy.attr("data-src");
            $lazy.attr("src",$load).removeClass("lazy");
        }
      });
      $('.bx-right').on('click', function() {
        $bxinstance.goToNextSlide();
      });
      $('.bx-left').on('click', function() {
        $bxinstance.goToPrevSlide();
      });
  }

  if ($('#cuboid').length) {
    imagesLoaded('#cuboid', function() {
       $("#cuboid").cuboid({
        sides: "6"
      });     
     });
  }

  var $spinning_container = $('.spinning-container');
  if ($spinning_container.length) {
    var images = $spinning_container.children();
    $spinning_container.height(images[0].height);
    var $new_width, $new_height;
    imagesLoaded($(images[0]), function() {
      $new_width = -$(images[0]).width() / 2;
      $new_height = -$(images[0]).height() / 2 + 100;
      f($(images[0]));
    });
    var f = function addThatNewsPage(elem) {
      elem.css("display", "block");
      elem.css("margin-top",$new_height + "px");
      elem.css("margin-left",$new_width + "px");
      elem.attr("src",elem.data("src"));
      $spinning_container.append(elem);
    };    
    $.each(images, function(i) {
      var $this = $(this);
      var $image = $(images[i]);
      var $offset = Math.floor((Math.random() * 200) + 1) + "px";

      $spinning_container.append($image);
      // $this.one('webkitAnimationEnd oanimationend msAnimationEnd animationend',   
      //     function(e) {
      //     if((i%2) === 0) {
      //       $this.animate({"margin-left": "+=" + $offset});
      //     } else {
      //       $this.animate({"margin-left": "-=" + $offset});
      //     }
      // });
      $this.on('click', function() {
        if((i%2) === 0) {
          $this.animate({"margin-left": "+=" + $offset});
        } else {
          $this.animate({"margin-left": "-=" + $offset});
        }
        var idx = (i + 1) % images.size();
        var elem = $(images[idx]);
        imagesLoaded(elem,function() {
          f(elem);
        });
      });
    });
  }


  
})(jQuery);