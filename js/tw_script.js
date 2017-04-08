jQuery(function () {
  jQuery('[data-toggle="tooltip"]').tooltip();
});

/*** Video Poster***/
jQuery(function() {
    var videos  = jQuery(".video");
    videos.on("click", function(){
        var elm = jQuery(this),
            conts   = elm.contents(),
            le      = conts.length,
            ifr     = null;
        elm_id = elm.attr('id');
        for(var i = 0; i<le; i++){
          if(conts[i].nodeType === 8){ ifr = conts[i].textContent; }
        }
        jQuery(".section-"+elm_id).css("background", "");
        elm.addClass("player").html(ifr);
        //elm.off("click");
        jQuery('.carousel').carousel('pause');
    });
});

/*** Scroll to Top ***/
function scrollToTop() {
	verticalOffset = typeof(verticalOffset) !== 'undefined' ? verticalOffset : 0;
	element = jQuery('body');
	offset = element.offset();
	offsetTop = offset.top;
	jQuery('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
}

jQuery(function(){
  "use strict";
	jQuery(document).on( 'scroll', function(){

		if (jQuery(window).scrollTop() > 100) {
			jQuery('.scroll-top-wrapper').addClass('show');
		} else {
			jQuery('.scroll-top-wrapper').removeClass('show');
		}
	});

	jQuery('.scroll-top-inner').on('click', scrollToTop);
});


/*** Carousel ***/
jQuery(document).bind('keyup', function(e) {
  "use strict";
  if(e.which === 39){
    jQuery('.carousel').carousel('next');
  }
  else if(e.which === 37){
    jQuery('.carousel').carousel('prev');
  }
});

jQuery('.carousel').carousel({
    pause: "hover",
    keyboard: true,
});




jQuery(window).load(function(){
  "use strict";
  jQuery('.widget_tw_blog_widget .articles').isotope({ itemSelector: '.article', layoutMode : 'fitRows' });

  jQuery('#related-posts .related-post-inner').isotope({ itemSelector: '.related-post-container', layoutMode : 'fitRows' });
});