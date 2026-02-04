<?php
/**
 * Template Functions
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get clinic rating HTML
 */
function str_get_clinic_rating($clinic_id) {
    $rating = get_post_meta($clinic_id, '_clinic_rating', true);
    $reviews_count = get_post_meta($clinic_id, '_clinic_reviews_count', true);
    
    if (!$rating) {
        return '';
    }
    
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;
    
    $html = '<div class="clinic-rating">';
    $html .= '<div class="stars">';
    
    for ($i = 0; $i < $full_stars; $i++) {
        $html .= '<span class="star full">★</span>';
    }
    
    if ($half_star) {
        $html .= '<span class="star half">★</span>';
    }
    
    for ($i = 0; $i < $empty_stars; $i++) {
        $html .= '<span class="star empty">☆</span>';
    }
    
    $html .= '</div>';
    $html .= '<span class="rating-value">' . number_format($rating, 1) . '</span>';
    
    if ($reviews_count) {
        $html .= '<span class="reviews-count">(' . number_format($reviews_count) . ' reviews)</span>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Display clinic rating
 */
function str_clinic_rating($clinic_id) {
    echo str_get_clinic_rating($clinic_id);
}

/**
 * Get clinic address
 */
function str_get_clinic_address($clinic_id) {
    $address = get_post_meta($clinic_id, '_clinic_address', true);
    $city = get_post_meta($clinic_id, '_clinic_city', true);
    $state = get_post_meta($clinic_id, '_clinic_state', true);
    $zip = get_post_meta($clinic_id, '_clinic_zip', true);
    
    $parts = array_filter(array($address, $city, $state, $zip));
    
    return implode(', ', $parts);
}

/**
 * Display clinic address
 */
function str_clinic_address($clinic_id) {
    echo esc_html(str_get_clinic_address($clinic_id));
}

/**
 * Get clinic contact info
 */
function str_get_clinic_contact($clinic_id, $type = 'all') {
    $contact = array(
        'phone' => get_post_meta($clinic_id, '_clinic_phone', true),
        'email' => get_post_meta($clinic_id, '_clinic_email', true),
        'website' => get_post_meta($clinic_id, '_clinic_website', true),
    );
    
    if ($type === 'all') {
        return $contact;
    }
    
    return isset($contact[$type]) ? $contact[$type] : '';
}

/**
 * Pagination
 */
function str_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);
    
    if ($paged >= 1) {
        $links[] = $paged;
    }
    
    if ($paged >= 3) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
    
    if (($paged + 2) <= $max) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
    
    echo '<nav class="pagination"><ul>' . "\n";
    
    if (get_previous_posts_link()) {
        printf('<li>%s</li>' . "\n", get_previous_posts_link('&laquo; Previous'));
    }
    
    if (!in_array(1, $links)) {
        $class = 1 == $paged ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');
        
        if (!in_array(2, $links)) {
            echo '<li>…</li>';
        }
    }
    
    sort($links);
    foreach ((array) $links as $link) {
        $class = $paged == $link ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($link)), $link);
    }
    
    if (!in_array($max, $links)) {
        if (!in_array($max - 1, $links)) {
            echo '<li>…</li>' . "\n";
        }
        
        $class = $paged == $max ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($max)), $max);
    }
    
    if (get_next_posts_link()) {
        printf('<li>%s</li>' . "\n", get_next_posts_link('Next &raquo;'));
    }
    
    echo '</ul></nav>' . "\n";
}

/**
 * Custom menu item class for Tailwind styling
 */
function str_menu_item_classes($classes, $item, $args, $depth) {
    if ($args->theme_location === 'primary') {
        $classes = array('text-sm', 'font-semibold', 'text-graphite', 'hover:text-brand', 'transition-colors');
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'str_menu_item_classes', 10, 4);

/**
 * Custom menu link attributes for Tailwind styling
 */
function str_menu_link_attributes($atts, $item, $args, $depth) {
    if ($args->theme_location === 'primary') {
        $atts['class'] = 'text-sm font-semibold text-graphite hover:text-brand transition-colors';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'str_menu_link_attributes', 10, 4);
