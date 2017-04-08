jQuery(document).ready(function($) {
  if(jQuery('#wpadminbar').length) {
		jQuery('#psp-title').animate({ top : "30px"},250);
	}
});

jQuery('#nav-menu a').smoothScroll({offset: -165});

function hasScrolled() {
    var st = jQuery(this).scrollTop();

    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;

    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        jQuery('#psp-title').animate({ top : "32px"},250);

    } else {
        // Scroll Up
        if(st + jQuery(window).height() < jQuery(document).height()) {
			if(jQuery('#wpadminbar').length) {
				jQuery('#psp-title').animate({ top : "30px"},250);
			} else {
				jQuery('#psp-title').animate({ top : "0px"},250);
        	}
		}
    }

    lastScrollTop = st;
}