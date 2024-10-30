<?php
if (!class_exists('bpg_same_content_settings')) {
    class bpg_same_content_settings
    {
        public function __construct(){
            add_action('admin_init', array($this, 'bpg_same_content_settings_init'));
        }
        function bpg_same_content_settings_init(){

            $post_types = get_post_types(array('public' => true));
            if (isset($_POST['submit_same_content'])) {
                if (wp_verify_nonce($_POST['bulk_page_generator_nonce'], 'bulk_page_generator_nonce')) {
                    $prefix_word = filter_input(INPUT_POST, 'bpg_page_prefix', FILTER_SANITIZE_SPECIAL_CHARS);
                    $prefix_word = sanitize_text_field(wp_unslash($prefix_word));

                    $postfix_word = filter_input(INPUT_POST, 'bpg_page_postfix', FILTER_SANITIZE_SPECIAL_CHARS);
                    $postfix_word = sanitize_text_field(wp_unslash($postfix_word));

                    $pages_list = filter_input(INPUT_POST, 'bpg_page_titles', FILTER_SANITIZE_SPECIAL_CHARS);
                    $pages_list = sanitize_textarea_field($pages_list);
                    $page_list = explode("|", $pages_list);

                    $pages_content = filter_input(INPUT_POST, 'bpg_pages_content', FILTER_SANITIZE_SPECIAL_CHARS);
                    $pages_content = htmlspecialchars_decode($pages_content);

                    $excerpt_content = filter_input(INPUT_POST, 'bpg_page_excerpt', FILTER_SANITIZE_SPECIAL_CHARS);
                    $excerpt_content = sanitize_textarea_field($excerpt_content);

                    $type = filter_input(INPUT_POST, 'bpg_type', FILTER_SANITIZE_SPECIAL_CHARS);
                    $type = sanitize_text_field(wp_unslash($type));

                    $page_status = filter_input(INPUT_POST, 'bpg_status', FILTER_SANITIZE_SPECIAL_CHARS);
                    $page_status = sanitize_text_field(wp_unslash($page_status));

                   
                    

                    $image_src = filter_var(sanitize_url($_POST['bpg_media_url']), FILTER_VALIDATE_URL);

                    $template_name = filter_input(INPUT_POST, 'bpg_template', FILTER_SANITIZE_SPECIAL_CHARS);
                    $template_name = sanitize_text_field(wp_unslash($template_name));

                    $comment_status = filter_input(INPUT_POST, 'bpg_comment', FILTER_SANITIZE_SPECIAL_CHARS);
                    $comment_status = ((empty($comment_status) ? get_default_comment_status($type) : $comment_status));
                    $comment_status = sanitize_text_field(wp_unslash($comment_status));

                    $authors = filter_input(INPUT_POST, 'bpg_author', FILTER_SANITIZE_SPECIAL_CHARS);
                    $authors = sanitize_text_field(wp_unslash($authors));

                    $bpg_parent_id = filter_input(INPUT_POST, 'bpg_parent_id', FILTER_SANITIZE_SPECIAL_CHARS);
                    $bpg_parent_id = sanitize_text_field(wp_unslash($bpg_parent_id));

                    if (array_key_exists($type, $post_types)) {
                        $error = 1;

                        if (isset($page_list) && !empty($page_list)) {
                            foreach ($page_list as $page) {
                                $my_post = array(
                                    'post_title'        => $prefix_word . ' ' . $page . ' ' . $postfix_word,
                                    'post_content'      => $pages_content,
                                    'post_excerpt'      => $excerpt_content,
                                    'post_type'         => $type,
                                    'post_status'       => $page_status,
                                    'page_template'     => $template_name,
                                    'comment_status'    => $comment_status,
                                    'post_author'       => $authors,
                                    'post_parent'       => $bpg_parent_id
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
                        } else {
                            $error = 0;
                        }
                        if ($error == 0) {
                            ?>
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

            <div class="bpg-section">
                <form id="bpg_same_post" method="post" class="bpg-main-table">
                    <?php $nonce = wp_create_nonce('bulk_page_generator_nonce'); ?>
                    <input type="hidden" name="bulk_page_generator_nonce" id="bul_page_generator" value="<?php esc_attr_e($nonce); ?>" />
                    <h2><?php esc_html_e('Multiple Pages/Posts/Products With Content (Same Content) ', 'bulk-page-generator'); ?></h2>

                    <table class="bpg_form-table">
                        <tr class="bpg_page_prefix_tr">
                            <th class="bpg_titledesc"><?php esc_html_e('Prefix pages/posts/products', 'bulk-page-generator'); ?></th>
                            <td><input type="text" class="bpg_text" value="" id="bpg_page_prefix" name="bpg_page_prefix">
                            </td>
                        </tr>
                        <tr class="bpg_page_post_tr">
                            <th class="bpg_titledesc"><?php esc_html_e('Postfix pages/posts/products', 'bulk-page-generator'); ?></th>
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
                        <tr class="bpg_page_post_titles">
                            <th class="bpg_titledesc"><?php esc_html_e('List of pages/posts/products (Pipe Separated)', 'bulk-page-generator'); ?>
                                <span>( | )</span>
                            </th>
                            <td>
                                <textarea name="bpg_page_titles" id="bpg_page_titles" class="bpg_post_list_validation"></textarea>
                                <p class="bpg_note"><?php esc_html_e('Use "|" delimiter for separate pages/posts/products.', 'bulk-page-generator'); ?></p>
                                <p class="bpg_note"><?php esc_html_e('Ex. Test1 | Test2 | Test3 will create three pages/posts/products.', 'bulk-page-generator'); ?></p>
                            </td>
                        </tr>
                        <tr class="bpg_pages_content">
                            <th class="bpg_titledesc"><?php esc_html_e('Content of pages/posts/products', 'bulk-page-generator'); ?></th>
                            <td>

                                <textarea name="bpg_pages_content" id="bpg_content" rows="5" style="width:700px;" class="wp-editor"></textarea>
                            </td>
                        </tr>
                        <tr class="bpg_page_post_excerpt" style="display:none;">
                            <th class="bpg_titledesc"><?php esc_html_e('Excerpt Content', 'bulk-page-generator'); ?></th>
                            <td>
                                <textarea name="bpg_page_excerpt" id="bpg_page_excerpt" cols="60" rows="5"></textarea>
                                <p class="bpg_note"><?php esc_html_e('Applies to ', 'bulk-page-generator'); ?> <b><?php esc_html_e('Post', 'bulk-page-generator'); ?></b> <?php esc_html_e('only', 'bulk-page-generator'); ?></p>
                            </td>
                        </tr>
                        <tr class="bpg_page_post_img">
                            <th class="bpg_titledesc"><?php esc_html_e('Featured Image', 'bulk-page-generator'); ?></th>
                            <td>
                                <img src="" alt="Featured Image" class="bpg-media-url" name="" style="display:none;">
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
                    <input type="submit" class="submit-btn" value="Save" name="submit_same_content">
                </form>
            </div>
            <?php
        }
    }
}
