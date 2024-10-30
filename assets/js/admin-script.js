jQuery(document).ready(function ($) {
    // Define a variable wkMedia

    $error_status = jQuery(".bpg-error-status").val();
    if ($error_status == 0) {
        jQuery(".bpg_success_msg_box").fadeIn();
    }
    jQuery("body").on("click", ".bpg_remove_success_msg", function () {
        jQuery(this).closest('.bpg_success_msg_box').fadeOut();
    });

    /** Form Validation Start */
    /** 
     * Post With Same content tab 
     * post with different content tab 
     *  
    */
    jQuery("body").on('submit', "#bpg_same_post", function () {

        var selected_type = jQuery(".bpg_post_type_validation").find(":selected").val();
        var selected_status = jQuery(".bpg_post_status_validation").find(":selected").val();

        var error = 0;
        if (jQuery(".bpg_sub_table .bpg_post_list_validation").parent().parent().hasClass("bpg_page_post_titles")) {

            jQuery("input[name='bpg-page-title[]']").map(function () {
                var post_list = jQuery(this).val();
                if (post_list) {
                    jQuery(this).removeClass('bpg_error_border');
                } else {
                    error = 1;
                    jQuery(this).addClass('bpg_error_border');
                }
            });
        } else {

            var post_list = jQuery(".bpg_post_list_validation").val();
            if (post_list) {
                jQuery(".bpg_post_list_validation").removeClass('bpg_error_border');
            } else {
                error = 1;
                jQuery(".bpg_post_list_validation").addClass('bpg_error_border');
            }
        }

        if (selected_type) {
            jQuery(".bpg_post_type_validation").removeClass('bpg_error_border');
        } else {
            error = 1;
            jQuery(".bpg_post_type_validation").addClass('bpg_error_border');
        }
        if (selected_status) {
            jQuery(".bpg_post_status_validation").removeClass('bpg_error_border');
        } else {
            error = 1;
            jQuery(".bpg_post_status_validation").addClass('bpg_error_border');
        }

        if (error == 1) {

            return false;
        }
    });

    /** Post with Dynamic title tab validation */
    jQuery("body").on("submit", "#bpg_dynamic_post", function () {

        var keyword = jQuery("input[name=bpg_page_keyword]").val();
        var title = jQuery("input[name=bpg_page_titles]").val();
        var selected_type = jQuery("select[name=bpg_type]").find(":selected").val();
        var selected_status = jQuery("select[name=bpg_status]").find(":selected").val();

        var error = 0;

        jQuery("input[name='bpg_page_keyword_data[]']").map(function () {
            var keyword_data = jQuery(this).val();
            if (keyword_data) {
                jQuery(this).removeClass('bpg_error_border');
            } else {
                error = 1;
                jQuery(this).addClass('bpg_error_border');
            }
        });

        if (keyword) {
            jQuery("input[name=bpg_page_keyword]").removeClass('bpg_error_border');
        } else {
            error = 1;
            jQuery("input[name=bpg_page_keyword]").addClass('bpg_error_border');
        }
        if (title) {
            jQuery("input[name=bpg_page_titles]").removeClass('bpg_error_border');
        } else {
            error = 1;
            jQuery("input[name=bpg_page_titles]").addClass('bpg_error_border');
        }
        if (selected_type) {
            jQuery("select[name=bpg_type]").removeClass('bpg_error_border');
        } else {
            error = 1;
            jQuery("select[name=bpg_type]").addClass('bpg_error_border');
        }
        if (selected_status) {
            jQuery("select[name=bpg_status]").removeClass('bpg_error_border');
        } else {
            error = 1;
            jQuery("select[name=bpg_status]").addClass('bpg_error_border');
        }

        if (error == 1) {
            return false;
        }
    });

    /** Form Validation End */

    jQuery("body").on("change", "#bpg_type", function () {
        $this = jQuery(this).val();
        if ($this == '') {
            jQuery(".bpg_page_post_parent_page").fadeIn();
            jQuery(".bpg_page_post_template").fadeIn();
            jQuery('.bpg_page_post_excerpt').fadeOut();
        }
        else {
            if ($this == 'page') {
                jQuery(".bpg_page_post_parent_page").fadeIn();
                jQuery(".bpg_page_post_template").fadeIn();
                jQuery('.bpg_page_post_excerpt').fadeOut();
            }
            else {
                jQuery('.bpg_page_post_excerpt').fadeIn();
                jQuery('.bpg_page_post_parent_page').fadeOut();
                jQuery('.bpg_page_post_template').fadeOut();
            }
        }

    })

    /** Wordpress media select */
    var wkMedia;
    jQuery("body").on("click", ".bpg-upload-button", function (e) {
        var $this = jQuery(this);
        e.preventDefault();
        // Extend the wp.media object
        wkMedia = wp.media.frames.file_frame = wp.media({
            title: 'Select media',
            button: {
                text: 'Select media'
            }
        });

        // When a file is selected, grab the URL and set it as the text field's value
        wkMedia.on('select', function () {
            var attachment = wkMedia.state().get('selection').first().toJSON();
            $this.parent().find(".bpg-media-url").show();
            $this.parent().find(".bpg-remove-image-button").show();
            $this.parent().find(".bpg-media-url").attr('src', attachment.url);
            $this.parent().find(".bpg_media_hidden_url").val(attachment.url);
        });
        // Open the upload dialog
        wkMedia.open();
    });
    jQuery("body").on("click", ".bpg-remove-image-button", function () {
        $this = jQuery(this);
        $this.parent().find(".bpg-media-url").hide();
        $this.parent().find(".bpg-media-url").attr("src", "");
        $this.parent().find(".bpg_media_hidden_url").attr("value", "");

        $this.hide();
    });

    /** Editor initialize  */
    var counter = 0;
    wp.editor.initialize("bpg_content", {
        mediaButtons: true,
        tinymce: {
            theme: 'modern',
            skin: 'lightgray',
            language: 'en',
            formats: {
                alignleft: [{
                    selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li',
                    styles: {
                        textAlign: 'left'
                    }
                },
                {
                    selector: 'img, table, dl.wp-caption',
                    classes: 'alignleft'
                }
                ],
                aligncenter: [{
                    selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li',
                    styles: {
                        textAlign: 'center'
                    }
                },
                {
                    selector: 'img, table, dl.wp-caption',
                    classes: 'aligncenter'
                }
                ],
                alignright: [{
                    selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li',
                    styles: {
                        textAlign: 'right'
                    }
                },
                {
                    selector: 'img, table, dl.wp-caption',
                    classes: 'alignright'
                }
                ],
                strikethrough: {
                    inline: 'del'
                }
            },
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,

            entities: '38, amp, 60, lt, 62, gt ',
            entity_encoding: 'raw',
            keep_styles: false,
            paste_webkit_styles: 'font-weight font-style color',
            preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
            tabfocus_elements: ': prev ,: next',
            plugins: 'charmap, hr, media, paste, tabfocus, textcolor, wordpress, wpeditimage, wpgallery, wplink, wpdialogs, wpview',
            resize: 'vertical',
            menubar: false,
            indent: false,
            toolbar1: 'bold, italic, strikethrough, bullist, numlist, blockquote, hr, alignleft, aligncenter, alignright, link, unlink, wp_more, spellchecker, wp_adv',
            toolbar2: 'formatselect, underline, alignjustify, forecolor, pastetext, removeformat, charmap, outdent, indent, undo, redo, wp_help',
            toolbar3: '',
            toolbar4: '',
            body_class: 'id post-type-post-status-publish post-format-standard',
            wpeditimage_disable_captions: false,
            wpeditimage_html5_captions: true

        },
        quicktags: true
    });

    /** Multiple Pages/Posts With Content start */
    jQuery("body").on("click", ".bpg_create_page", function () {
        $this = jQuery(this);
        $type = jQuery("#bpg_type").val();
        jQuery.ajax({
            url: bpgObj.ajaxurl,
            type: "POST",
            data: {
                action: "bpg_add_more",
                type: $type,
                counter: counter
            },
            success: function (response) {
                jQuery(response).insertAfter($this.parents('.bpg_sub_table').find('tbody').last());
                wp.editor.initialize("bpg_content_" + counter, {
                    mediaButtons: true,
                    tinymce: {
                        theme: 'modern',
                        skin: 'lightgray',
                        language: 'en',
                        formats: {
                            alignleft: [{
                                selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li',
                                styles: {
                                    textAlign: 'left'
                                }
                            },
                            {
                                selector: 'img, table, dl.wp-caption',
                                classes: 'alignleft'
                            }
                            ],
                            aligncenter: [{
                                selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li',
                                styles: {
                                    textAlign: 'center'
                                }
                            },
                            {
                                selector: 'img, table, dl.wp-caption',
                                classes: 'aligncenter'
                            }
                            ],
                            alignright: [{
                                selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li',
                                styles: {
                                    textAlign: 'right'
                                }
                            },
                            {
                                selector: 'img, table, dl.wp-caption',
                                classes: 'alignright'
                            }
                            ],
                            strikethrough: {
                                inline: 'del'
                            }
                        },
                        relative_urls: false,
                        remove_script_host: false,
                        convert_urls: false,

                        entities: '38, amp, 60, lt, 62, gt ',
                        entity_encoding: 'raw',
                        keep_styles: false,
                        paste_webkit_styles: 'font-weight font-style color',
                        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
                        tabfocus_elements: ': prev ,: next',
                        plugins: 'charmap, hr, media, paste, tabfocus, textcolor, wordpress, wpeditimage, wpgallery, wplink, wpdialogs, wpview',
                        resize: 'vertical',
                        menubar: false,
                        indent: false,
                        toolbar1: 'bold, italic, strikethrough, bullist, numlist, blockquote, hr, alignleft, aligncenter, alignright, link, unlink, wp_more, spellchecker, wp_adv',
                        toolbar2: 'formatselect, underline, alignjustify, forecolor, pastetext, removeformat, charmap, outdent, indent, undo, redo, wp_help',
                        toolbar3: '',
                        toolbar4: '',
                        body_class: 'id post-type-post-status-publish post-format-standard',
                        wpeditimage_disable_captions: false,
                        wpeditimage_html5_captions: true

                    },
                    quicktags: true
                });

                counter++;
            },
        });
    });
    jQuery("body").on("click", ".bpg_remove_page", function () {
        $(this).closest("tbody").fadeOut("normal", function () {
            $(this).remove();
        });
    });
    /** Multiple Pages/Posts With Content end */


    /** Multiple Pages/Posts With Dynamic Title */
    /** Keyword repeater JS start */
    jQuery("body").on("click", ".bpg_create_keyword_data", function () {
        $html = '<tr><th></th><td class="bpg_btns"><input type="text" class="bpg_text" value=""  name="bpg_page_keyword_data[]"><div class="bpg_add_key"><button type="button" class="bpg_create_keyword_data bpg_plus_btn">+</button><button  type="button" class="bpg_remove_keyword_data bpg_minus_btn">-</button></div></td></tr>';
        jQuery($html).insertAfter(jQuery(this).closest('tr'));
        jQuery(this).remove();
    });
    jQuery("body").on("click", ".bpg_remove_keyword_data", function () {
        $this = $(this).closest("tr");
        $prev = $this.prev();
        $last = jQuery('.bpg_form-table').find('.bpg_remove_keyword_data').last().closest("tr");
        if ($this.html() == $last.html()) {
            jQuery($prev.find('.bpg_add_key').prepend('<button type="button" class="bpg_create_keyword_data bpg_plus_btn">+</button>'));
        }
        $this.fadeOut("normal", function () {
            $(this).remove();
        });
    });
    jQuery("body").on("keyup change", "#bpg_page_keyword", function () {
        jQuery(this).next('.bpg_key').val('{' + jQuery(this).val() + '}');
    });
    /** Keyword repeater JS end */

    /**  Keyword Copy JS Start */
    jQuery("body").on("click", "#bpg_copy_icon", function () {
        var copyText = document.getElementById("bpg_shortcode_copy");

        var text = copyText.value;
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);

        var tooltip = document.getElementById("bpg_shortcodeTooltip");
        tooltip.innerHTML = "Copied!";
    });
    jQuery("body #bpg_shortcode_copy").mouseout(function () {
        var tooltip = document.getElementById("bpg_shortcodeTooltip");
        tooltip.innerHTML = "Copy to clipboard";
    });
    /**  Keyword Copy JS End */
});