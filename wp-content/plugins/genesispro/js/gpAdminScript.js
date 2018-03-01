/**
 * Genesis Pro admin script
 * only includes admin side functionality
 *
 * @package Genesis Pro
 * @author Swapnil Ghone
 */

jQuery(function($) {

    //Tabs
    $('.tabs li a').click(function() {
        $('.tabs li').removeClass('uk-active');
        $(this).parent().addClass('uk-active');
        $('.nav').fadeOut(250);
        var index = $('.tabs li a').index(this);
        $('.nav').eq(index).fadeIn(250);

        if ($('.nav').eq(index).html() == '') {
            $('.nav').eq(index).html('Comming Soon...').css({
                'color': 'red',
                'font-size': '16px'
            });
        }
        return false;
    });

    $(".vtab-content").hide();
    jQuery(".gp_ver_tab_wrap").each(function(i, t) {
        jQuery(t).find(".vtab-content:first").show();
    })
    $("#gp_ver_tab_wrap .vtab li a").click(function() {
        $(this).parent('li').siblings('li').removeClass('vtab-active');
        $(this).parent('li').addClass('vtab-active');
        $(this).closest('#gp_ver_tab_wrap').find(".vtab-content").fadeOut(250);
        $("#" + $(this).attr('rel')).fadeIn(250);
        if ($(this).attr('rel') == 'gp_social_stream_wrap') {
            $("#facebook").fadeIn(250);
        }
    })


    // save loading action
    $("form[alt=gp_update]").each(function() {
        var id = "#" + $(this).attr('id');
        var submit_id = "#" + $(this).find('input[type="submit"]').attr('id');
        var loading_id = "#loading_" + $(this).attr('id');
        var img = "/wp-content/plugins/genesispro/images/loading.gif";
        $(id).before('<div id="loading_' + $(this).attr('id') + '"></div>');
        $(submit_id).click(function(e) {
            console.log('hi');
            e.preventDefault();
            gp_loading(loading_id, id, img);
            return false;
        });
    })

    // action for unselect all
    $('.unselect-all').click(function() {
        $('#' + $(this).attr('rel')).attr('value', '');
        $(this).parent('td').find('select option').removeAttr('selected');
    });

    /*
     * Redirect Js
     */
    $("input.gp_url_type").on('click', function() {
        $('#gp_add_redirect').attr('disabled', 'disabled').css('opacity', '0.2');
        $("#load_new_url_input").html('');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: {
                action: "gp_redirect_options",
                command: "get_url_type",
                url_type: $(this).val()
            }, // serializes the form's elements.
            success: function(data) {
                $('#gp_add_redirect').removeAttr('disabled').css('opacity', '1');
                $("#load_new_url_input").html(data);

            }
        });
    });

    setTimeout(function() {
        jQuery("#page_type").click();
    }, 10);

    $("form[alt=gp_redirect]").each(function() {
        var submit_id = "#" + $(this).find('input[type="submit"]').attr('id');
        var loading_id = "#loading_" + $(this).attr('id');
        var img = "/wp-content/plugins/genesispro/images/loading.gif";
        $("#add_redirect").before('<div id="loading_' + $(this).attr('id') + '"></div>');
        $(submit_id).on('click', function() {

            $(loading_id).html('<img src=' + img + '>');
            $(loading_id).addClass('loading');
            $("#add_redirect").css({
                'opacity': '0.3'
            });
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: $('#add_redirect').serialize(), // serializes the form's elements.
                success: function(data) {
                    $(loading_id).removeClass('loading');
                    $(loading_id).html('');
                    $('#add_redirect').removeAttr('style');
                    var is_present = false;
                    var present_index;

                    // check if url is already present or not
                    $("table#redirect_url_list tr").each(function(i, t) {
                        if (data.source_url == $(t).find('td:first').html()) {
                            is_present = true;
                            present_index = i;
                        }
                    })
                    $("table#redirect_url_list tr").removeClass('new_added');
                    var tr = "<tr alt='" + data.alt + "' class='new_added'>";

                    tr += "<th><input type='checkbox' name='urls' value='' class='urls' /></th>";
                    tr += "<td width='30%'>" + data.source_url + "</td>";
                    tr += "<td width='30%' >" + data.destination_url + "</td>";
                    tr += "<td width='20%'>" + data.url_type + "</td>";
                    tr += "<td width='20%'><a href='javascript:void(0)' title='Edit' class='edit_url'><span class='dashicons dashicons-edit'></span></a>   <a href='javascript:void(0)' title='Delete' class='delete_url'><span class='dashicons dashicons-trash'></span></a></td></tr>";
                    if (is_present) {
                        jQuery('table#redirect_url_list tr').eq(present_index).remove();
                    }
                    jQuery('table#redirect_url_list tbody').prepend(tr);

                    jQuery('table#redirect_url_list tbody tr').removeClass('editable');
                    jQuery('#add_redirect').find('input[type=text]').attr('value', '');
                }
            });
            jQuery("#gp_source_url").removeAttr('readonly');
            return false;
        });
    });

    jQuery(document).on('click', '#redirect_url_list .delete_url', function() {
        if (confirm('Are you Sure!')) {
            jQuery(this).parents("tr").addClass("recently_deleted")
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    action: 'gp_redirect_options',
                    target_url: jQuery(this).parents('tr').attr('alt'),
                    command: "delete",
                }, // serializes the form's elements.
                success: function(data) {
                    jQuery("#redirect_url_list .recently_deleted").remove();
                }
            });
        } else {
            return false;
        }
    });

    jQuery("input[name=all]").click(function() {
        jQuery(".urls").attr('checked', this.checked);
    });



    jQuery('#redirect_delete_multiple').click(function() {
        if (confirm('Are you Sure!')) {
            var selected = new Array();
            jQuery('.urls:checkbox:checked').each(function() {
                jQuery(this).parents("tr").addClass("recently_deleted")
                selected.push(jQuery(this).val());
            });
            console.log(selected);
            if (selected.length === 0) {
                alert("Please select any one");
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    action: 'gp_redirect_options',
                    target_url: selected,
                    command: 'delete_selected'
                }, // serializes the form's elements.
                success: function(data) {
                    jQuery("#redirect_url_list .recently_deleted").remove();
                }
            });
        } else {
            return false;
        }
    })

    jQuery(document).on('click', '#redirect_url_list .edit_url', function() {

        jQuery("#redirect_url_list .edit_url").parents("tr").removeClass('editable');
        jQuery(this).parents("tr").addClass("editable");

        jQuery("#gp_source_url").val($(this).parents("tr").attr('alt')).prop('readonly', true);
        var url_type = $('.editable td').eq(2).text();
        var post_page_id = jQuery('.editable td').eq(1).attr('alt');

        jQuery("#add_redirect input[id=" + url_type + "_type]").click();

        setTimeout(function() {
            if (url_type == 'custom') {
                jQuery("#destination_url").val(post_page_id);
            } else {
                jQuery("#add_redirect select#destination_url option").removeAttr('selected');
                jQuery("#add_redirect select#destination_url option[value=" + post_page_id + "]").attr('selected', 'selected');
            }
        }, 2000);

        return false;
    });

    /*
     * end of redirect code
     */

    /*
     * Script for social share
     */
    if ($("#gp_socialshare_theme:checked").val() == 'popout') {
        $("#gp_socialshare_size[value='small']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
        $("#gp_socialshare_size[value='rectangle']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
        $("#gp_socialshare_counter[value='top']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
        $("#gp_socialshare_counter[value='side']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
    }

    if ($("#gp_socialshare_size:checked").val() == 'small') {
        $("#gp_socialshare_counter[value='top']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
        $("#gp_socialshare_counter[value='badge']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');

    } else if ($("#gp_socialshare_size:checked").val() == 'rectangle') {

        $("#gp_socialshare_counter[value='badge']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');

    }

    $("#gp_social_share input:radio").each(function(i, t) {
        $(t).click(function() {
            $("#gp_social_share input:radio[name='" + $(this).attr('name') + "']").parent().removeClass('ss-active');
            $(this).parent().addClass('ss-active');
            // conditions to disable size and counter options for popout theme 
            if ($(t).attr('id') == 'gp_socialshare_theme') {
                if ($(t).val() == 'popout') {
                    $("#gp_socialshare_size").click();
                    $("#gp_socialshare_size[value='small']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                    $("#gp_socialshare_size[value='rectangle']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                    $("#gp_socialshare_counter").click();
                    $("#gp_socialshare_counter[value='top']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                    $("#gp_socialshare_counter[value='side']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                } else {
                    $("#gp_socialshare_size[value='small']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                    $("#gp_socialshare_size[value='rectangle']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                    $("#gp_socialshare_counter[value='top']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                    $("#gp_socialshare_counter[value='side']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                }
            }

            // condition to disable counter option for small size
            if ($(t).attr('id') == 'gp_socialshare_size') {
                if ($(t).val() == 'small') {
                    $("#gp_socialshare_counter").click();
                    $("#gp_socialshare_counter[value='top']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                    $("#gp_socialshare_counter[value='badge']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                } else if ($(t).val() == 'rectangle') {
                    $("#gp_socialshare_counter").click();
                    $("#gp_socialshare_counter[value='badge']").attr('disabled', 'disabled').next('span').addClass('gpss_icon_disable');
                    $("#gp_socialshare_counter[value='top']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                } else {
                    $("#gp_socialshare_counter[value='top']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                    $("#gp_socialshare_counter[value='badge']").removeAttr('disabled').next('span').removeClass('gpss_icon_disable');
                }
            }

        })
    })

    $("#gp_social_share input:checkbox").each(function(i, t) {
        $(t).click(function() {
            $(t).parent().toggleClass('ss-active');
        })
    })

    $(".services_toggle").sortable();

    /*
     * script for social icons
     */

    // Add Color Picker to all inputs that have 'color-field' class
    $('.color-field').wpColorPicker();
    $('.color-hover-field').wpColorPicker();
    $('[data-socailicon="iconpicker"]').fontIconPicker();

    $('body').on('click', '#gp_add_new_icon', function() {
        var opt = $("#gp_cp_options").html();
        $(".gp_socailicon_wrap").append('<div class="gp_section" ><select name="gp_socialicon[social][icon][]" data-socailicon="iconpicker" class="myselect"><option value="">No icon</option>' + opt + '</select><div class="gp_right-section"><input type="text" name="gp_socialicon[social][url][]" placeholder="Enter your url here"><input type="text" name="gp_socialicon[social][alt][]"  placeholder="Enter alt text for link"> <input type="hidden" name="gp_socialicon[social][type][]"  value="font_icon"><button id="gp_remove_icon" class="gp-button">Remove</button></div></div>');
        $('[data-socailicon="iconpicker"]').fontIconPicker();
    })

    $('body').on('click', '#gp_add_new_custom_icon', function() {
        var row = $("#gp_custom_icon_html").html();
        $(".gp_socailicon_wrap").append(row);
    });

    jQuery('body').on('click', '#gp_remove_icon', function() {
        $(this).parents('.gp_section').remove();
    })

    jQuery('body').on('click', '[alt="gp_add_social_icon"]', function(e) {
        e.preventDefault();
        wsig_get_image_path(this, 'social_icon');
        return false;
    });

    jQuery('body').on('click', '[alt="gp_add_site_icon"]', function(e) {
        e.preventDefault();
        wsig_get_image_path(this, 'icon');
        return false;
    });

    jQuery('body').on('click', '[alt="gp_remove_image"]', function(e) {
        e.preventDefault();
        var id = jQuery(this).data('source');
        jQuery(id).val('');
        jQuery(this).parent('.gp-image-warp').html('No image selected ');
        return false;
    });

    $('[id^=gp_setting_sitemap_]').click(function() {
        if ($(this).val() == 1) {
            $("#gp_sitemap_config").slideDown('slow');
        } else {
            $("#gp_sitemap_config").slideUp('slow');
        }
    })

    /*
     *   local seo code
     */


    $("form[alt=gp_local_kml]").each(function() {
        var submit_id = "#" + $(this).find('input[type="submit"]').attr('id');
        var loading_id = "#loading_" + $(this).attr('id');
        var img = "/wp-content/plugins/genesispro/images/loading.gif";
        $("#add_location").before('<div id="loading_' + $(this).attr('id') + '"></div>');
        $(submit_id).on('click', function() {

            $(loading_id).html('<img src=' + img + '>');
            $(loading_id).addClass('loading');
            $("#add_location").css({
                'opacity': '0.3'
            });
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: $('#add_location').serialize(), // serializes the form's elements.
                success: function(data) {
                    $(loading_id).removeClass('loading');
                    $(loading_id).html('');
                    $('#add_location').removeAttr('style');
                    var is_present = false;
                    var present_index;

                    // check if url is already present or not
                    $("table#location_list tr").each(function(i, t) {
                        var match_text = $(t).find('td:first').html()
                        if (data.name == match_text || data.match == match_text) {
                            is_present = true;
                            present_index = i;
                        }
                    })
                    $("table#location_list tr").removeClass('new_added');
                    var tr = "<tr alt='" + data.alt + "' class='new_added'>";

                    tr += "<th><input type='checkbox' name='urls' value='" + data.alt + "' class='urls_sel' /></th>";
                    tr += "<td width='30%'>" + data.name + "</td>";
                    tr += "<td width='30%' >" + data.address + "</td>";
                    tr += "<td width='20%'><a href='javascript:void(0)' title='Edit' class='edit_url'><span class='dashicons dashicons-edit'></span></a>   <a href='javascript:void(0)' title='Delete' class='delete_url'><span class='dashicons dashicons-trash'></span></a></td></tr>";
                    if (is_present) {
                        jQuery('table#location_list tr').eq(present_index).remove();
                    }
                    jQuery('table#location_list tbody').prepend(tr);

                    jQuery('table#location_list tbody tr').removeClass('editable');
                    jQuery('#add_location').find('input[type=text]').attr('value', '');
                }
            });
            jQuery("#gp_source_url").removeAttr('readonly');
            return false;
        });
    });


    jQuery(document).on('click', '#location_list .edit_location', function() {
        jQuery("#location_list .edit_location").parents("tr").removeClass('editable');
        jQuery("#add_location #edit_key").val($(this).parents('tr').find('#lc_name').val());
        jQuery("#add_location #gp_kml_location_name").val($(this).parents('tr').find('#lc_name').val());
        jQuery("#add_location #gp_kml_location_address").val($(this).parents('tr').find('#lc_address').val());
        jQuery("#add_location #gp_kml_location_city").val($(this).parents('tr').find('#lc_city').val());
        jQuery("#add_location #gp_kml_location_state").val($(this).parents('tr').find('#lc_state').val());
        jQuery("#add_location #gp_kml_location_zip").val($(this).parents('tr').find('#lc_zip').val());
        jQuery("#add_location #gp_kml_location_country").val($(this).parents('tr').find('#lc_country').val());
        jQuery("#add_location #gp_kml_location_phone").val($(this).parents('tr').find('#lc_phno').val());
        jQuery("#add_location #gp_kml_location_desc").val($(this).parents('tr').find('#lc_desc').val());
    })


    jQuery(document).on('click', '#location_list .delete_location', function() {
        if (confirm('Are you Sure!')) {
            jQuery(this).parents("tr").addClass("recently_deleted")
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    action: 'gp_kml_location',
                    loc: jQuery(this).parents('tr').attr('alt'),
                    command: "delete",
                }, // serializes the form's elements.
                success: function(data) {
                    jQuery("#location_list .recently_deleted").remove();
                }
            });
        } else {
            return false;
        }
    });

    jQuery("input[name=all_loc]").click(function() {
        jQuery(".urls_sel").attr('checked', this.checked);
    });



    jQuery('#location_delete_multiple').click(function() {
        if (confirm('Are you Sure!')) {
            var selected = new Array();
            jQuery('.urls_sel:checkbox:checked').each(function() {
                jQuery(this).parents("tr").addClass("recently_deleted")
                selected.push(jQuery(this).val());
            });
            if (selected.length === 0) {
                alert("Please select any one");
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    action: 'gp_kml_location',
                    locations: selected,
                    command: 'delete_selected'
                }, // serializes the form's elements.
                success: function(data) {
                    jQuery("#location_list .recently_deleted").remove();
                }
            });
        } else {
            return false;
        }
    })

})

/*--------------------------Jquery For get Image path-----------------------------*/
function wsig_get_image_path(obj, type) {
    console.log(obj);
    console.log(type);
    if (type == '') {
        type = 'icon';
    }
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html) {
        var fileInput = jQuery(obj);
        var id = "#" + jQuery(obj).attr('id');
        if (fileInput) {
            if (jQuery(html).attr('src') !== undefined) {
                fileurl = jQuery(html).attr('src');
            } else if (jQuery(html).find('img')) {
                fileurl = jQuery(html).find('img').attr('src');
            } else {
                fileurl = false;
            }

            if (fileurl) {
                new_fileurl = fileurl.replace(jQuery("#website_url").val(), '');
            }

            if (type == 'social_icon') {
                jQuery(fileInput).parents('.gp_section').find('#gp_socialicon_path').val(new_fileurl);
                jQuery(fileInput).parent('.img_wrap').html('<img src="' + fileurl + '" width="40px" height="40px">');
            } else {
                var width = jQuery(fileInput).data('width');
                if (width == 'undefined') {
                    width = 40;
                }
                var height = jQuery(fileInput).data('height');
                if (height == 'undefined') {
                    height = 40;
                }
                jQuery(id + "_val").val(new_fileurl);
                jQuery(fileInput).parent('.gp-image-container').find('.gp-image-warp').html('<img src="' + fileurl + '" width="' + width + 'px" height="' + height + 'px">\n\
<input type="button" value="Remove" alt="gp_remove_image" data-source="' + id + '_val">');
            }
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    };
    return false;
}

function gp_loading(loading_class, div_location, img) {
    var loading = jQuery(loading_class);
    jQuery(loading).html('<img src=' + img + '>');
    jQuery(loading).addClass('loading');
    jQuery(div_location).css({
        'opacity': '0.3'
    });
    jQuery.ajax({
        type: "post",
        url: ajaxurl,
        data: jQuery(div_location).serialize(), // serializes the form's elements.
        success: function(data) {
            jQuery(loading).removeClass('loading');
            jQuery(loading).html('');
            jQuery(div_location).removeAttr('style');
        }
    });
}


