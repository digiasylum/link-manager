<?php
// Display Link Editor page per post
function lm_link_editor_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'link-manager'));
    }

    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0; 

    if (!$post_id || get_post_status($post_id) === false) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Invalid Post ID.', 'link-manager') . '</p></div>';
        return;
    }

    $message = '';
    $message_type = ''; 


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'lm_reset_attributes') {
        // Reset logic remains the same
    }
    
    if (isset($_GET['message'])) {
        // Message logic remains the same
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lm_save_attributes_submit'])) {
        // Save logic remains the same
    }

    if ($message) {
        $alert_class = ($message_type === 'success') ? 'alert-success' : 'alert-danger';
        echo '<div class="alert ' . esc_attr($alert_class) . ' is-dismissible"><p>' . $message . '</p></div>';
    }

    $post = get_post($post_id); 
    if (!$post) { 
        echo '<div class="alert alert-danger"><p>' . esc_html__('Post not found.', 'link-manager') . '</p></div>';
        return;
    }

    $links = lm_get_links_from_post($post_id); 
    ?>
    <div class="wrap link-manager-wrap container-fluid">
        <p><a href="<?php echo esc_url(admin_url('admin.php?page=link-manager-overview')); ?>" class="button">&laquo; <?php esc_html_e('Back to Overview', 'link-manager'); ?></a></p>
        <h1><?php echo sprintf(esc_html__('Edit Links for: %s', 'link-manager'), esc_html(get_the_title($post_id))); ?></h1>

        <form method="post" id="link-attributes-form">
            <?php wp_nonce_field('lm_save_link_attributes_' . $post_id); ?>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped lm-link-editor-table">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 25%;"><?php esc_html_e('Link Text / Title', 'link-manager'); ?></th>
                            <th style="width: 30%;"><?php esc_html_e('URL', 'link-manager'); ?></th>
                            <th class="text-center"><?php esc_html_e('Nofollow', 'link-manager'); ?></th>
                            <th class="text-center"><?php esc_html_e('Sponsored', 'link-manager'); ?></th>
                            <th class="text-center"><?php esc_html_e('UGC', 'link-manager'); ?></th>
                            <th class="text-center"><?php esc_html_e('Noopener', 'link-manager'); ?></th>
                            <th class="text-center"><?php esc_html_e('Noreferrer', 'link-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (['internal', 'external'] as $link_type): ?>
                            <tr class="link-type-header-row"><td colspan="7" class="link-type-header"><?php echo ucfirst($link_type) . ' ' . esc_html__('Links', 'link-manager'); ?></td></tr>
                            <?php
                            if (!empty($links[$link_type])):
                                foreach ($links[$link_type] as $link_idx => $link):
                                    $attrs = $link['attributes'];
                                    $form_link_url_key = esc_attr($link['url']);
                                    $is_internal = ($link_type === 'internal');
                                    $unique_id_base = 'toggle-' . $link_idx . '-' . md5($form_link_url_key);
                            ?>
                            <tr>
                                <td data-colname="<?php esc_attr_e('Link Text', 'link-manager'); ?>"><?php echo esc_html($link['text']); ?></td>
                                <td data-colname="<?php esc_attr_e('URL', 'link-manager'); ?>"><code title="<?php echo esc_attr($link['url']); ?>"><?php echo esc_url($link['url']); ?></code></td>
                                
                                <?php
                                $rel_attributes = ['nofollow', 'sponsored', 'ugc', 'noopener', 'noreferrer'];
                                foreach ($rel_attributes as $rel_attr) {
                                    $id = $unique_id_base . '-' . $rel_attr;
                                    echo '<td class="text-center" data-colname="' . esc_attr(ucfirst($rel_attr)) . '">';
                                    echo '<div class="custom-control custom-switch">';
                                    echo '<input type="checkbox" class="custom-control-input" id="' . esc_attr($id) . '" name="lm_links[' . $form_link_url_key . '][' . $rel_attr . ']" ' . checked(!empty($attrs[$rel_attr]), true, false) . ' ' . disabled($is_internal, true, false) . '>';
                                    echo '<label class="custom-control-label" for="' . esc_attr($id) . '"><span class="sr-only">' . esc_html(ucfirst($rel_attr)) . '</span></label>';
                                    echo '</div>';
                                    echo '</td>';
                                }
                                ?>
                                </tr>
                            <?php
                                endforeach;
                            else:
                            ?>
                            <tr>
                                <td colspan="7"><?php esc_html_e('No links found.', 'link-manager'); ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="mt-3">
                <input type="submit" name="lm_save_attributes_submit" class="button button-primary" value="<?php esc_attr_e('Save Attributes for External Links', 'link-manager'); ?>">
                <span class="description"><?php esc_html_e('Note: Attributes can only be set for external links via this editor.', 'link-manager'); ?></span>
            </p>
        </form>
        <hr>
        <form method="post" id="link-reset-form" onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to delete all specific link settings for this post? It will revert to using the global settings. This cannot be undone.', 'link-manager'); ?>');">
            <?php wp_nonce_field('lm_reset_link_attributes_' . $post_id); ?>
            <input type="hidden" name="action" value="lm_reset_attributes">
            <p><strong><?php esc_html_e('Reset to Defaults', 'link-manager'); ?></strong></p>
            <p class="description"><?php esc_html_e('If this post has specific link settings, you can clear them here. The post will then use your site-wide global settings.', 'link-manager'); ?></p>
            <p>
                <input type="submit" name="lm_reset_attributes_submit" class="button button-secondary" value="<?php esc_attr_e('Reset Link Attributes for This Post', 'link-manager'); ?>">
            </p>
        </form>
    </div>
    <?php
}
?>