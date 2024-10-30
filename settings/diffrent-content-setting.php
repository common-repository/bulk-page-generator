<?php
if (!class_exists('bpg_diffrent_content_settings')) {
    class bpg_diffrent_content_settings
    {
        public function __construct(){
            add_action('admin_init', array($this, 'bpg_diffrent_content_settings_init'));
        }
        function bpg_diffrent_content_settings_init(){
            $post_types = get_post_types(array('public' => true));
            if (isset($_POST['submit_diffrent_content'])) {
                if (wp_verify_nonce($_POST['bulk_page_generator_nonce'], 'bulk_page_generator_nonce')) {
                    $prefix_word = filter_input(INPUT_POST, 'bpg_page_prefix', FILTER_SANITIZE_SPECIAL_CHARS);
                    $prefix_word = sanitize_text_field(wp_unslash($prefix_word));

                    $postfix_word = filter_input(INPUT_POST, 'bpg_page_postfix', FILTER_SANITIZE_SPECIAL_CHARS);
                    $postfix_word = sanitize_text_field(wp_unslash($postfix_word));

                    $type = filter_input(INPUT_POST, 'bpg_type', FILTER_SANITIZE_SPECIAL_CHARS);
                    $type = sanitize_text_field(wp_unslash($type));

                    $page_status = filter_input(INPUT_POST, 'bpg_status', FILTER_SANITIZE_SPECIAL_CHARS);
                    $page_status = sanitize_text_field(wp_unslash($page_status));

                    $template_name = filter_input(INPUT_POST, 'bpg_template', FILTER_SANITIZE_SPECIAL_CHARS);
                    $template_name = sanitize_text_field(wp_unslash($template_name));

                    $comment_status = filter_input(INPUT_POST, 'bpg_comment', FILTER_SANITIZE_SPECIAL_CHARS);
                    $comment_status = ((empty($comment_status) ? get_default_comment_status($type) : $comment_status));
                    $comment_status = sanitize_text_field(wp_unslash($comment_status));

                    $authors = filter_input(INPUT_POST, 'bpg_author', FILTER_SANITIZE_SPECIAL_CHARS);
                    $authors = sanitize_text_field(wp_unslash($authors));

                    $parent_page_id = filter_input(INPUT_POST, 'bpg_parent_id', FILTER_SANITIZE_SPECIAL_CHARS);
                    $parent_page_id = sanitize_text_field(wp_unslash($parent_page_id));

                    $page_list = array_map('sanitize_text_field', $_POST['bpg-page-title']);
                    
                    if (array_key_exists($type, $post_types)) {
                        $error = 1;
                        foreach ($page_list as $key => $value) {

                            $allowedposttags = array(
                                'address'    => array(),
                                'a'          => array('href' => true, 'rel'      => true, 'rev'      => true, 'name'     => true, 'target'   => true, 'download' => array('valueless' => 'y',),),
                                'abbr'       => array(),
                                'acronym'    => array(),
                                'area'       => array('alt'    => true, 'coords' => true, 'href'   => true, 'nohref' => true, 'shape'  => true, 'target' => true,),
                                'article'    => array('align' => true,),
                                'aside'      => array('align' => true,),
                                'audio'      => array('autoplay' => true, 'controls' => true, 'loop'     => true, 'muted'    => true, 'preload'  => true, 'src'      => true,),
                                'b'          => array(),
                                'bdo'        => array(),
                                'big'        => array(),
                                'blockquote' => array('cite' => true,),
                                'br'         => array(),
                                'button'     => array('disabled' => true, 'name'     => true, 'type'     => true, 'value'    => true,),
                                'caption'    => array('align' => true,),
                                'cite'       => array(),
                                'code'       => array(),
                                'col'        => array('align'   => true, 'char'    => true, 'charoff' => true, 'span'    => true, 'valign'  => true, 'width'   => true,),
                                'colgroup'   => array('align'   => true, 'char'    => true, 'charoff' => true, 'span'    => true, 'valign'  => true, 'width'   => true,),
                                'del'        => array('datetime' => true,),
                                'dd'         => array(),
                                'dfn'        => array(),
                                'details'    => array('align' => true, 'open'  => true,),
                                'div'        => array('align' => true,),
                                'dl'         => array(),
                                'dt'         => array(),
                                'em'         => array(),
                                'fieldset'   => array(),
                                'figure'     => array('align' => true,),
                                'figcaption' => array('align' => true,),
                                'font'       => array('color' => true, 'face'  => true, 'size'  => true,),
                                'footer'     => array('align' => true,),
                                'h1'         => array('align' => true,),
                                'h2'         => array('align' => true,),
                                'h3'         => array('align' => true,),
                                'h4'         => array('align' => true,),
                                'h5'         => array('align' => true,),
                                'h6'         => array('align' => true,),
                                'header'     => array('align' => true,),
                                'hgroup'     => array('align' => true,),
                                'hr'         => array('align'   => true, 'noshade' => true, 'size'    => true, 'width'   => true,),
                                'i'          => array(),
                                'img'        => array('alt'      => true, 'align'    => true, 'border'   => true, 'height'   => true, 'hspace'   => true, 'loading'  => true, 'longdesc' => true, 'vspace'   => true, 'src'      => true, 'usemap'   => true, 'width'    => true,),
                                'ins'        => array('datetime' => true, 'cite'     => true,),
                                'kbd'        => array(),
                                'label'      => array('for' => true,),
                                'legend'     => array('align' => true,),
                                'li'         => array('align' => true, 'value' => true,),
                                'main'       => array('align' => true,),
                                'map'        => array('name' => true,),
                                'mark'       => array(),
                                'menu'       => array('type' => true,),
                                'nav'        => array('align' => true,),
                                'object'     => array('data' => array('required' => true, 'value_callback' => '_wp_kses_allow_pdf_objects',), 'type' => array('required' => true, 'values' => array('application/pdf'),),),
                                'p'          => array('align' => true,),
                                'pre'        => array('width' => true,),
                                'q'          => array('cite' => true,),
                                'rb'         => array(),
                                'rp'         => array(),
                                'rt'         => array(),
                                'rtc'        => array(),
                                'ruby'       => array(),
                                's'          => array(),
                                'samp'       => array(),
                                'span'       => array('align' => true,),
                                'section'    => array('align' => true,),
                                'small'      => array(),
                                'strike'     => array(),
                                'strong'     => array(),
                                'sub'        => array(),
                                'summary'    => array('align' => true,),
                                'sup'        => array(),
                                'table'      => array('align' => true, 'bgcolor' => true, 'border' => true, 'cellpadding' => true, 'cellspacing' => true, 'rules' => true, 'summary' => true, 'width' => true,),
                                'tbody'      => array('align'   => true, 'char'    => true, 'charoff' => true, 'valign'  => true,),
                                'td'         => array('abbr' => true, 'align' => true, 'axis' => true, 'bgcolor' => true, 'char' => true, 'charoff' => true, 'colspan' => true, 'headers' => true, 'height'  => true, 'nowrap'  => true, 'rowspan' => true, 'scope'   => true, 'valign'  => true, 'width'   => true,),
                                'textarea'   => array('cols'     => true, 'rows'     => true, 'disabled' => true, 'name'     => true, 'readonly' => true,),
                                'tfoot'      => array('align'   => true, 'char'    => true, 'charoff' => true, 'valign'  => true,),
                                'th'         => array('abbr'    => true, 'align'   => true, 'axis'    => true, 'bgcolor' => true, 'char'    => true, 'charoff' => true, 'colspan' => true, 'headers' => true, 'height'  => true, 'nowrap'  => true, 'rowspan' => true, 'scope'   => true, 'valign'  => true, 'width'   => true,),
                                'thead'      => array('align'   => true, 'char'    => true, 'charoff' => true, 'valign'  => true,),
                                'title'      => array(),
                                'tr'         => array('align'   => true, 'bgcolor' => true, 'char'    => true, 'charoff' => true, 'valign'  => true,),
                                'track'      => array('default' => true, 'kind'    => true, 'label'   => true, 'src'     => true, 'srclang' => true,),
                                'tt'         => array(),
                                'u'          => array(),
                                'ul'         => array('type' => true,),
                                'ol'         => array('start'    => true, 'type'     => true, 'reversed' => true,),
                                'var'        => array(),
                                'video'      => array(
                                    'autoplay'    => true, 'controls'    => true, 'height'      => true, 'loop'        => true, 'muted'       => true, 'playsinline' => true, 'poster'      => true, 'preload'     => true, 'src'         => true, 'width'       => true,
                                )
                            );

                            $pages_content = wp_kses($_POST['bpg-page-content'][$key], $allowedposttags);

                            $excerpt_content = (!empty($_POST['bpg_page_excerpt']) ? array_map('sanitize_text_field', $_POST['bpg_page_excerpt']) : '');

                            $image_src = (!empty($_POST['bpg_media_url']) ? array_map('sanitize_url', $_POST['bpg_media_url']) : '');

                            $my_post = array(
                                'post_title'     => $prefix_word . ' ' . $value . ' ' . $postfix_word,
                                'post_content'   => $pages_content,
                                'post_excerpt'   => $excerpt_content[$key],
                                'post_type'      => $type,
                                'post_status'    => $page_status,
                                'page_template'  => $template_name,
                                'comment_status' => $comment_status,
                                'post_author'    => $authors,
                                'post_parent'    => $parent_page_id,
                            );

                            $last_insert_id = wp_insert_post($my_post);
                            if ($last_insert_id) {
                                if (!empty($image_src[$key])) {
                                    bpg_featured_img($image_src[$key], $last_insert_id);
                                }
                            } else {
                                $error = 0;
                            }
                        }
                        if ($error == 0) { ?>
                            <div class="bpg_success_msg_box">
                                <p class="bpg_success_msg bpg_error_msg"><?php esc_html_e('Something went wrong.', 'bulk-page-generator'); ?></p>
                                <button class="bpg_remove_success_msg" title="Close"></button>
                            </div>
                        <?php
                        } else { ?>
                            <div class="bpg_success_msg_box">
                                <p class="bpg_success_msg"><?php esc_html_e('Page/Post has been created.', 'bulk-page-generator'); ?></p>
                                <button class="bpg_remove_success_msg" title="Close"></button>
                            </div>
                        <?php
                        }

                    } else { ?>
                        <div class="bpg_success_msg_box">
                            <p class="bpg_success_msg bpg_error_msg"><?php esc_html_e('Invalid post type.', 'bulk-page-generator'); ?></p>
                            <button class="bpg_remove_success_msg" title="Close"></button>
                        </div>
                        <?php
                    }
                }
            }
            ?>
            <div class="bpg_wrap">
                <div class="bpg-section">
                    <form id="bpg_same_post" method="post" class="bpg-main-table">
                        <?php $nonce = wp_create_nonce('bulk_page_generator_nonce'); ?>
                        <input type="hidden" name="bulk_page_generator_nonce" id="bul_page_generator" value="<?php esc_attr_e($nonce); ?>" />
                        <h2><?php esc_html_e('Multiple Pages/Posts/Products With Content (Different Content) ', 'bulk-page-generator'); ?></h2>

                        <table class="bpg_form-table">
                            <tr class="bpg_page_prefix_tr">
                                <th class="bpg_titledesc"><?php esc_html_e('Prefix of pages/posts/products', 'bulk-page-generator'); ?></th>
                                <td><input type="text" class="bpg_text" value="" id="bpg_page_prefix" name="bpg_page_prefix">
                                </td>
                            </tr>
                            <tr class="bpg_page_post_tr">
                                <th class="bpg_titledesc"><?php esc_html_e('Postfix of pages/posts/products', 'bulk-page-generator'); ?></th>
                                <td><input type="text" class="bpg_text" value="" id="bpg_page_postfix" name="bpg_page_postfix">
                                </td>
                            </tr>
                            <tr class="bpg_page_post_type">
                                <th class="bpg_titledesc"><?php esc_html_e('Type', 'bulk-page-generator'); ?></th>
                                <td>
                                    <div class="bpg_dropdown">
                                        <select name="bpg_type" id="bpg_type" class="bpg_type bpg_post_type_validation">
                                            <option value=""><?php esc_html_e('Select Type', 'bulk-page-generator'); ?></option>
                                            <?php
                                            unset($post_types['attachment']);
                                            foreach ($post_types  as $post_type) { ?>
                                                <option value="<?php esc_attr_e($post_type, 'bulk-page-generator'); ?>"><?php esc_attr_e(ucfirst($post_type), 'bulk-page-generator'); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bpg_page_post_status">
                                <th class="bpg_titledesc"><?php esc_html_e('Status', 'bulk-page-generator'); ?></th>
                                <td>
                                    <div class="bpg_dropdown">
                                        <select name="bpg_status" id="bpg_status" class="bpg_status bpg_post_status_validation">
                                            <option value=""><?php esc_html_e('Select Status', 'bulk-page-generator'); ?></option>
                                            <?php $post_status = get_post_statuses();
                                            foreach ($post_status  as $key => $value) { ?>
                                                <option value="<?php esc_attr_e($key, 'bulk-page-generator'); ?>"><?php esc_attr_e(ucfirst($value), 'bulk-page-generator'); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr class="bpg_page_post_comment">
                                <th class="bpg_titledesc"><?php esc_html_e('Comment Status', 'bulk-page-generator'); ?></th>
                                <td>
                                    <div class="bpg_dropdown">
                                        <select name="bpg_comment" id="bpg_comment" class="bpg_comment">
                                            <option value=""><?php esc_html_e('Select Comment Status', 'bulk-page-generator'); ?></option>
                                            <option value="open"><?php esc_html_e('Open', 'bulk-page-generator'); ?></option>
                                            <option value="closed"><?php esc_html_e('Close', 'bulk-page-generator'); ?></option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bpg_page_post_author">
                                <th class="bpg_titledesc"><?php esc_html_e('Author', 'bulk-page-generator'); ?></th>
                                <td>
                                    <div class="bpg_dropdown">
                                        <select name="bpg_author" id="bpg_author" class="bpg_author">
                                            <option value="<?php esc_attr_e(get_current_user_id(), 'bulk-page-generator');  ?>"><?php esc_html_e('Select Author', 'bulk-page-generator'); ?></option>
                                            <?php $users = get_users();
                                            foreach ($users  as $user) { ?>
                                                <option value="<?php esc_attr_e($user->ID, 'bulk-page-generator'); ?>"><?php esc_attr_e($user->display_name, 'bulk-page-generator'); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr class="bpg_page_post_parent_page"  style="display:none;">
                                <th class="bpg_titledesc"><?php esc_html_e('Parent Page', 'bulk-page-generator'); ?></th>
                                <td>
                                    <div class="bpg_dropdown">
                                        <?php
                                        wp_dropdown_pages(array(
                                            'name' => 'bpg_parent_id',
                                            'show_option_none' => '(no parent)',
                                            'option_none_value' => '0',
                                            'sort_column' => 'menu_order, post_title',
                                            'echo' => 1,
                                            'hierarchical' => 1
                                        ));
                                        ?>
                                    </div>
                                    <p class="bpg_note"><?php esc_html_e('Applies to ', 'bulk-page-generator'); ?> <b><?php esc_html_e('Pages', 'bulk-page-generator'); ?></b> <?php esc_html_e('only', 'bulk-page-generator'); ?></p>
                                </td>
                            </tr>
                            <tr class="bpg_page_post_template" style="display:none;">
                                <th class="bpg_titledesc"><?php esc_html_e('Page Template', 'bulk-page-generator'); ?></th>
                                <td>
                                    <div class="bpg_dropdown">
                                        <select name="bpg_template" id="bpg_template" class="bpg_template">
                                            <option value="default"><?php esc_html_e('Select Template', 'bulk-page-generator'); ?></option>
                                            <?php $templates = get_page_templates();
                                            foreach ($templates  as $key => $page_template) { ?>
                                                <option value="<?php esc_attr_e($page_template, 'bulk-page-generator'); ?>"><?php esc_attr_e($key, 'bulk-page-generator'); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <p class="bpg_note"><?php esc_html_e('Applies to ', 'bulk-page-generator'); ?> <b><?php esc_html_e('Pages', 'bulk-page-generator'); ?></b> <?php esc_html_e('only', 'bulk-page-generator'); ?></p>
                                </td>
                            </tr>
                        </table>
                        <div class="bpg_all_pages">
                            <div class="bpg_page_data">
                                <table class="bpg_form-table bpg_sub_table">
                                    <tbody>
                                        <tr class="bpg_page_post_titles">
                                            <th class="bpg_titledesc"><?php esc_html_e('Title', 'bulk-page-generator'); ?></th>
                                            <td>
                                                <input type="text" name="bpg-page-title[]" class="bpg_post_list_validation" value="" required1 />
                                            </td>
                                        </tr>
                                        <tr class="bpg_pages_content">
                                            <th class="bpg_titledesc"><?php esc_html_e('Content', 'bulk-page-generator'); ?></th>
                                            <td>
                                                <textarea name="bpg-page-content[]" id="bpg_content" rows="5" style="width:700px;" class="wp-editor"></textarea>
                                            </td>
                                        </tr>
                                        <tr class="bpg_page_post_excerpt" style="display:none;">
                                            <th class="bpg_titledesc"><?php esc_html_e('Excerpt Content', 'bulk-page-generator'); ?></th>
                                            <td>
                                                <textarea name="bpg_page_excerpt[]" id="bpg_page_excerpt" cols="60" rows="5"></textarea>
                                                <p class="bpg_note"><?php esc_html_e('Applies to ', 'bulk-page-generator'); ?> <b><?php esc_html_e('Post', 'bulk-page-generator'); ?></b> <?php esc_html_e('only', 'bulk-page-generator'); ?></p>
                                            </td>
                                        </tr>
                                        <tr class="bpg_page_post_img">
                                            <th class="bpg_titledesc"><?php esc_html_e('Featured Image', 'bulk-page-generator'); ?></th>
                                            <td>
                                                <img src="" alt="" srcset="" class="bpg-media-url" style="display:none;">
                                                <input type="hidden" name="bpg_media_url[]" class="bpg_media_hidden_url">
                                                <input type="button" class="bpg-remove-image-button" value="Remove Image" style="display:none;">
                                                <input id="" type="button" class="button bpg-upload-button" value="Upload Image" />
                                            </td>
                                        </tr>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>
                                                <button type="button" class="bpg_create_page bpg_plus_btn">+</button>
                                            </td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                        <input name="submit_diffrent_content" class="submit-btn" type="submit" value="<?php esc_attr_e('Save'); ?>" />
                    </form>
                </div>
            </div>
            <?php
        }
    }
}
