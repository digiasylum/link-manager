<?php
// Add settings page
function lm_admin_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'link-manager'));
    }
    ?>
    <div class="wrap link-manager-wrap">
        <h1><?php esc_html_e('Link Manager Settings', 'link-manager'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('lm_settings_group');
            do_settings_sections('link-manager-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function lm_register_settings() {
    register_setting('lm_settings_group', 'lm_settings', 'lm_sanitize_settings');

    add_settings_section(
        'lm_section_general',
        __('General Settings for External Links', 'link-manager'),
        function() {
            echo '<p>' . esc_html__('Configure global link attribute settings. These primarily apply to external links that do not have specific attributes set in the Link Editor.', 'link-manager') . '</p>';
        },
        'link-manager-settings'
    );

    add_settings_field(
        'lm_auto_nofollow_external',
        __('Automatically add <code>rel="nofollow"</code>', 'link-manager'),
        'lm_auto_nofollow_external_callback',
        'link-manager-settings',
        'lm_section_general'
    );

    add_settings_field(
        'lm_auto_ugc_external',
        __('Automatically add <code>rel="ugc"</code>', 'link-manager'),
        'lm_auto_ugc_external_callback',
        'link-manager-settings',
        'lm_section_general'
    );

    add_settings_field(
        'lm_auto_noopener_external',
        __('Automatically add <code>rel="noopener"</code>', 'link-manager'),
        'lm_auto_noopener_external_callback',
        'link-manager-settings',
        'lm_section_general'
    );

    add_settings_field(
    'lm_auto_noreferrer_external',
    __('Automatically add <code>rel="noreferrer"</code>', 'link-manager'),
    'lm_auto_noreferrer_external_callback',
    'link-manager-settings',
    'lm_section_general'
);
}

function lm_auto_nofollow_external_callback() {
    $options = get_option('lm_settings', []);
    $auto_nofollow = isset($options['auto_nofollow']) ? (bool) $options['auto_nofollow'] : false;
    ?>
    <label for="lm_settings_auto_nofollow">
        <input type="checkbox" id="lm_settings_auto_nofollow" name="lm_settings[auto_nofollow]" value="1" <?php checked($auto_nofollow, true); ?>>
        <span class="description"><?php esc_html_e('If checked, external links will automatically get this attribute.', 'link-manager'); ?></span>
    </label>
    <?php
}

function lm_auto_ugc_external_callback() {
    $options = get_option('lm_settings', []);
    $auto_ugc = isset($options['auto_ugc']) ? (bool) $options['auto_ugc'] : false;
    ?>
    <label for="lm_settings_auto_ugc">
        <input type="checkbox" id="lm_settings_auto_ugc" name="lm_settings[auto_ugc]" value="1" <?php checked($auto_ugc, true); ?>>
        <span class="description"><?php esc_html_e('Good for links in comments or forums. If checked, external links will automatically get this attribute.', 'link-manager'); ?></span>
    </label>
    <?php
}

function lm_auto_noopener_external_callback() {
    $options = get_option('lm_settings', []);
    $auto_noopener = isset($options['auto_noopener_external']) ? (bool) $options['auto_noopener_external'] : false;
    ?>
    <label for="lm_settings_auto_noopener_external">
        <input type="checkbox" id="lm_settings_auto_noopener_external" name="lm_settings[auto_noopener_external]" value="1" <?php checked($auto_noopener, true); ?>>
        <span class="description"><?php esc_html_e('If checked, external links will automatically get this attribute. (Recommended for security)', 'link-manager'); ?></span>
    </label>
    <?php
}

function lm_auto_noreferrer_external_callback() {
    $options = get_option('lm_settings', []);
    $auto_noreferrer = isset($options['auto_noreferrer_external']) ? (bool) $options['auto_noreferrer_external'] : false;
    ?>
    <label for="lm_settings_auto_noreferrer_external">
        <input type="checkbox" id="lm_settings_auto_noreferrer_external" name="lm_settings[auto_noreferrer_external]" value="1" <?php checked($auto_noreferrer, true); ?>>
        <span class="description"><?php esc_html_e('If checked, external links will automatically get this attribute. (Improves privacy)', 'link-manager'); ?></span>
    </label>
    <?php
}

function lm_sanitize_settings($input) {
    $output = [];
    $output['auto_nofollow'] = !empty($input['auto_nofollow']) ? 1 : 0;
    $output['auto_ugc'] = !empty($input['auto_ugc']) ? 1 : 0;
    $output['auto_noopener_external'] = !empty($input['auto_noopener_external']) ? 1 : 0;
    $output['auto_noreferrer_external'] = !empty($input['auto_noreferrer_external']) ? 1 : 0; 
    return $output;
}
?>