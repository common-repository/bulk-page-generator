<?php
include(BPG_PLUGIN_DIR_PATH . '/settings/same-content-setting.php');
include(BPG_PLUGIN_DIR_PATH . '/settings/diffrent-content-setting.php');
include(BPG_PLUGIN_DIR_PATH . '/settings/dynamic-title-setting.php');

$default_tab = null;
$tab = "";
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;

if (!class_exists('bpg_settings')) {
    class bpg_settings
    {
        public function __construct(){
            add_action('admin_menu',  array($this, 'bpg_menu_pages'));
        }
        function bpg_menu_pages(){

            add_menu_page(
                'Page Title',
                'Bulk Page Generator',
                'edit_posts',
                'bulk-page-generator',
                array($this, 'bpg_option_page_callback'),
                'dashicons-admin-page'
            );
        }
        function bpg_option_page_callback(){
            $default_tab = null;
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab; ?>
            <div class="bpg-main-box">

                <div class="bpg-container">
                    <div class="bpg-header">
                        <h1 class="bpgp-h1">
                            <?php _e('Bulk Page Generator', 'bulk-page-generator'); ?></h1>
                    </div>
                    <div class="bpg-option-section">
                        <div class="bpg-tabbing-box">
                            <ul class="bpg-tab-list">
                                <li><a href="?page=bulk-page-generator" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>"><?php _e('Post With Same Content', 'bulk-page-generator'); ?></a>
                                </li>
                                <li><a href="?page=bulk-page-generator&tab=bpg-diffrent-content" class="nav-tab <?php if ($tab === 'bpg-diffrent-content') : ?>nav-tab-active<?php endif; ?>"><?php _e('Post With Different Content', 'bulk-page-generator'); ?></a>
                                </li>
                                <li><a href="?page=bulk-page-generator&tab=bpg-dynamic-title" class="nav-tab <?php if ($tab === 'bpg-dynamic-title') : ?>nav-tab-active<?php endif; ?>"><?php _e('Post With Dynamic Title ', 'bulk-page-generator'); ?></a>
                                </li>
                            </ul>
                        </div>

                        <div class="bpg-tabing-option">
                            <?php
                            if ($tab == null) {
                                $same_content  = new bpg_same_content_settings();
                                $same_content->bpg_same_content_settings_init();
                            }
                            if ($tab == 'bpg-diffrent-content') {
                                $diffrent_content  = new bpg_diffrent_content_settings();
                                $diffrent_content->bpg_diffrent_content_settings_init();
                            }
                            if ($tab == 'bpg-dynamic-title') {
                                $dynamic_title  = new bpg_dynamic_title_settings();
                                $dynamic_title->bpg_dynamic_title_settings_init();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    new bpg_settings();
}