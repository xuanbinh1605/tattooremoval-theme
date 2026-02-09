<?php
/**
 * Custom Fields for Clinics and Laser Technologies
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
        'clinic_basic_info',
        __('Basic Information', 'search-tattoo-removal'),
        'str_clinic_basic_info_callback',
        'clinic',
        'normal',
        'high'
    );

    add_meta_box(
        'clinic_rating',
        __('Rating & Reviews', 'search-tattoo-removal'),
        'str_clinic_rating_callback',
        'clinic',
        'normal',
        'high'
    );

    add_meta_box(
        'clinic_status_verification',
        __('Status & Verification', 'search-tattoo-removal'),
        'str_clinic_status_verification_callback',
        'clinic',
        'normal',
        'high'
    );

    add_meta_box(
        'clinic_address',
        __('Address Details', 'search-tattoo-removal'),
        'str_clinic_address_callback',
        'clinic',
        'normal',
        'high'
    );

    add_meta_box(
        'clinic_hours',
        __('Operating Hours', 'search-tattoo-removal'),
        'str_clinic_hours_callback',
        'clinic',
        'normal',
        'default'
    );

    add_meta_box(
        'clinic_pricing',
        __('Pricing', 'search-tattoo-removal'),
        'str_clinic_pricing_callback',
        'clinic',
        'normal',
        'default'
    );

    add_meta_box(
        'clinic_media',
        __('Media & Branding', 'search-tattoo-removal'),
        'str_clinic_media_callback',
        'clinic',
        'normal',
        'default'
    );

    add_meta_box(
        'clinic_business',
        __('Business Information', 'search-tattoo-removal'),
        'str_clinic_business_callback',
        'clinic',
        'side',
        'default'
    );

    add_meta_box(
        'clinic_laser_tech',
        __('Laser Technologies', 'search-tattoo-removal'),
        'str_clinic_laser_tech_callback',
        'clinic',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'str_add_clinic_meta_boxes');

/**
 * Basic Information Meta Box
 */
function str_clinic_basic_info_callback($post) {
    wp_nonce_field('str_save_clinic_meta', 'str_clinic_meta_nonce');
    
    $website = get_post_meta($post->ID, '_clinic_website', true);
    $phone = get_post_meta($post->ID, '_clinic_phone', true);
    $google_maps_url = get_post_meta($post->ID, '_clinic_google_maps_url', true);
    ?>
    <p>
        <label for="website"><strong><?php _e('Website URL:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="url" id="website" name="website" value="<?php echo esc_attr($website); ?>" style="width: 100%;" placeholder="https://example.com">
    </p>
    <p>
        <label for="phone"><strong><?php _e('Phone Number:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;" placeholder="(555) 123-4567">
    </p>
    <p>
        <label for="google_maps_url"><strong><?php _e('Google Maps URL:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="url" id="google_maps_url" name="google_maps_url" value="<?php echo esc_attr($google_maps_url); ?>" style="width: 100%;" placeholder="https://maps.google.com/...">
    </p>
    <?php
}

/**
 * Rating Meta Box
 */
function str_clinic_rating_callback($post) {
    $rating = get_post_meta($post->ID, '_clinic_rating', true);
    $reviews_count = get_post_meta($post->ID, '_clinic_reviews_count', true);
    $reviews_summary = get_post_meta($post->ID, '_clinic_reviews_summary', true);
    ?>
    <p>
        <label for="rating"><strong><?php _e('Rating (0-5):', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="number" id="rating" name="rating" value="<?php echo esc_attr($rating); ?>" step="0.1" min="0" max="5" style="width: 100%;">
    </p>
    <p>
        <label for="reviews_count"><strong><?php _e('Number of Reviews:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="number" id="reviews_count" name="reviews_count" value="<?php echo esc_attr($reviews_count); ?>" min="0" style="width: 100%;">
    </p>
    <p>
        <label for="reviews_summary"><strong><?php _e('What People Say (Reviews Summary):', 'search-tattoo-removal'); ?></strong></label><br>
        <textarea id="reviews_summary" name="reviews_summary" rows="5" style="width: 100%;"><?php echo esc_textarea($reviews_summary); ?></textarea>
        <span class="description"><?php _e('A summary of patient reviews that will be displayed in the "What People Say" section.', 'search-tattoo-removal'); ?></span>
    </p>
    <?php
}

/**
 * Status & Verification Meta Box
 */
function str_clinic_status_verification_callback($post) {
    $is_verified = get_post_meta($post->ID, '_clinic_is_verified', true);
    $open_status = get_post_meta($post->ID, '_clinic_open_status', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="is_verified" value="1" <?php checked($is_verified, '1'); ?>>
            <strong><?php _e('Verified Clinic', 'search-tattoo-removal'); ?></strong>
        </label>
        <br><span class="description"><?php _e('Shows "Verified" badge on clinic card', 'search-tattoo-removal'); ?></span>
    </p>
    <p>
        <label for="open_status"><strong><?php _e('Open Status:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="text" id="open_status" name="open_status" value="<?php echo esc_attr($open_status); ?>" style="width: 100%;" placeholder="Open Now, Closed until 9am tomorrow, etc.">
        <span class="description"><?php _e('Display text for current operating status (e.g., "Open Now", "Closed until 9am tomorrow")', 'search-tattoo-removal'); ?></span>
    </p>
    <?php
}

/**
 * Address Meta Box
 * NOTE: City & State should be set via US Location taxonomy, not here
 */
function str_clinic_address_callback($post) {
    $street = get_post_meta($post->ID, '_clinic_street', true);
    $zip_code = get_post_meta($post->ID, '_clinic_zip_code', true);
    $full_address = get_post_meta($post->ID, '_clinic_full_address', true);
    ?>
    <div style="background: #fff3cd; padding: 10px; margin-bottom: 15px; border-left: 4px solid #ffc107;">
        <strong>⚠️ Important:</strong> City & State MUST be set using the <strong>US Locations</strong> taxonomy (State → City), not these fields.
    </div>
    <p>
        <label for="street"><strong><?php _e('Street Address:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="text" id="street" name="street" value="<?php echo esc_attr($street); ?>" style="width: 100%;" placeholder="123 Main St, Suite 100">
    </p>
    <p>
        <label for="zip_code"><strong><?php _e('ZIP Code:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="text" id="zip_code" name="zip_code" value="<?php echo esc_attr($zip_code); ?>" style="width: 100%;" placeholder="12345">
    </p>
    <p>
        <label for="full_address"><strong><?php _e('Full Address (optional - for display):', 'search-tattoo-removal'); ?></strong></label><br>
        <textarea id="full_address" name="full_address" rows="3" style="width: 100%;" placeholder="Complete address including city, state, ZIP"><?php echo esc_textarea($full_address); ?></textarea>
    </p>
    <?php
}

/**
 * Operating Hours Meta Box
 */
function str_clinic_hours_callback($post) {
    $operating_hours_raw = get_post_meta($post->ID, '_clinic_operating_hours_raw', true);
    ?>
    <p>
        <label for="operating_hours_raw"><strong><?php _e('Operating Hours:', 'search-tattoo-removal'); ?></strong></label><br>
        <textarea id="operating_hours_raw" name="operating_hours_raw" rows="6" style="width: 100%;" placeholder="Mon-Fri: 9:00 AM - 5:00 PM&#10;Sat: 10:00 AM - 3:00 PM&#10;Sun: Closed"><?php echo esc_textarea($operating_hours_raw); ?></textarea>
        <span class="description"><?php _e('Enter raw hours text. Structured hours can be added later.', 'search-tattoo-removal'); ?></span>
    </p>
    <?php
}

/**
 * Pricing Meta Box
 */
function str_clinic_pricing_callback($post) {
    $min_price = get_post_meta($post->ID, '_clinic_min_price', true);
    $max_price = get_post_meta($post->ID, '_clinic_max_price', true);
    $consultation_price = get_post_meta($post->ID, '_clinic_consultation_price', true);
    $price_range_display = get_post_meta($post->ID, '_clinic_price_range_display', true);
    ?>
    <p>
        <label for="min_price"><strong><?php _e('Minimum Price ($):', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="number" id="min_price" name="min_price" value="<?php echo esc_attr($min_price); ?>" step="0.01" min="0" style="width: 100%;" placeholder="100.00">
    </p>
    <p>
        <label for="max_price"><strong><?php _e('Maximum Price ($):', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="number" id="max_price" name="max_price" value="<?php echo esc_attr($max_price); ?>" step="0.01" min="0" style="width: 100%;" placeholder="500.00">
    </p>
    <p>
        <label for="consultation_price"><strong><?php _e('Consultation Price:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="text" id="consultation_price" name="consultation_price" value="<?php echo esc_attr($consultation_price); ?>" style="width: 100%;" placeholder="Free, $50, Varies, etc.">
        <span class="description"><?php _e('Can be text like "Free", "$50", "Varies", etc.', 'search-tattoo-removal'); ?></span>
    </p>
    <p>
        <label for="price_range_display"><strong><?php _e('Price Range Display (for card):', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="text" id="price_range_display" name="price_range_display" value="<?php echo esc_attr($price_range_display); ?>" style="width: 100%;" placeholder="$90 range, $150 range, Consultation range, etc.">
        <span class="description"><?php _e('Display text shown on clinic cards (e.g., "$90 range", "$150 range", "Consultation range")', 'search-tattoo-removal'); ?></span>
    </p>
    <?php
}

/**
 * Media & Branding Meta Box
 */
function str_clinic_media_callback($post) {
    $logo = get_post_meta($post->ID, '_clinic_logo', true);
    $before_after_gallery = get_post_meta($post->ID, '_clinic_before_after_gallery', true);
    ?>
    <p>
        <label for="logo"><strong><?php _e('Logo URL:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="url" id="logo" name="logo" value="<?php echo esc_attr($logo); ?>" style="width: 100%;" placeholder="https://example.com/logo.png">
        <span class="description"><?php _e('Or use Featured Image for clinic thumbnail', 'search-tattoo-removal'); ?></span>
    </p>
    <p>
        <label for="before_after_gallery"><strong><?php _e('Before/After Gallery IDs:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="text" id="before_after_gallery" name="before_after_gallery" value="<?php echo esc_attr($before_after_gallery); ?>" style="width: 100%;" placeholder="123,456,789">
        <span class="description"><?php _e('Comma-separated image IDs from Media Library', 'search-tattoo-removal'); ?></span>
    </p>
    <?php
}

/**
 * Business Information Meta Box
 */
function str_clinic_business_callback($post) {
    $years_in_business = get_post_meta($post->ID, '_clinic_years_in_business', true);
    $is_featured = get_post_meta($post->ID, '_clinic_is_featured', true);
    ?>
    <p>
        <label for="years_in_business"><strong><?php _e('Years in Business:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="number" id="years_in_business" name="years_in_business" value="<?php echo esc_attr($years_in_business); ?>" min="0" style="width: 100%;">
    </p>
    <p>
        <label>
            <input type="checkbox" name="is_featured" value="1" <?php checked($is_featured, '1'); ?>>
            <strong><?php _e('Featured Clinic', 'search-tattoo-removal'); ?></strong>
        </label>
    </p>
    <?php
}

/**
 * Laser Technologies Meta Box
 */
function str_clinic_laser_tech_callback($post) {
    $laser_technologies = get_post_meta($post->ID, '_laser_technologies', true);
    $selected_ids = !empty($laser_technologies) ? explode(',', $laser_technologies) : array();
    
    $laser_techs = get_posts(array(
        'post_type' => 'laser_tech',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));
    ?>
    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
        <?php if ($laser_techs) : ?>
            <?php foreach ($laser_techs as $tech) : ?>
                <label style="display: block; margin-bottom: 5px;">
                    <input type="checkbox" name="laser_technologies[]" value="<?php echo $tech->ID; ?>" <?php checked(in_array($tech->ID, $selected_ids)); ?>>
                    <?php echo esc_html($tech->post_title); ?>
                </label>
            <?php endforeach; ?>
        <?php else : ?>
            <p><?php _e('No laser technologies found. Create some first.', 'search-tattoo-removal'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add meta boxes for Laser Technology
 */
function str_add_laser_tech_meta_boxes() {
    add_meta_box(
        'laser_tech_info',
        __('Technology Information', 'search-tattoo-removal'),
        'str_laser_tech_info_callback',
        'laser_tech',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'str_add_laser_tech_meta_boxes');

/**
 * Laser Tech Info Meta Box
 */
function str_laser_tech_info_callback($post) {
    wp_nonce_field('str_save_laser_tech_meta', 'str_laser_tech_meta_nonce');
    
    $official_website = get_post_meta($post->ID, '_official_website', true);
    $short_description = get_post_meta($post->ID, '_short_description', true);
    $technical_notes = get_post_meta($post->ID, '_technical_notes', true);
    ?>
    <p>
        <label for="official_website"><strong><?php _e('Official Website:', 'search-tattoo-removal'); ?></strong></label><br>
        <input type="url" id="official_website" name="official_website" value="<?php echo esc_attr($official_website); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="short_description"><strong><?php _e('Short Description:', 'search-tattoo-removal'); ?></strong></label><br>
        <textarea id="short_description" name="short_description" rows="3" style="width: 100%;"><?php echo esc_textarea($short_description); ?></textarea>
    </p>
    <p>
        <label for="technical_notes"><strong><?php _e('Technical Notes:', 'search-tattoo-removal'); ?></strong></label><br>
        <textarea id="technical_notes" name="technical_notes" rows="6" style="width: 100%;"><?php echo esc_textarea($technical_notes); ?></textarea>
    </p>
    <?php
}

/**
 * Save Clinic Meta Data
 */
function str_save_clinic_meta($post_id) {
    if (!isset($_POST['str_clinic_meta_nonce']) || !wp_verify_nonce($_POST['str_clinic_meta_nonce'], 'str_save_clinic_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'website', 'phone', 'google_maps_url', 'rating', 'reviews_count',
        'street', 'zip_code', 'full_address', 'operating_hours_raw',
        'min_price', 'max_price', 'consultation_price', 'price_range_display',
        'logo', 'before_after_gallery', 'years_in_business', 'open_status'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_clinic_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Handle textarea fields separately
    if (isset($_POST['reviews_summary'])) {
        update_post_meta($post_id, '_clinic_reviews_summary', sanitize_textarea_field($_POST['reviews_summary']));
    }

    // Handle featured checkbox
    $is_featured = isset($_POST['is_featured']) ? '1' : '0';
    update_post_meta($post_id, '_clinic_is_featured', $is_featured);

    // Handle verified checkbox
    $is_verified = isset($_POST['is_verified']) ? '1' : '0';
    update_post_meta($post_id, '_clinic_is_verified', $is_verified);

    // Handle laser technologies (relationship)
    if (isset($_POST['laser_technologies'])) {
        $tech_ids = array_map('intval', $_POST['laser_technologies']);
        update_post_meta($post_id, '_laser_technologies', implode(',', $tech_ids));
    } else {
        delete_post_meta($post_id, '_laser_technologies');
    }
}
add_action('save_post_clinic', 'str_save_clinic_meta');

/**
 * Save Laser Tech Meta Data
 */
function str_save_laser_tech_meta($post_id) {
    if (!isset($_POST['str_laser_tech_meta_nonce']) || !wp_verify_nonce($_POST['str_laser_tech_meta_nonce'], 'str_save_laser_tech_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('official_website', 'short_description', 'technical_notes');

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_laser_tech', 'str_save_laser_tech_meta');
