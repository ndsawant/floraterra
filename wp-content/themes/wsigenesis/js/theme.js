/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

//recapcha scale
jQuery(function($) {
$(window).resize(function (){
function scaleCaptcha(elementWidth) {
  var reCaptchaWidth = 304;
  var containerWidth = $('.gform_body').width();
  // Only scale the reCAPTCHA if it won't fit
  if(reCaptchaWidth > containerWidth) {
    var captchaScale = containerWidth / reCaptchaWidth;
    $('.ginput_recaptcha').css({
      'transform':'scale('+captchaScale+')'
    });
  }
}
$(function() { 
    scaleCaptcha(); 
    });
});

});

//top scroll
jQuery(window).scroll(function () {
    console.log(jQuery(window).scrollTop());
    var viewPortSize = jQuery(window).height();
    
    var triggerAt = 300;
    var triggerHeight = viewPortSize - triggerAt;

    if (jQuery(window).scrollTop() >= triggerHeight) {
    jQuery('.uk-totop-button').fadeIn();
		
    } else {
        jQuery('.uk-totop-button').fadeOut();

    }
});

jQuery(function($){
	$('.uk-nav-sub').hide();
	$('.tm-header-mobile .uk-nav li.uk-parent').click(function(){
		$(this).children('.uk-nav-sub').toggle();
		$(this).toggleClass('uk-open');
	});
});

jQuery(function($){
$("button.uk-close").click(function(){
 $('video').trigger('pause');
});
});