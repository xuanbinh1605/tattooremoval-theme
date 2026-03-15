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
 * Get clinic thumbnail URL with priority
 * 
 * Priority order:
 * 1. Custom thumbnail URL from meta field (_clinic_thumbnail_url)
 * 2. Featured image
 * 3. Default placeholder
 * 
 * @param int $clinic_id Clinic post ID
 * @param string $size Image size (default: 'large')
 * @param string $default Default placeholder URL
 * @return string Thumbnail URL
 */
function str_get_clinic_thumbnail($clinic_id, $size = 'large', $default = '') {
    // Priority 1: Check for custom thumbnail URL
    $thumbnail_url = get_post_meta($clinic_id, '_clinic_thumbnail_url', true);
    if (!empty($thumbnail_url)) {
        return esc_url($thumbnail_url);
    }
    
    // Priority 2: Check for featured image
    $featured_image = get_the_post_thumbnail_url($clinic_id, $size);
    if ($featured_image) {
        return esc_url($featured_image);
    }
    
    // Priority 3: Return default or generic placeholder
    if (!empty($default)) {
        return esc_url($default);
    }
    
    return 'https://placehold.co/400x300?text=No+Image';
}

/**
 * US state name → default IANA timezone mapping.
 * Used as fallback when a clinic has no explicit timezone saved.
 */
function str_get_state_timezone_map() {
    return array(
        'Alabama' => 'America/Chicago', 'Alaska' => 'America/Anchorage',
        'Arizona' => 'America/Phoenix', 'Arkansas' => 'America/Chicago',
        'California' => 'America/Los_Angeles', 'Colorado' => 'America/Denver',
        'Connecticut' => 'America/New_York', 'Delaware' => 'America/New_York',
        'Florida' => 'America/New_York', 'Georgia' => 'America/New_York',
        'Hawaii' => 'Pacific/Honolulu', 'Idaho' => 'America/Boise',
        'Illinois' => 'America/Chicago', 'Indiana' => 'America/Indiana/Indianapolis',
        'Iowa' => 'America/Chicago', 'Kansas' => 'America/Chicago',
        'Kentucky' => 'America/New_York', 'Louisiana' => 'America/Chicago',
        'Maine' => 'America/New_York', 'Maryland' => 'America/New_York',
        'Massachusetts' => 'America/New_York', 'Michigan' => 'America/Detroit',
        'Minnesota' => 'America/Chicago', 'Mississippi' => 'America/Chicago',
        'Missouri' => 'America/Chicago', 'Montana' => 'America/Denver',
        'Nebraska' => 'America/Chicago', 'Nevada' => 'America/Los_Angeles',
        'New Hampshire' => 'America/New_York', 'New Jersey' => 'America/New_York',
        'New Mexico' => 'America/Denver', 'New York' => 'America/New_York',
        'North Carolina' => 'America/New_York', 'North Dakota' => 'America/Chicago',
        'Ohio' => 'America/New_York', 'Oklahoma' => 'America/Chicago',
        'Oregon' => 'America/Los_Angeles', 'Pennsylvania' => 'America/New_York',
        'Rhode Island' => 'America/New_York', 'South Carolina' => 'America/New_York',
        'South Dakota' => 'America/Chicago', 'Tennessee' => 'America/Chicago',
        'Texas' => 'America/Chicago', 'Utah' => 'America/Denver',
        'Vermont' => 'America/New_York', 'Virginia' => 'America/New_York',
        'Washington' => 'America/Los_Angeles', 'West Virginia' => 'America/New_York',
        'Wisconsin' => 'America/Chicago', 'Wyoming' => 'America/Denver',
        'District of Columbia' => 'America/New_York',
    );
}

/**
 * Determine a clinic's IANA timezone.
 * Priority: explicit meta → state-based lookup via taxonomy → site default.
 */
function str_get_clinic_timezone($clinic_id) {
    // 1. Explicit timezone saved on the clinic
    $tz = get_post_meta($clinic_id, '_clinic_timezone', true);
    if ($tz) {
        return $tz;
    }

    // 2. Derive from the clinic's US Location taxonomy (state level)
    $terms = wp_get_post_terms($clinic_id, 'us_location');
    if (!empty($terms) && !is_wp_error($terms)) {
        $map = str_get_state_timezone_map();
        foreach ($terms as $term) {
            // Find the top-level (state) term
            $state_term = $term;
            while ($state_term->parent) {
                $state_term = get_term($state_term->parent, 'us_location');
            }
            if (isset($map[$state_term->name])) {
                return $map[$state_term->name];
            }
        }
    }

    // 3. Fallback to site timezone
    return wp_timezone_string();
}

/**
 * Get real-time open/closed status for a clinic.
 *
 * @param int $clinic_id
 * @return array {
 *   'status' => 'open'|'closed'|'closing_soon',
 *   'text'   => Displayed label,
 *   'class'  => Tailwind text color class
 * }
 */
function str_get_clinic_open_status($clinic_id) {
    $default = array('status' => 'unknown', 'text' => 'Call for hours', 'class' => 'text-graphite');

    $structured_json = get_post_meta($clinic_id, '_clinic_structured_hours', true);
    if (!$structured_json) {
        return $default;
    }

    $hours = json_decode($structured_json, true);
    if (!is_array($hours) || empty($hours)) {
        return $default;
    }

    $tz_string = str_get_clinic_timezone($clinic_id);
    try {
        $tz  = new DateTimeZone($tz_string);
        $now = new DateTime('now', $tz);
    } catch (Exception $e) {
        return $default;
    }

    $day_key = strtolower($now->format('l')); // e.g. "monday"

    if (!isset($hours[$day_key]) || empty($hours[$day_key]['open']) || empty($hours[$day_key]['close'])) {
        return array('status' => 'closed', 'text' => 'Closed Today', 'class' => 'text-red-500');
    }

    $open_time  = DateTime::createFromFormat('H:i', $hours[$day_key]['open'], $tz);
    $close_time = DateTime::createFromFormat('H:i', $hours[$day_key]['close'], $tz);

    if (!$open_time || !$close_time) {
        return $default;
    }

    // Set the date portion to today so comparison is accurate
    $open_time->setDate((int)$now->format('Y'), (int)$now->format('m'), (int)$now->format('d'));
    $close_time->setDate((int)$now->format('Y'), (int)$now->format('m'), (int)$now->format('d'));

    if ($now >= $open_time && $now < $close_time) {
        // Currently open — check if closing within 60 minutes
        $diff_minutes = ($close_time->getTimestamp() - $now->getTimestamp()) / 60;
        if ($diff_minutes <= 60) {
            $close_display = $close_time->format('g:i A');
            return array(
                'status' => 'closing_soon',
                'text'   => 'Closes at ' . $close_display,
                'class'  => 'text-amber',
            );
        }
        return array('status' => 'open', 'text' => 'Open Now', 'class' => 'text-teal');
    }

    // Currently closed — find next opening
    if ($now < $open_time) {
        $open_display = $open_time->format('g:i A');
        return array('status' => 'closed', 'text' => 'Opens at ' . $open_display, 'class' => 'text-red-500');
    }

    return array('status' => 'closed', 'text' => 'Closed Now', 'class' => 'text-red-500');
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
