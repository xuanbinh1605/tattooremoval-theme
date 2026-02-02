<?php
/**
 * Custom Fields for Clinics
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for clinic information
 */
function str_add_clinic_meta_boxes() {
    add_meta_box(
        'clinic_details',
        __('Clinic Details', 'search-tattoo-removal'),
        'str_clinic_details_callback',
        'clinic',
        'normal',
        'high'
    );

    add_meta_box(
        'clinic_contact',
        __('Contact Information', 'search-tattoo-removal'),
        'str_clinic_contact_callback',
        'clinic',
        'normal',
        'high'
    );

    add_meta_box(
        'clinic_location',
        __('Location', 'search-tattoo-removal'),
        'str_clinic_location_callback',
        'clinic',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'str_add_clinic_meta_boxes');

/**
 * Clinic Details Meta Box Callback
 */
function str_clinic_details_callback($post) {
    wp_nonce_field('str_save_clinic_details', 'str_clinic_details_nonce');
    
    $rating = get_post_meta($post->ID, '_clinic_rating', true);
    $reviews_count = get_post_meta($post->ID, '_clinic_reviews_count', true);
    $price_range = get_post_meta($post->ID, '_clinic_price_range', true);
    $established_year = get_post_meta($post->ID, '_clinic_established_year', true);
    ?>
    <p>
        <label for="clinic_rating"><?php _e('Rating (0-5):', 'search-tattoo-removal'); ?></label><br>
        <input type="number" id="clinic_rating" name="clinic_rating" value="<?php echo esc_attr($rating); ?>" step="0.1" min="0" max="5" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_reviews_count"><?php _e('Number of Reviews:', 'search-tattoo-removal'); ?></label><br>
        <input type="number" id="clinic_reviews_count" name="clinic_reviews_count" value="<?php echo esc_attr($reviews_count); ?>" min="0" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_price_range"><?php _e('Price Range:', 'search-tattoo-removal'); ?></label><br>
        <select id="clinic_price_range" name="clinic_price_range" style="width: 100%;">
            <option value="">Select Price Range</option>
            <option value="$" <?php selected($price_range, '$'); ?>>$ - Budget</option>
            <option value="$$" <?php selected($price_range, '$$'); ?>>$$ - Moderate</option>
            <option value="$$$" <?php selected($price_range, '$$$'); ?>>$$$ - Premium</option>
            <option value="$$$$" <?php selected($price_range, '$$$$'); ?>>$$$$ - Luxury</option>
        </select>
    </p>
    <p>
        <label for="clinic_established_year"><?php _e('Established Year:', 'search-tattoo-removal'); ?></label><br>
        <input type="number" id="clinic_established_year" name="clinic_established_year" value="<?php echo esc_attr($established_year); ?>" min="1900" max="<?php echo date('Y'); ?>" style="width: 100%;">
    </p>
    <?php
}

/**
 * Clinic Contact Meta Box Callback
 */
function str_clinic_contact_callback($post) {
    wp_nonce_field('str_save_clinic_contact', 'str_clinic_contact_nonce');
    
    $phone = get_post_meta($post->ID, '_clinic_phone', true);
    $email = get_post_meta($post->ID, '_clinic_email', true);
    $website = get_post_meta($post->ID, '_clinic_website', true);
    $hours = get_post_meta($post->ID, '_clinic_hours', true);
    ?>
    <p>
        <label for="clinic_phone"><?php _e('Phone Number:', 'search-tattoo-removal'); ?></label><br>
        <input type="tel" id="clinic_phone" name="clinic_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_email"><?php _e('Email:', 'search-tattoo-removal'); ?></label><br>
        <input type="email" id="clinic_email" name="clinic_email" value="<?php echo esc_attr($email); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_website"><?php _e('Website:', 'search-tattoo-removal'); ?></label><br>
        <input type="url" id="clinic_website" name="clinic_website" value="<?php echo esc_attr($website); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_hours"><?php _e('Business Hours:', 'search-tattoo-removal'); ?></label><br>
        <textarea id="clinic_hours" name="clinic_hours" rows="4" style="width: 100%;"><?php echo esc_textarea($hours); ?></textarea>
        <span class="description"><?php _e('e.g., Mon-Fri: 9am-5pm, Sat: 10am-3pm', 'search-tattoo-removal'); ?></span>
    </p>
    <?php
}

/**
 * Clinic Location Meta Box Callback
 */
function str_clinic_location_callback($post) {
    wp_nonce_field('str_save_clinic_location', 'str_clinic_location_nonce');
    
    $address = get_post_meta($post->ID, '_clinic_address', true);
    $city = get_post_meta($post->ID, '_clinic_city', true);
    $state = get_post_meta($post->ID, '_clinic_state', true);
    $zip = get_post_meta($post->ID, '_clinic_zip', true);
    $latitude = get_post_meta($post->ID, '_clinic_latitude', true);
    $longitude = get_post_meta($post->ID, '_clinic_longitude', true);
    ?>
    <p>
        <label for="clinic_address"><?php _e('Street Address:', 'search-tattoo-removal'); ?></label><br>
        <input type="text" id="clinic_address" name="clinic_address" value="<?php echo esc_attr($address); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_city"><?php _e('City:', 'search-tattoo-removal'); ?></label><br>
        <input type="text" id="clinic_city" name="clinic_city" value="<?php echo esc_attr($city); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_state"><?php _e('State:', 'search-tattoo-removal'); ?></label><br>
        <input type="text" id="clinic_state" name="clinic_state" value="<?php echo esc_attr($state); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_zip"><?php _e('ZIP Code:', 'search-tattoo-removal'); ?></label><br>
        <input type="text" id="clinic_zip" name="clinic_zip" value="<?php echo esc_attr($zip); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_latitude"><?php _e('Latitude:', 'search-tattoo-removal'); ?></label><br>
        <input type="text" id="clinic_latitude" name="clinic_latitude" value="<?php echo esc_attr($latitude); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="clinic_longitude"><?php _e('Longitude:', 'search-tattoo-removal'); ?></label><br>
        <input type="text" id="clinic_longitude" name="clinic_longitude" value="<?php echo esc_attr($longitude); ?>" style="width: 100%;">
    </p>
    <?php
}

/**
 * Save Clinic Meta Data
 */
function str_save_clinic_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['str_clinic_details_nonce']) && 
        !isset($_POST['str_clinic_contact_nonce']) && 
        !isset($_POST['str_clinic_location_nonce'])) {
        return;
    }

    // Verify nonces
    if (isset($_POST['str_clinic_details_nonce']) && 
        !wp_verify_nonce($_POST['str_clinic_details_nonce'], 'str_save_clinic_details')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save clinic details
    $fields = array(
        'clinic_rating' => '_clinic_rating',
        'clinic_reviews_count' => '_clinic_reviews_count',
        'clinic_price_range' => '_clinic_price_range',
        'clinic_established_year' => '_clinic_established_year',
        'clinic_phone' => '_clinic_phone',
        'clinic_email' => '_clinic_email',
        'clinic_website' => '_clinic_website',
        'clinic_hours' => '_clinic_hours',
        'clinic_address' => '_clinic_address',
        'clinic_city' => '_clinic_city',
        'clinic_state' => '_clinic_state',
        'clinic_zip' => '_clinic_zip',
        'clinic_latitude' => '_clinic_latitude',
        'clinic_longitude' => '_clinic_longitude',
    );

    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_clinic', 'str_save_clinic_meta');
