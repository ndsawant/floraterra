/*
 * include the script for wsi widget section
 */

jQuery(function($) {

    $(document).on("change", "[id$='show_social_icons']", function() {
        if ($(this).is(":checked")) {
            $(this).parent().next($("div#social-icon-prop")).slideDown();
        } else {
            $(this).parent().next($("div#social-icon-prop")).slideUp();
        }
    });

    $(document).on("change", "[id$='show_image']", function() {
        if ($(this).is(":checked")) {
            $(this).parent().next($("div#image-prop")).slideDown();
        } else {
            $(this).parent().next($("div#image-prop")).slideUp();
        }
    });

    $(document).on("change", "[id$='posts_type']", function() {
        if ($(this).val() == 'page') {
            $(this).parent().parent().find($("div#post_prop")).hide();
            $(this).parent().parent().find($("p#page_prop")).slideDown();
        } else {
            $(this).parent().parent().find($("p#page_prop")).hide();
            $(this).parent().parent().find($("div#post_prop")).slideDown();
        }
    });

    $(document).on("change", "[id$='show_content']", function() {
        if ($(this).val() == 'excerpt') {
            console.log('expert');
            $(this).parent().next("div#content-prop").slideDown();
            $(this).parent().next("div#content-prop").children("p#content-limit-prop").hide();

        } else if ($(this).val() == 'content-limit') {
            $(this).parent().next("div#content-prop").slideDown();
            $(this).parent().next("div#content-prop").children("p#content-limit-prop").show();
        } else {
            $(this).parent().next("div#content-prop").slideUp();
            $(this).parent().next("div#content-prop").children("p#content-limit-prop").hide()
        }
    });

    $(document).on("change", "[id$='include_exclude']", function() {
        if ($(this).val() == 'include' || $(this).val() == 'exclude') {
            $(this).parent().parent("#exclude-include-prop").find($("p#exclude-include-filter-container")).slideDown();
        } else {
            $(this).parent().parent("#exclude-include-prop").find($("p#exclude-include-filter-container")).slideUp();
        }
    });
    
    $(document).on("click",".gp_tag_color",function(){
        if($(this).val() == 'set'){
            $(this).parent().next("div#content-set-chooser").slideDown();
        }else{
            $(this).parent().next("div#content-set-chooser").slideUp();
        }
    })
    
    $(document).on("change", "[id$='menu_style']", function() {
        if ($(this).val()=='vertical') {
            $(this).parent().next("#gp_menu_position").slideDown();
        } else {
            $(this).parent().next("#gp_menu_position").slideUp();
        }
    });


})