<?php
// Display Post Link Overview page
function lm_link_overview_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'link-manager'));
    }

    $search_term = isset($_GET['s_all']) ? sanitize_text_field(wp_unslash($_GET['s_all'])) : '';
    $current_post_type_filter = isset($_GET['post_type_filter']) ? sanitize_key($_GET['post_type_filter']) : 'all';

    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $per_page = 20;

    $base_args = ['posts_per_page' => $per_page, 'paged' => $paged, 'post_status' => 'publish'];

    if ($current_post_type_filter === 'post') $base_args['post_type'] = 'post';
    elseif ($current_post_type_filter === 'page') $base_args['post_type'] = 'page';
    else $base_args['post_type'] = apply_filters('lm_scannable_post_types', ['post', 'page']); 

    $query_args = $base_args;
    if (!empty($search_term)) $query_args['s'] = $search_term;

    $query = new WP_Query($query_args);
    $analytics = lm_get_all_links_analytics();
    ?>
    <div class="wrap link-manager-wrap container-fluid">
        <h1><?php esc_html_e('Link Manager Dashboard', 'link-manager'); ?></h1>
        <p class="lead"><?php esc_html_e('An overview of your site\'s link profile and health.', 'link-manager'); ?></p>

        <div class="row">
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="mb-0"><?php esc_html_e('Total Posts', 'link-manager'); ?> (<?php echo esc_html(number_format_i18n($analytics['total_posts_scanned'])); ?>)</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php esc_html_e('Internal Links', 'link-manager'); ?>
                                        <span class="badge badge-primary badge-pill"><?php echo esc_html(number_format_i18n($analytics['post_internal_links'])); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php esc_html_e('External Links', 'link-manager'); ?>
                                        <span class="badge badge-secondary badge-pill"><?php echo esc_html(number_format_i18n($analytics['post_external_links'])); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="mb-0"><?php esc_html_e('Total Pages', 'link-manager'); ?> (<?php echo esc_html(number_format_i18n($analytics['total_pages_scanned'])); ?>)</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php esc_html_e('Internal Links', 'link-manager'); ?>
                                        <span class="badge badge-primary badge-pill"><?php echo esc_html(number_format_i18n($analytics['page_internal_links'])); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php esc_html_e('External Links', 'link-manager'); ?>
                                        <span class="badge badge-secondary badge-pill"><?php echo esc_html(number_format_i18n($analytics['page_external_links'])); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                 
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><?php esc_html_e('External Link Attributes', 'link-manager'); ?></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item d-flex justify-content-between align-items-center">With nofollow<span class="badge badge-info badge-pill"><?php echo esc_html(number_format_i18n($analytics['nofollow_count'])); ?></span></li>
                           <li class="list-group-item d-flex justify-content-between align-items-center">With sponsored<span class="badge badge-info badge-pill"><?php echo esc_html(number_format_i18n($analytics['sponsored_count'])); ?></span></li>
                           <li class="list-group-item d-flex justify-content-between align-items-center">With ugc<span class="badge badge-info badge-pill"><?php echo esc_html(number_format_i18n($analytics['ugc_count'])); ?></span></li>
                           <li class="list-group-item d-flex justify-content-between align-items-center">With noopener<span class="badge badge-info badge-pill"><?php echo esc_html(number_format_i18n($analytics['noopener_count'])); ?></span></li>
                           <li class="list-group-item d-flex justify-content-between align-items-center">With noreferrer<span class="badge badge-info badge-pill"><?php echo esc_html(number_format_i18n($analytics['noreferrer_count'])); ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                 <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><?php esc_html_e('Insights & Suggestions', 'link-manager'); ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong><?php echo esc_html(number_format_i18n($analytics['total_items_scanned'])); ?></strong> <?php esc_html_e('items have been scanned across your site.', 'link-manager'); ?></p>
                        <?php
                        $settings = get_option('lm_settings', []);
                        if (empty($settings['auto_noopener_external'])) {
                           echo '<div class="alert alert-warning" role="alert"><strong>' . esc_html__('Security Suggestion:', 'link-manager') . '</strong> ' . sprintf(wp_kses(__('Enable the global "<code>noopener</code>" setting for better security. <a href="%s" class="alert-link">Update Settings</a>.', 'link-manager'),['a' => ['href' => [], 'class' => []], 'code' => []]), esc_url(admin_url('admin.php?page=link-manager-settings'))) . '</div>';
                        } else {
                           echo '<div class="alert alert-success" role="alert"><strong>' . esc_html__('Good Practice:', 'link-manager') . '</strong> ' . esc_html__('You have `noopener` enabled globally, which is great for security!', 'link-manager') . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>
        <h2><?php esc_html_e('Content Library', 'link-manager'); ?></h2>
        
        <div class="lm-table-wrapper">
            <div class="lm-table-header">
                <?php
                $base_filter_url = admin_url('admin.php?page=link-manager-overview');
                $all_url = add_query_arg(['post_type_filter' => 'all', 's_all' => $search_term ? urlencode($search_term) : false], $base_filter_url);
                $post_url = add_query_arg(['post_type_filter' => 'post', 's_all' => $search_term ? urlencode($search_term) : false], $base_filter_url);
                $page_url = add_query_arg(['post_type_filter' => 'page', 's_all' => $search_term ? urlencode($search_term) : false], $base_filter_url);
                ?>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="<?php echo esc_url(remove_query_arg('paged', $all_url)); ?>" class="nav-link <?php if ($current_post_type_filter === 'all') echo 'active'; ?>"><?php esc_html_e('All Types', 'link-manager'); ?></a></li>
                    <li class="nav-item"><a href="<?php echo esc_url(remove_query_arg('paged', $post_url)); ?>" class="nav-link <?php if ($current_post_type_filter === 'post') echo 'active'; ?>"><?php esc_html_e('Posts Only', 'link-manager'); ?></a></li>
                    <li class="nav-item"><a href="<?php echo esc_url(remove_query_arg('paged', $page_url)); ?>" class="nav-link <?php if ($current_post_type_filter === 'page') echo 'active'; ?>"><?php esc_html_e('Pages Only', 'link-manager'); ?></a></li>
                </ul>

                <form method="get" class="form-inline">
                    <input type="hidden" name="page" value="link-manager-overview">
                    <input type="hidden" name="post_type_filter" value="<?php echo esc_attr($current_post_type_filter); ?>">
                    <label for="s_all" class="sr-only"><?php esc_html_e('Search Term:', 'link-manager'); ?></label>
                    <input type="search" name="s_all" id="s_all" class="form-control mr-sm-2" placeholder="<?php esc_attr_e('Search content...', 'link-manager'); ?>" value="<?php echo esc_attr($search_term); ?>">
                    <button type="submit" class="btn btn-primary"><?php esc_attr_e('Search', 'link-manager'); ?></button>
                    <?php if (!empty($search_term)): 
                        $clear_search_url_args = ['page' => 'link-manager-overview', 'post_type_filter' => $current_post_type_filter];
                    ?>
                        <a href="<?php echo esc_url(admin_url(add_query_arg($clear_search_url_args, 'admin.php'))); ?>" class="btn btn-secondary ml-2"><?php esc_html_e('Clear', 'link-manager'); ?></a>
                    <?php endif; ?>
                </form>
            </div>
        
            <table class="widefat fixed striped lm-overview-table">
                <thead>
                    <tr>
                        <th class="lm-col-title"><?php esc_html_e('Post Title', 'link-manager'); ?></th>
                        <th class="lm-col-type"><?php esc_html_e('Type', 'link-manager'); ?></th>
                        <th class="lm-col-internal"><?php esc_html_e('Internal Links', 'link-manager'); ?></th>
                        <th class="lm-col-external"><?php esc_html_e('External Links', 'link-manager'); ?></th>
                        <th class="lm-col-actions"><?php esc_html_e('Actions', 'link-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($query->have_posts()):
                        while ($query->have_posts()) : $query->the_post();
                            $post_id = get_the_ID();
                            $post_type_obj = get_post_type_object(get_post_type($post_id));
                            $links = lm_get_links_from_post($post_id);
                    ?>
                        <tr>
                            <td><a href="<?php echo esc_url(get_permalink($post_id)); ?>" target="_blank"><?php the_title(); ?></a></td>
                            <td><?php echo esc_html($post_type_obj->labels->singular_name); ?></td>
                            <td><?php echo intval(count($links['internal'])); ?></td>
                            <td><?php echo intval(count($links['external'])); ?></td>
                            <td><a href="<?php echo esc_url(admin_url('admin.php?page=link-editor&post_id=' . $post_id)); ?>" class="button button-small"><?php esc_html_e('Manage Links', 'link-manager'); ?></a></td>
                        </tr>
                        <?php endwhile; 
                        wp_reset_postdata(); 
                    else: ?>
                        <tr><td colspan="5"><?php esc_html_e('No content found matching your criteria.', 'link-manager'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php
        $total_pages = $query->max_num_pages; 
        if ($total_pages > 1) {
            $current_page = max(1, $paged);
            $paginate_base_args = ['page' => 'link-manager-overview', 'post_type_filter' => $current_post_type_filter];
            if (!empty($search_term)) $paginate_base_args['s_all'] = $search_term;
            echo '<div class="tablenav"><div class="tablenav-pages">' . paginate_links(['base' => add_query_arg($paginate_base_args, admin_url('admin.php')), 'format' => '&paged=%#%', 'prev_text' => __('&laquo;'), 'next_text' => __('&raquo;'), 'total' => $total_pages, 'current' => $current_page]) . '</div></div>';
        }
        ?>
    </div>
    <?php
}
?>