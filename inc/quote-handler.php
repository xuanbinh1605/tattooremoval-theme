<?php
/**
 * Quote Request Handler
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Quote Meta Boxes
 */
function str_add_quote_meta_boxes() {
    add_meta_box(
        'quote_details',
        __('Quote Request Details', 'search-tattoo-removal'),
        'str_quote_details_callback',
        'quote_request',
        'normal',
        'high'
    );

    add_meta_box(
        'quote_tattoo_details',
        __('Tattoo Details', 'search-tattoo-removal'),
        'str_quote_tattoo_details_callback',
        'quote_request',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'str_add_quote_meta_boxes');

/**
 * Quote Details Meta Box
 */
function str_quote_details_callback($post) {
    $name = get_post_meta($post->ID, '_quote_name', true);
    $email = get_post_meta($post->ID, '_quote_email', true);
    $phone = get_post_meta($post->ID, '_quote_phone', true);
    $location = get_post_meta($post->ID, '_quote_location', true);
    $clinic_id = get_post_meta($post->ID, '_quote_clinic_id', true);
    $status = get_post_meta($post->ID, '_quote_status', true) ?: 'pending';
    $ip_address = get_post_meta($post->ID, '_quote_ip_address', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label><?php _e('Name:', 'search-tattoo-removal'); ?></label></th>
            <td><strong><?php echo esc_html($name); ?></strong></td>
        </tr>
        <tr>
            <th><label><?php _e('Email:', 'search-tattoo-removal'); ?></label></th>
            <td><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td>
        </tr>
        <tr>
            <th><label><?php _e('Phone:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo esc_html($phone); ?></td>
        </tr>
        <tr>
            <th><label><?php _e('Location:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo esc_html($location); ?></td>
        </tr>
        <?php if ($clinic_id) : ?>
        <tr>
            <th><label><?php _e('Clinic:', 'search-tattoo-removal'); ?></label></th>
            <td><a href="<?php echo get_edit_post_link($clinic_id); ?>"><?php echo get_the_title($clinic_id); ?></a></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th><label><?php _e('Status:', 'search-tattoo-removal'); ?></label></th>
            <td>
                <select name="quote_status" style="width: 200px;">
                    <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending', 'search-tattoo-removal'); ?></option>
                    <option value="contacted" <?php selected($status, 'contacted'); ?>><?php _e('Contacted', 'search-tattoo-removal'); ?></option>
                    <option value="quoted" <?php selected($status, 'quoted'); ?>><?php _e('Quoted', 'search-tattoo-removal'); ?></option>
                    <option value="completed" <?php selected($status, 'completed'); ?>><?php _e('Completed', 'search-tattoo-removal'); ?></option>
                    <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php _e('Cancelled', 'search-tattoo-removal'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label><?php _e('IP Address:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo esc_html($ip_address); ?></td>
        </tr>
        <tr>
            <th><label><?php _e('Submitted:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo get_the_date('F j, Y g:i a', $post->ID); ?></td>
        </tr>
    </table>
    <?php
    wp_nonce_field('str_save_quote_meta', 'str_quote_meta_nonce');
}

/**
 * Tattoo Details Meta Box
 */
function str_quote_tattoo_details_callback($post) {
    $tattoo_size = get_post_meta($post->ID, '_quote_tattoo_size', true);
    $tattoo_colors = get_post_meta($post->ID, '_quote_tattoo_colors', true);
    $tattoo_location = get_post_meta($post->ID, '_quote_tattoo_location', true);
    $additional_info = get_post_meta($post->ID, '_quote_additional_info', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label><?php _e('Tattoo Size:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo esc_html($tattoo_size); ?></td>
        </tr>
        <tr>
            <th><label><?php _e('Colors:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo esc_html($tattoo_colors); ?></td>
        </tr>
        <tr>
            <th><label><?php _e('Body Location:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo esc_html($tattoo_location); ?></td>
        </tr>
        <?php if ($additional_info) : ?>
        <tr>
            <th><label><?php _e('Additional Information:', 'search-tattoo-removal'); ?></label></th>
            <td><?php echo nl2br(esc_html($additional_info)); ?></td>
        </tr>
        <?php endif; ?>
    </table>
    <?php
}

/**
 * Save Quote Meta
 */
function str_save_quote_meta($post_id) {
    if (!isset($_POST['str_quote_meta_nonce']) || !wp_verify_nonce($_POST['str_quote_meta_nonce'], 'str_save_quote_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['quote_status'])) {
        update_post_meta($post_id, '_quote_status', sanitize_text_field($_POST['quote_status']));
    }
}
add_action('save_post_quote_request', 'str_save_quote_meta');

/**
 * AJAX Handler for Quote Form Submission
 */
function str_handle_quote_submission() {
    check_ajax_referer('str_quote_nonce', 'nonce');

    $errors = array();
    
    // Validate required fields
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $location = sanitize_text_field($_POST['location'] ?? '');
    $tattoo_size = sanitize_text_field($_POST['tattoo_size'] ?? '');
    $tattoo_colors = sanitize_text_field($_POST['tattoo_colors'] ?? '');
    $tattoo_location = sanitize_text_field($_POST['tattoo_location'] ?? '');
    $additional_info = sanitize_textarea_field($_POST['additional_info'] ?? '');
    $clinic_id = intval($_POST['clinic_id'] ?? 0);
    
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !is_email($email)) $errors[] = 'Valid email is required';
    if (empty($phone)) $errors[] = 'Phone is required';
    if (empty($location)) $errors[] = 'Location is required';
    if (empty($tattoo_size)) $errors[] = 'Tattoo size is required';

    // Handle file upload
    $tattoo_image_id = 0;
    if (!empty($_FILES['tattoo_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $file = $_FILES['tattoo_image'];
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            $attachment = array(
                'post_mime_type' => $movefile['type'],
                'post_title'     => sanitize_file_name($file['name']),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $tattoo_image_id = wp_insert_attachment($attachment, $movefile['file']);
            wp_update_attachment_metadata($tattoo_image_id, wp_generate_attachment_metadata($tattoo_image_id, $movefile['file']));
        }
    }

    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode(', ', $errors)));
    }

    // Create quote request post
    $post_id = wp_insert_post(array(
        'post_title'  => sprintf('%s - %s', $name, $location),
        'post_type'   => 'quote_request',
        'post_status' => 'publish',
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Failed to create quote request'));
    }

    // Set featured image if uploaded
    if ($tattoo_image_id) {
        set_post_thumbnail($post_id, $tattoo_image_id);
    }

    // Save meta data
    update_post_meta($post_id, '_quote_name', $name);
    update_post_meta($post_id, '_quote_email', $email);
    update_post_meta($post_id, '_quote_phone', $phone);
    update_post_meta($post_id, '_quote_location', $location);
    update_post_meta($post_id, '_quote_tattoo_size', $tattoo_size);
    update_post_meta($post_id, '_quote_tattoo_colors', $tattoo_colors);
    update_post_meta($post_id, '_quote_tattoo_location', $tattoo_location);
    update_post_meta($post_id, '_quote_additional_info', $additional_info);
    update_post_meta($post_id, '_quote_clinic_id', $clinic_id);
    update_post_meta($post_id, '_quote_status', 'pending');
    update_post_meta($post_id, '_quote_ip_address', $_SERVER['REMOTE_ADDR']);

    // Send emails
    str_send_quote_emails($post_id);

    wp_send_json_success(array('message' => 'Quote request submitted successfully!'));
}
add_action('wp_ajax_submit_quote_request', 'str_handle_quote_submission');
add_action('wp_ajax_nopriv_submit_quote_request', 'str_handle_quote_submission');

/**
 * Send Quote Notification Emails
 */
function str_send_quote_emails($post_id) {
    $name = get_post_meta($post_id, '_quote_name', true);
    $email = get_post_meta($post_id, '_quote_email', true);
    $phone = get_post_meta($post_id, '_quote_phone', true);
    $location = get_post_meta($post_id, '_quote_location', true);
    $tattoo_size = get_post_meta($post_id, '_quote_tattoo_size', true);
    $clinic_id = get_post_meta($post_id, '_quote_clinic_id', true);

    // Email to admin
    $admin_email = get_option('admin_email');
    $admin_subject = 'New Quote Request - ' . get_bloginfo('name');
    $admin_message = "New quote request received:\n\n";
    $admin_message .= "Name: {$name}\n";
    $admin_message .= "Email: {$email}\n";
    $admin_message .= "Phone: {$phone}\n";
    $admin_message .= "Location: {$location}\n";
    $admin_message .= "Tattoo Size: {$tattoo_size}\n";
    if ($clinic_id) {
        $admin_message .= "Clinic: " . get_the_title($clinic_id) . "\n";
    }
    $admin_message .= "\nView in admin: " . admin_url('post.php?post=' . $post_id . '&action=edit');

    wp_mail($admin_email, $admin_subject, $admin_message);

    // Email to customer
    $customer_subject = 'Quote Request Received - ' . get_bloginfo('name');
    $customer_message = "Dear {$name},\n\n";
    $customer_message .= "Thank you for your quote request. We have received your information and will contact you shortly.\n\n";
    $customer_message .= "Your Request Details:\n";
    $customer_message .= "Location: {$location}\n";
    $customer_message .= "Tattoo Size: {$tattoo_size}\n\n";
    $customer_message .= "We typically respond within 24 hours.\n\n";
    $customer_message .= "Best regards,\n" . get_bloginfo('name');

    wp_mail($email, $customer_subject, $customer_message);
}
