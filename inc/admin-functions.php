<?php
/**
 * Admin Functions
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom columns to clinic list
 */
function str_clinic_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['rating'] = __('Rating', 'search-tattoo-removal');
            $new_columns['city'] = __('City', 'search-tattoo-removal');
            $new_columns['state'] = __('State', 'search-tattoo-removal');
            $new_columns['phone'] = __('Phone', 'search-tattoo-removal');
        }
    }
    
    return $new_columns;
}
add_filter('manage_clinic_posts_columns', 'str_clinic_columns');

/**
 * Populate custom columns
 */
function str_clinic_column_content($column, $post_id) {
    switch ($column) {
        case 'rating':
            $rating = get_post_meta($post_id, '_clinic_rating', true);
            echo $rating ? number_format($rating, 1) . ' ★' : '—';
            break;
            
        case 'city':
            $city = get_post_meta($post_id, '_clinic_city', true);
            echo $city ? esc_html($city) : '—';
            break;
            
        case 'state':
            $state = get_post_meta($post_id, '_clinic_state', true);
            echo $state ? esc_html($state) : '—';
            break;
            
        case 'phone':
            $phone = get_post_meta($post_id, '_clinic_phone', true);
            echo $phone ? esc_html($phone) : '—';
            break;
    }
}
add_action('manage_clinic_posts_custom_column', 'str_clinic_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function str_clinic_sortable_columns($columns) {
    $columns['rating'] = 'rating';
    $columns['city'] = 'city';
    $columns['state'] = 'state';
    
    return $columns;
}
add_filter('manage_edit-clinic_sortable_columns', 'str_clinic_sortable_columns');

/**
 * Custom column sorting
 */
function str_clinic_column_orderby($query) {
    if (!is_admin()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ('rating' === $orderby) {
        $query->set('meta_key', '_clinic_rating');
        $query->set('orderby', 'meta_value_num');
    } elseif ('city' === $orderby) {
        $query->set('meta_key', '_clinic_city');
        $query->set('orderby', 'meta_value');
    } elseif ('state' === $orderby) {
        $query->set('meta_key', '_clinic_state');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'str_clinic_column_orderby');
