<?php
// Hook to filter post content and auto-add link attributes based on global settings and saved link attributes
function lm_filter_the_content($content) {
    if (is_admin() || !is_singular() || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    global $post;
    if (!$post) {
        return $content;
    }

    $saved_links_attributes = get_post_meta($post->ID, '_lm_link_attributes', true);
    if (!is_array($saved_links_attributes)) {
        $saved_links_attributes = [];
    }

    $settings = get_option('lm_settings', []);
    $auto_nofollow_global = !empty($settings['auto_nofollow']);
    $auto_ugc_global = !empty($settings['auto_ugc']);
    $auto_noopener_global = !empty($settings['auto_noopener_external']);
    $auto_noreferrer_global = !empty($settings['auto_noreferrer_external']);

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $load_content = $content;
    $is_wrapped = false;
    if (preg_match('/<(!DOCTYPE|html|body|head)/i', trim($load_content)) === 0) {
        $load_content = '<div>' . $load_content . '</div>';
        $is_wrapped = true;
    }
    @$dom->loadHTML(mb_convert_encoding($load_content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $anchors = $dom->getElementsByTagName('a');
    $site_url = site_url();
    $site_host = parse_url($site_url, PHP_URL_HOST);

    foreach ($anchors as $a) {
        $href = $a->getAttribute('href');
        if (!$href || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0 || strpos($href, 'javascript:') === 0) {
            continue;
        }

        $is_external = false;
        $link_host = parse_url($href, PHP_URL_HOST);

        if ($link_host && strtolower($link_host) !== strtolower($site_host) && !empty($link_host)) {
             $is_external = true;
        } elseif (empty($link_host) && preg_match('/^([a-z]+:)?\/\//i', $href) && strpos($href, $site_url) !== 0 ) {
            $is_external = true;
        }
        
        $attributes_to_apply = ['nofollow' => false, 'sponsored' => false, 'ugc' => false, 'noopener' => false, 'noreferrer' => false];
        $final_rels = [];

        if ($a->hasAttribute('rel')) {
            $existing_rels_array = preg_split('/\s+/', $a->getAttribute('rel'));
            foreach($existing_rels_array as $er) {
                if (!empty($er) && !in_array($er, ['nofollow', 'sponsored', 'ugc', 'noopener', 'noreferrer'])) { 
                    $final_rels[] = $er;
                }
            }
        }
        
        if ($is_external) { 
            if (isset($saved_links_attributes[$href])) { 
                $specific_attrs = $saved_links_attributes[$href];
                $attributes_to_apply['nofollow'] = !empty($specific_attrs['nofollow']);
                $attributes_to_apply['sponsored'] = !empty($specific_attrs['sponsored']);
                $attributes_to_apply['ugc'] = !empty($specific_attrs['ugc']);
                $attributes_to_apply['noopener'] = !empty($specific_attrs['noopener']);
                $attributes_to_apply['noreferrer'] = !empty($specific_attrs['noreferrer']);
            } else { 
                if ($auto_nofollow_global) $attributes_to_apply['nofollow'] = true;
                if ($auto_ugc_global) $attributes_to_apply['ugc'] = true;
                if ($auto_noopener_global) $attributes_to_apply['noopener'] = true;
                if ($auto_noreferrer_global) $attributes_to_apply['noreferrer'] = true;
            }
        }

        if ($attributes_to_apply['nofollow']) $final_rels[] = 'nofollow';
        if ($attributes_to_apply['sponsored']) $final_rels[] = 'sponsored';
        if ($attributes_to_apply['ugc']) $final_rels[] = 'ugc';
        if ($attributes_to_apply['noopener']) $final_rels[] = 'noopener';
        if ($attributes_to_apply['noreferrer']) $final_rels[] = 'noreferrer';

        $final_rels = array_unique($final_rels);
        if (!empty($final_rels)) {
            $a->setAttribute('rel', implode(' ', $final_rels)); 
        } else {
            $a->removeAttribute('rel');
        }
    }

    $html = $dom->saveHTML();
    $processed_content = $html;

    if ($is_wrapped) {
        $body_node = $dom->getElementsByTagName('body')->item(0);
        if ($body_node && $body_node->firstChild && strtolower($body_node->firstChild->nodeName) === 'div') {
            $html_fragment = '';
            foreach ($body_node->firstChild->childNodes as $child_node) {
                $html_fragment .= $dom->saveHTML($child_node);
            }
            $processed_content = $html_fragment;
        } elseif (substr($html, 0, 5) === '<div>' && substr($html, -6) === '</div>') { 
             $processed_content = substr($html, 5, -6);
        }
    } elseif (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) { 
        $processed_content = $matches[1];
    }
    
    return $processed_content ?: $content;
}
add_filter('the_content', 'lm_filter_the_content', 20000);
?>