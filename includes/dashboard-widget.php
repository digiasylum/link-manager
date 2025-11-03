<?php
/**
 * Link Manager Dashboard Widget
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

function lm_setup_dashboard_widget_action() {
    if ( current_user_can( 'manage_options' ) ) {
        wp_add_dashboard_widget(
            'lm_dashboard_stats_widget',        
            __( 'Link Manager Stats', 'link-manager' ), 
            'lm_render_dashboard_stats_widget_content' 
        );
    }
}

function lm_render_dashboard_stats_widget_content() {
    $analytics = lm_get_all_links_analytics(); 
    ?>
    <div class="lm-dashboard-widget-content">
        <?php 
        $total_scanned_items = $analytics['total_items_scanned']; 
        if ( empty( $analytics ) || $total_scanned_items === 0 ) : ?>
            <p><?php esc_html_e( 'No link data has been processed yet. Visit the Link Manager overview page to start populating stats.', 'link-manager' ); ?></p>
        <?php else : ?>
            <table class="lm-dashboard-stats-table">
                <tr>
                    <td><strong><?php esc_html_e( 'Total Posts Scanned:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['total_posts_scanned'] ) ); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e( 'Total Pages Scanned:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['total_pages_scanned'] ) ); ?></td>
                </tr>
                <?php if ($analytics['total_other_post_types_scanned'] > 0) : ?>
                <tr>
                    <td><strong><?php esc_html_e( 'Other Types Scanned:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['total_other_post_types_scanned'] ) ); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="border-top: 1px dashed #e0e0e0;"><strong><?php esc_html_e( 'Total Internal Links:', 'link-manager' ); ?></strong></td>
                    <td style="border-top: 1px dashed #e0e0e0;"><?php echo esc_html( number_format_i18n( $analytics['total_internal_links'] ) ); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e( 'Total External Links:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['total_external_links'] ) ); ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="lm-dashboard-stats-subtitle"><em><?php esc_html_e( 'External Link Attributes (approximate):', 'link-manager' ); ?></em></td>
                </tr>
                <tr>
                    <td class="lm-dashboard-stats-indented"><strong><?php esc_html_e( 'With nofollow:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['nofollow_count'] ) ); ?></td>
                </tr>
                <tr>
                    <td class="lm-dashboard-stats-indented"><strong><?php esc_html_e( 'With sponsored:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['sponsored_count'] ) ); ?></td>
                </tr>
                 <tr>
                    <td class="lm-dashboard-stats-indented"><strong><?php esc_html_e( 'With ugc:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['ugc_count'] ) ); ?></td>
                </tr>
                <tr>
                    <td class="lm-dashboard-stats-indented"><strong><?php esc_html_e( 'With noopener:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['noopener_count'] ) ); ?></td>
                </tr>
                <tr>
                    <td class="lm-dashboard-stats-indented"><strong><?php esc_html_e( 'With noreferrer:', 'link-manager' ); ?></strong></td>
                    <td><?php echo esc_html( number_format_i18n( $analytics['noreferrer_count'] ) ); ?></td>
                </tr>
            </table>
        <?php endif; ?>
        <p class="lm-dashboard-widget-footer">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=link-manager-overview' ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'View Full Link Report', 'link-manager' ); ?>
            </a>
        </p>
    </div>
    <?php
}