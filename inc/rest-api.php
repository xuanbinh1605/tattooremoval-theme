<?php
/**
 * REST API Endpoints
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom REST API routes
 */
function str_register_rest_routes() {
    // Search clinics endpoint
    register_rest_route('str/v1', '/search', array(
        'methods' => 'GET',
        'callback' => 'str_search_clinics',
        'permission_callback' => '__return_true',
        'args' => array(
            'query' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'state' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'city' => array(
                'required' => false,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'per_page' => array(
                'default' => 10,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
            ),
            'page' => array(
                'default' => 1,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
            ),
        ),
    ));

    // Get clinic details endpoint
    register_rest_route('str/v1', '/clinic/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'str_get_clinic_details',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
            ),
        ),
    ));

    // Get states endpoint
    register_rest_route('str/v1', '/states', array(
        'methods' => 'GET',
        'callback' => 'str_get_states',
        'permission_callback' => '__return_true',
    ));

    // Location suggestions endpoint for autocomplete
    register_rest_route('str/v1', '/locations/suggest', array(
        'methods' => 'GET',
        'callback' => 'str_suggest_locations',
        'permission_callback' => '__return_true',
        'args' => array(
            'q' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));
}
add_action('rest_api_init', 'str_register_rest_routes');

/**
 * Search clinics callback
 */
function str_search_clinics($request) {
    $query_args = array(
        'post_type' => 'clinic',
        'post_status' => 'publish',
        'posts_per_page' => $request->get_param('per_page'),
        'paged' => $request->get_param('page'),
    );

    // Search by query string
    if ($request->get_param('query')) {
        $query_args['s'] = $request->get_param('query');
    }

    // Filter by state
    if ($request->get_param('state')) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'state',
            'field' => 'slug',
            'terms' => $request->get_param('state'),
        );
    }

    // Filter by city
    if ($request->get_param('city')) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'city',
            'field' => 'slug',
            'terms' => $request->get_param('city'),
        );
    }

    $query = new WP_Query($query_args);
    $clinics = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $clinics[] = str_format_clinic_data(get_the_ID());
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response(array(
        'success' => true,
        'data' => $clinics,
        'total' => $query->found_posts,
        'pages' => $query->max_num_pages,
    ), 200);
}

/**
 * Get clinic details callback
 */
function str_get_clinic_details($request) {
    $clinic_id = $request->get_param('id');
    $post = get_post($clinic_id);

    if (!$post || $post->post_type !== 'clinic') {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Clinic not found',
        ), 404);
    }

    return new WP_REST_Response(array(
        'success' => true,
        'data' => str_format_clinic_data($clinic_id),
    ), 200);
}

/**
 * Get states callback
 */
function str_get_states($request) {
    $states = get_terms(array(
        'taxonomy' => 'state',
        'hide_empty' => true,
    ));

    if (is_wp_error($states)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Error fetching states',
        ), 500);
    }

    $formatted_states = array();
    foreach ($states as $state) {
        $formatted_states[] = array(
            'id' => $state->term_id,
            'name' => $state->name,
            'slug' => $state->slug,
            'count' => $state->count,
        );
    }

    return new WP_REST_Response(array(
        'success' => true,
        'data' => $formatted_states,
    ), 200);
}

/**
 * Location suggestions callback for autocomplete
 */
function str_suggest_locations($request) {
    $query = $request->get_param('q');

    if (strlen($query) < 2) {
        return new WP_REST_Response(array('results' => array()), 200);
    }

    // Get all us_location terms (states = parent 0, cities = children)
    $all_terms = get_terms(array(
        'taxonomy'   => 'us_location',
        'hide_empty' => false,
        'search'     => $query,
        'number'     => 20,
    ));

    if (is_wp_error($all_terms)) {
        return new WP_REST_Response(array('results' => array()), 200);
    }

    $results = array();

    foreach ($all_terms as $term) {
        if ($term->parent === 0) {
            // This is a state
            $results[] = array(
                'type'  => 'state',
                'name'  => $term->name,
                'slug'  => $term->slug,
                'state' => $term->name,
                'city'  => '',
                'count' => $term->count,
            );
        } else {
            // This is a city — look up its parent state
            $parent = get_term($term->parent, 'us_location');
            $state_name = ($parent && !is_wp_error($parent)) ? $parent->name : '';
            $results[] = array(
                'type'  => 'city',
                'name'  => $term->name . ', ' . $state_name,
                'slug'  => $term->slug,
                'state' => $state_name,
                'city'  => $term->name,
                'count' => $term->count,
            );
        }
    }

    return new WP_REST_Response(array('results' => $results), 200);
}

/**
 * Format clinic data for API response
 */
function str_format_clinic_data($clinic_id) {
    $post = get_post($clinic_id);
    $open_status = str_get_clinic_open_status($clinic_id);
    
    return array(
        'id' => $clinic_id,
        'title' => get_the_title($clinic_id),
        'slug' => $post->post_name,
        'excerpt' => get_the_excerpt($clinic_id),
        'content' => apply_filters('the_content', $post->post_content),
        'url' => get_permalink($clinic_id),
        'image' => get_the_post_thumbnail_url($clinic_id, 'str-clinic-card'),
        'rating' => get_post_meta($clinic_id, '_clinic_rating', true),
        'reviews_count' => get_post_meta($clinic_id, '_clinic_reviews_count', true),
        'price_range' => get_post_meta($clinic_id, '_clinic_price_range', true),
        'phone' => get_post_meta($clinic_id, '_clinic_phone', true),
        'email' => get_post_meta($clinic_id, '_clinic_email', true),
        'website' => get_post_meta($clinic_id, '_clinic_website', true),
        'address' => get_post_meta($clinic_id, '_clinic_address', true),
        'city' => get_post_meta($clinic_id, '_clinic_city', true),
        'state' => get_post_meta($clinic_id, '_clinic_state', true),
        'zip' => get_post_meta($clinic_id, '_clinic_zip', true),
        'latitude' => get_post_meta($clinic_id, '_clinic_latitude', true),
        'longitude' => get_post_meta($clinic_id, '_clinic_longitude', true),
        'hours' => get_post_meta($clinic_id, '_clinic_operating_hours_raw', true),
        'open_status' => $open_status,
    );
}
