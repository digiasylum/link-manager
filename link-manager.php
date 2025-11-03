<?php
/*
Plugin Name: Link Manager
Plugin URI: https://www.digiasylum.com
Contributors: Digiasylum
Description: Manage `rel` attributes (nofollow, sponsored, ugc, noopener, noreferrer) for external links.
Version: 1.6.2
Author: Umesh Kumar Sahai
Author URI: https://linkedin.com/in/umeshkumarsahai
License: GPLv2 or later
Text Domain: link-manager
*/
defined('ABSPATH') or die('No script kiddies please!');

// Define plugin paths
define('LINK_MANAGER_DIR', plugin_dir_path(__FILE__));
define('LINK_MANAGER_URL', plugin_dir_url(__FILE__));

// Include files
require_once LINK_MANAGER_DIR . 'includes/functions.php';
require_once LINK_MANAGER_DIR . 'includes/link-overview.php';
require_once LINK_MANAGER_DIR . 'includes/link-editor.php';
require_once LINK_MANAGER_DIR . 'includes/admin-settings.php';
require_once LINK_MANAGER_DIR . 'includes/content-filter.php';
require_once LINK_MANAGER_DIR . 'includes/dashboard-widget.php';

// Enqueue admin styles and scripts
function lm_enqueue_admin_assets($hook) {
    $is_link_manager_page = false;
    $current_screen = get_current_screen();

    if (isset($_GET['page'])) {
        $current_page_slug = sanitize_key($_GET['page']);
        if ($current_page_slug === 'link-manager-overview' || 
            $current_page_slug === 'link-editor' || 
            $current_page_slug === 'link-manager-settings') {
            $is_link_manager_page = true;
        }
    }
    
    if ($current_screen) {
        if (strpos($current_screen->id, 'link-manager') !== false || $current_screen->id === 'admin_page_link-editor') {
            $is_link_manager_page = true;
        }
        if ($current_screen->id === 'dashboard') { 
             $is_link_manager_page = true;
        }
    }

    if (!$is_link_manager_page) {
        return;
    }

    // Enqueue Bootstrap CSS on relevant pages
    if (isset($_GET['page']) && ($_GET['page'] === 'link-editor' || $_GET['page'] === 'link-manager-overview')) {
        wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2');
        wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array('jquery'), '1.16.1', true);
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery', 'popper-js'), '4.5.2', true);
    }

    // --- CACHE BUSTING: Version changed to 1.6.2 ---
    wp_enqueue_style('link-manager-admin-style', LINK_MANAGER_URL . 'assets/css/admin.css', array(), '1.6.2');
    wp_enqueue_script('link-manager-admin-js', LINK_MANAGER_URL . 'assets/js/admin.js', array('jquery'), '1.4.2', true);
    
    wp_localize_script('link-manager-admin-js', 'lmAdmin', array(
        'confirmSave' => __('Are you sure you want to save these link attributes for external links?', 'link-manager'),
    ));
}
add_action('admin_enqueue_scripts', 'lm_enqueue_admin_assets');

// Add admin menu pages
function lm_add_admin_menu() {
    $capability = 'manage_options';
    add_menu_page(
        __('Link Manager', 'link-manager'),
        __('Link Manager', 'link-manager'),
        $capability,
        'link-manager-overview',
        'lm_link_overview_page',
        'dashicons-admin-links',
        80
    );
    add_submenu_page(
        null, 
        __('Link Editor', 'link-manager'),
        __('Link Editor', 'link-manager'),
        $capability,
        'link-editor', 
        'lm_link_editor_page'
    );
    add_submenu_page(
        'link-manager-overview',
        __('Settings', 'link-manager'),
        __('Settings', 'link-manager'),
        $capability,
        'link-manager-settings',
        'lm_admin_settings_page'
    );
}
add_action('admin_menu', 'lm_add_admin_menu');

// Register settings
add_action('admin_init', 'lm_register_settings');

// Add action links to plugin page
function lm_plugin_action_links_callback($links) {
    $manage_links_link = '<a href="' . admin_url('admin.php?page=link-manager-overview') . '">' . __('Manage Links', 'link-manager') . '</a>';
    $settings_link = '<a href="' . admin_url('admin.php?page=link-manager-settings') . '">' . __('Settings', 'link-manager') . '</a>';
    array_unshift($links, $settings_link);
    array_unshift($links, $manage_links_link);
    return $links;
}
$plugin_basename_for_actions = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename_for_actions", 'lm_plugin_action_links_callback');

// Add Dashboard Widget
add_action('wp_dashboard_setup', 'lm_setup_dashboard_widget_action');

// Add "Manage Links" to post/page row actions
function lm_add_list_row_actions($actions, $post) {
    if (current_user_can('manage_options') && in_array($post->post_type, ['post', 'page'])) { 
        $manage_links_url = admin_url('admin.php?page=link-editor&post_id=' . $post->ID);
        $actions['manage_links'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            esc_url($manage_links_url),
            esc_attr(sprintf(__('Manage links for &#8220;%s&#8221;', 'link-manager'), get_the_title($post))),
            esc_html__('Manage Links', 'link-manager')
        );
    }
    return $actions;
}
add_filter('post_row_actions', 'lm_add_list_row_actions', 10, 2);
add_filter('page_row_actions', 'lm_add_list_row_actions', 10, 2);

// Add custom meta links to the plugin row
function lm_custom_plugin_row_meta($links, $file) {
    if (strpos($file, basename(LINK_MANAGER_DIR) . '/link-manager.php') !== false) {
        $new_links = array(
            'visit_site' => '<a href="https://www.digiasylum.com" target="_blank" aria-label="' . esc_attr__('Visit external plugin site', 'link-manager') . '">' . __('Visit plugin site', 'link-manager') . '</a>'
        );
        $links = array_merge($links, $new_links);
    }
    return $links;
}
add_filter('plugin_row_meta', 'lm_custom_plugin_row_meta', 10, 2);

?>