<?php
/*
Plugin Name: Bulk Page Generator
Description: Bulk Pages/Posts Generator is a plugin that provides an easy way through which a user can create multiple pages/posts at a time.
Author: Geek Code Lab
Version: 1.3.1
Author URI: https://geekcodelab.com/
Text Domain : bulk-page-generator 
*/

if (!defined('ABSPATH')) exit;

define('BPG_BUILD', '1.3.1');

if (!defined('BPG_PLUGIN_DIR_PATH'))
    define('BPG_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

if (!defined('BPG_PLUGIN_URL'))
    define('BPG_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));

add_action( 'admin_init', 'bpg_plugin_load' );
function bpg_plugin_load() {
    if(!current_user_can('publish_posts') && !current_user_can('upload_files')) {
        return;
    }
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'bpg_add_plugin_link');
function bpg_add_plugin_link($links){
    $support_link = '<a href="https://geekcodelab.com/contact/" target="_blank" >' . __('Support', 'bulk-page-generator') . '</a>';
    array_unshift($links, $support_link);

    $setting_link = '<a href="' . admin_url('admin.php?page=bulk-page-generator') . '">' . __('Settings', 'bulk-page-generator') . '</a>';
    array_unshift($links, $setting_link);

    return $links;
}

// admin scripts
add_action('admin_enqueue_scripts', 'bpg_plugin_admin_scripts');
function bpg_plugin_admin_scripts( $hook ){
    
    if (is_admin() && $hook == 'toplevel_page_bulk-page-generator') {
        $plugin_url = BPG_PLUGIN_URL . '/assets';
        wp_enqueue_editor();
        wp_enqueue_media();
        wp_enqueue_style('bpg-admin-css', $plugin_url . '/css/style.css', array(), BPG_BUILD);
        wp_enqueue_script('bpg-admin-script', $plugin_url . '/js/admin-script.js', array('jquery'), rand(100, 10000));
        wp_localize_script('bpg-admin-script', 'bpgObj', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}

require_once(BPG_PLUGIN_DIR_PATH . '/options.php');

add_action('wp_ajax_bpg_add_more', 'bpg_add_more_row');
function bpg_add_more_row(){
    $style = '';
    $counter = sanitize_text_field($_POST['counter']);
    $type = sanitize_text_field($_POST['type']);
    if (empty($type)) {
        $style="style=display:none";
    }else{
        if ($type=='page') {
            $style="style=display:none";
        }
    }
    ?>
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

                <textarea name="bpg-page-content[]" id="bpg_content_<?php esc_attr_e($counter) ?>" rows="5" style="width:700px;" class="wp-editor"></textarea>
            </td>
        </tr>
        <tr class="bpg_page_post_excerpt" <?php esc_attr_e($style); ?>>
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
                <input type="button" id="remove-<?php esc_attr_e($counter) ?>" class="bpg-remove-image-button" value="Remove Image" style="display:none;">
                <input id="" type="button" class="button bpg-upload-button" value="Upload Image" />
            </td>
        </tr>
        <tr>
            <th></th>
            <td>
                <button type="button" class="bpg_remove_page bpg_minus_btn">-</button>
            </td>
        </tr>
    </tbody>
    <?php
    die;
}

function bpg_featured_img($image_src, $last_insert_id)
{
    if (!empty($image_src)) {
        $upload_dir       = wp_upload_dir(); // Set upload folder
        $filename         = basename($image_src); // Create image file name
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        $image_data       = file_get_contents($image_src, false, $context); // Get image data

        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        file_put_contents($file, $image_data);
        $wp_filetype = wp_check_filetype($filename, null);

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
        global $wpdb;

        $file_exists_id =  intval($wpdb->get_var("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'"));
        if ($file_exists_id) {
            $attach_id = $file_exists_id;
        } else {
            $attach_id = wp_insert_attachment($attachment, $file, $last_insert_id);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }

        set_post_thumbnail($last_insert_id, $attach_id);
    }
}