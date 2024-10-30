<?php
if (!class_exists('bpg_dynamic_title_settings')) {
    class bpg_dynamic_title_settings
    {
        public function __construct(){
            add_action('admin_init', array($this, 'bpg_dynamic_title_settings_init'));
        }
        function bpg_dynamic_title_settings_init(){
            $post_types = get_post_types(array('public' => true));
            if (isset($_POST['submit_dynamic_title'])) {
                if (wp_verify_nonce($_POST['bulk_page_generator_nonce'], 'bulk_page_generator_nonce')) {
                    $keyword = filter_input(INPUT_POST, 'bpg_key', FILTER_SANITIZE_SPECIAL_CHARS);
                    $keyword = sanitize_text_field(wp_unslash($keyword));

                    $keyword_data = array_map('sanitize_text_field', $_POST['bpg_page_keyword_data']);

                    $title = filter_input(INPUT_POST, 'bpg_page_titles', FILTER_SANITIZE_SPECIAL_CHARS);
                    $title = sanitize_text_field(wp_unslash($title));

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

                    $image_src = filter_var(sanitize_url($_POST['bpg_media_url']), FILTER_VALIDATE_URL);
                    
                    if (array_key_exists($type, $post_types)) {
                        $error = 1;

                        if (isset($keyword_data) && !empty($keyword_data)) {
                            foreach ($keyword_data as $key => $value) {
                                $post_title = str_replace($keyword, $value, $title);

                                $pages_content = filter_input(INPUT_POST, 'bpg_pages_content', FILTER_SANITIZE_SPECIAL_CHARS);
                                $pages_content = htmlspecialchars_decode($pages_content);
                                $pages_content = str_replace($keyword, $value, $pages_content);

                                $excerpt_content = filter_input(INPUT_POST, 'bpg_page_excerpt', FILTER_SANITIZE_SPECIAL_CHARS);
                                $excerpt_content = sanitize_textarea_field($excerpt_content);
                                $excerpt_content = str_replace($keyword, $value, $excerpt_content);


                                $my_post = array(
                                    'post_title'     => $post_title,
                                    'post_content'   => $pages_content,
                                    'post_excerpt'   => $excerpt_content,
                                    'post_type'      => $type,
                                    'post_status'    => $page_status,
                                    'page_template'  => $template_name,
                                    'comment_status' => $comment_status,
                                    'post_author'    => $authors,
                                    'post_parent'    => $parent_page_id,
                                );
                                $last_insert_id = wp_insert_post($my_post);
                                if ($last_insert_id) {
                                    if (!empty($image_src)) {
                                        bpg_featured_img($image_src, $last_insert_id);
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
                                <p class="bpg_success_msg bpg_error_msg"><?php esc_html_e('Please enter minimum 1 keyword.', 'bulk-page-generator'); ?></p>
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
            <div class="bpg-section">
                <form id="bpg_dynamic_post" method="post" class="bpg-main-table">
                    <?php $nonce = wp_create_nonce('bulk_page_generator_nonce'); ?>
                    <input type="hidden" name="bulk_page_generator_nonce" id="bul_page_generator" value="<?php esc_attr_e($nonce); ?>" />
                    <h2><?php esc_html_e('Multiple Pages/Posts/Products With Dynamic Title ', 'bulk-page-generator'); ?></h2>

                    <table class="bpg_form-table">
                        <tr class="bpg_page_keyword_tr bpg_shortcode_input_wp">
                            <th class="bpg_titledesc"><?php esc_html_e('Variable', 'bulk-page-generator'); ?></th>
                            <td><input type="text" class="bpg_text" value="" id="bpg_page_keyword" name="bpg_page_keyword">

                                <input type="text" onfocus="this.select();" readonly="readonly" value="" name="bpg_key" id="bpg_shortcode_copy" class="code bpg_key">
                                <div class="bpg_shortcode_tooltip">
                                    <svg id="bpg_copy_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 699.428 699.428" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                        <path d="M502.714,0c-2.71,0-262.286,0-262.286,0C194.178,0,153,42.425,153,87.429l-25.267,0.59     c-46.228,0-84.019,41.834-84.019,86.838V612c0,45.004,41.179,87.428,87.429,87.428H459c46.249,0,87.428-42.424,87.428-87.428     h21.857c46.25,0,87.429-42.424,87.429-87.428v-349.19L502.714,0z M459,655.715H131.143c-22.95,0-43.714-21.441-43.714-43.715     V174.857c0-22.272,18.688-42.993,41.638-42.993L153,131.143v393.429C153,569.576,194.178,612,240.428,612h262.286     C502.714,634.273,481.949,655.715,459,655.715z M612,524.572c0,22.271-20.765,43.713-43.715,43.713H240.428     c-22.95,0-43.714-21.441-43.714-43.713V87.429c0-22.272,20.764-43.714,43.714-43.714H459c-0.351,50.337,0,87.975,0,87.975     c0,45.419,40.872,86.882,87.428,86.882c0,0,23.213,0,65.572,0V524.572z M546.428,174.857c-23.277,0-43.714-42.293-43.714-64.981     c0,0,0-22.994,0-65.484v-0.044L612,174.857H546.428z M502.714,306.394H306c-12.065,0-21.857,9.77-21.857,21.835     c0,12.065,9.792,21.835,21.857,21.835h196.714c12.065,0,21.857-9.771,21.857-21.835     C524.571,316.164,514.779,306.394,502.714,306.394z M502.714,415.57H306c-12.065,0-21.857,9.77-21.857,21.834     c0,12.066,9.792,21.836,21.857,21.836h196.714c12.065,0,21.857-9.77,21.857-21.836C524.571,425.34,514.779,415.57,502.714,415.57     z" fill="#1d6e69" data-original="#000000" class=""></path>
                                    </svg>
                                    <span class="bpg_tooltiptext" id="bpg_shortcodeTooltip"><?php _e('Copy to clipboard') ?></span>
                                </div>
                                <p class="bpg_note"><?php esc_html_e('This variable is used to generating title and content dynamically (Copy this variable and use it in title,content,excerpt).', 'bulk-page-generator'); ?></p>
                                <p class="bpg_note"><?php esc_html_e('Ex. "{variable} title text".', 'bulk-page-generator'); ?></p>
                            </td>
                        </tr>
                        <tr class="bpg_page_keyword_data_tr">
                            <th class="bpg_titledesc"><?php esc_html_e('Variable Data', 'bulk-page-generator'); ?></th>
                            <td class="bpg_btns"><input type="text" class="bpg_text" value="" name="bpg_page_keyword_data[]">
                                <div class="bpg_add_key">
                                    <button type="button" class="bpg_create_keyword_data bpg_plus_btn">+</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                            <td>
                                <p class="bpg_note"><?php esc_html_e('Add Word(s) or phrase(s) here, that will be included into title or content dynamically using the above variable. ', 'bulk-page-generator'); ?></p>
                            </td>
                            </th>
                        </tr>

                        <tr class="bpg_page_post_titles">
                            <th class="bpg_titledesc"><?php esc_html_e('Title', 'bulk-page-generator'); ?></th>
                            <td>
                                <input type="text" name="bpg_page_titles" id="bpg_page_titles"></input>
                            </td>
                        </tr>
                        <tr class="bpg_pages_content">
                            <th class="bpg_titledesc"><?php esc_html_e('Content of pages/posts/products', 'bulk-page-generator'); ?></th>
                            <td>
                                <textarea name="bpg_pages_content" id="bpg_content" rows="5" style="width:700px;" class="wp-editor"></textarea>
                            </td>
                        </tr>
                        <tr class="bpg_page_post_excerpt" style="display:none">
                            <th class="bpg_titledesc"><?php esc_html_e('Excerpt Content', 'bulk-page-generator'); ?></th>
                            <td>
                                <textarea name="bpg_page_excerpt" id="bpg_page_excerpt" cols="60" rows="5"></textarea>
                                <p class="bpg_note"><?php esc_html_e('Applies to ', 'bulk-page-generator'); ?> <b><?php esc_html_e('Post', 'bulk-page-generator'); ?></b> <?php esc_html_e('only', 'bulk-page-generator'); ?></p>
                            </td>
                        </tr>
                        <tr class="bpg_page_post_type">
                            <th class="bpg_titledesc"><?php esc_html_e('Type', 'bulk-page-generator'); ?></th>
                            <td>
                                <div class="bpg_dropdown">
                                    <select name="bpg_type" id="bpg_type" class="bpg_type">
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
                                    <select name="bpg_status" id="bpg_status" class="bpg_status">
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
                        <tr class="bpg_page_post_img">
                            <th class="bpg_titledesc"><?php esc_html_e('Featured Image', 'bulk-page-generator'); ?></th>
                            <td>
                                <img src="" alt="" srcset="" class="bpg-media-url" name="bpg_media_url" style="display:none;">
                                <input type="hidden" name="bpg_media_url" class="bpg_media_hidden_url">
                                <input type="button" class="bpg-remove-image-button" value="Remove Image" style="display:none;">
                                <input id="" type="button" class="button bpg-upload-button" value="Upload Image" />
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
                        <tr class="bpg_page_post_template" style="display:none">
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
                    <input type="submit" value="Save" class="submit-btn" name="submit_dynamic_title">
                </form>
            </div>
            <?php
        }
    }
}
