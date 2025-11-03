<?php
// Helper function: Get all links from post content, separated into internal and external
function lm_get_links_from_post($post_id) {
    $content = get_post_field('post_content', $post_id);
    if (empty(trim($content))) { 
        return ['internal' => [], 'external' => []];
    }

    $internal_links = [];
    $external_links = [];

    $load_html_content = $content;
    if (preg_match('/<(!DOCTYPE|html|body|head)/i', trim($load_html_content)) === 0) {
        $load_html_content = '<div>' . $load_html_content . '</div>';
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    
    @$dom->loadHTML(mb_convert_encoding($load_html_content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors(); 

    $anchors = $dom->getElementsByTagName('a');
    $site_url = site_url();
    $site_host = parse_url($site_url, PHP_URL_HOST);

    foreach ($anchors as $a) {
        $href = $a->getAttribute('href');
        $text = trim($a->textContent); 
        if (empty($href) || strpos($href, 'javascript:') === 0) continue;

        $is_internal = false;
        $link_host = parse_url($href, PHP_URL_HOST);
        if (empty($link_host) && (strpos($href, '/') === 0 || strpos($href, '#') === 0 || !preg_match('/^([a-z]+:)?\/\//i', $href))) {
            $is_internal = true;
        } elseif ($link_host && strtolower($link_host) === strtolower($site_host)) {
            $is_internal = true;
        }
        $link_data = [
            'url' => $href,
            'text' => $text ?: $href, 
        ];
        $saved_attrs = lm_get_link_attributes($post_id, $href);
        if ($saved_attrs) {
            $link_data['attributes'] = $saved_attrs;
        } else { 
            $settings = get_option('lm_settings', []);
            $auto_nofollow = !empty($settings['auto_nofollow']);
            $auto_ugc = !empty($settings['auto_ugc']);
            $auto_noopener = !empty($settings['auto_noopener_external']);
            $auto_noreferrer = !empty($settings['auto_noreferrer_external']);
            $link_data['attributes'] = [
                'nofollow' => !$is_internal && $auto_nofollow,
                'sponsored' => false,
                'ugc' => !$is_internal && $auto_ugc,
                'noopener' => !$is_internal && $auto_noopener,
                'noreferrer' => !$is_internal && $auto_noreferrer, 
            ];
        }
        if ($is_internal) {
            $internal_links[] = $link_data;
        } else {
            $external_links[] = $link_data;
        }
    }
    return [
        'internal' => array_map("unserialize", array_unique(array_map("serialize", $internal_links))),
        'external' => array_map("unserialize", array_unique(array_map("serialize", $external_links))),
    ];
}

function lm_save_link_attributes($post_id, $link_url, $attributes) {
    $all_links_meta = get_post_meta($post_id, '_lm_link_attributes', true);
    if (!is_array($all_links_meta)) {
        $all_links_meta = [];
    }
    $sanitized_attributes = [
        'nofollow' => !empty($attributes['nofollow']),
        'sponsored' => !empty($attributes['sponsored']),
        'ugc' => !empty($attributes['ugc']),
        'noopener' => !empty($attributes['noopener']),
        'noreferrer' => !empty($attributes['noreferrer']),
    ];
    $all_links_meta[stripslashes($link_url)] = $sanitized_attributes;
    update_post_meta($post_id, '_lm_link_attributes', $all_links_meta);
}

function lm_get_link_attributes($post_id, $link_url) {
    $all_links_meta = get_post_meta($post_id, '_lm_link_attributes', true);
    if (is_array($all_links_meta) && isset($all_links_meta[$link_url])) {
        $attrs = $all_links_meta[$link_url];
        return [
            'nofollow' => !empty($attrs['nofollow']),
            'sponsored' => !empty($attrs['sponsored']),
            'ugc' => !empty($attrs['ugc']),
            'noopener' => !empty($attrs['noopener']),
            'noreferrer' => !empty($attrs['noreferrer']),
        ];
    }
    return false;
}

function lm_get_all_links_analytics() {
    $analytics = [
        'total_posts_scanned' => 0,
        'post_internal_links' => 0,
        'post_external_links' => 0,
        'total_pages_scanned' => 0, 
        'page_internal_links' => 0,
        'page_external_links' => 0,
        'total_other_post_types_scanned' => 0,
        'total_items_scanned' => 0,
        'nofollow_count' => 0,
        'sponsored_count' => 0,
        'ugc_count' => 0,
        'noopener_count' => 0,
        'noreferrer_count' => 0,
    ];
    $scannable_post_types = apply_filters('lm_scannable_post_types', ['post', 'page']);
    $args = [
        'post_type' => $scannable_post_types,
        'posts_per_page' => -1, 
        'post_status' => 'publish',
        'fields' => 'ids', 
    ];
    $post_ids = get_posts($args); 

    if (!empty($post_ids)) {
        $analytics['total_items_scanned'] = count($post_ids);
        
        foreach ($post_ids as $post_id) {
            $links_in_post = lm_get_links_from_post($post_id); 
            $current_post_type = get_post_type($post_id);

            if ($current_post_type === 'post') {
                $analytics['total_posts_scanned']++;
                $analytics['post_internal_links'] += count($links_in_post['internal']);
                $analytics['post_external_links'] += count($links_in_post['external']);
            } elseif ($current_post_type === 'page') {
                $analytics['total_pages_scanned']++;
                $analytics['page_internal_links'] += count($links_in_post['internal']);
                $analytics['page_external_links'] += count($links_in_post['external']);
            } else {
                $analytics['total_other_post_types_scanned']++;
            }

            $all_post_links_data = array_merge($links_in_post['internal'], $links_in_post['external']);
            
            foreach ($all_post_links_data as $link_data) {
                if (isset($link_data['attributes'])) {
                    $final_attrs = $link_data['attributes']; 
                    if (!empty($final_attrs['nofollow'])) $analytics['nofollow_count']++;
                    if (!empty($final_attrs['sponsored'])) $analytics['sponsored_count']++;
                    if (!empty($final_attrs['ugc'])) $analytics['ugc_count']++;
                    if (!empty($final_attrs['noopener'])) $analytics['noopener_count']++;
                    if (!empty($final_attrs['noreferrer'])) $analytics['noreferrer_count']++;
                }
            }
        }
    }
    return $analytics;
}
?>