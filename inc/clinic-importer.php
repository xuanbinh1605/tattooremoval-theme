<?php
/**
 * Clinic Excel/CSV Importer
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Clinic Importer menu to admin
 */
function str_add_importer_menu() {
    add_submenu_page(
        'edit.php?post_type=clinic',
        __('Import Clinics', 'search-tattoo-removal'),
        __('Import from Excel', 'search-tattoo-removal'),
        'manage_options',
        'clinic-importer',
        'str_importer_page'
    );
}
add_action('admin_menu', 'str_add_importer_menu');

/**
 * Importer Admin Page
 */
function str_importer_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Import Clinics from Excel/CSV', 'search-tattoo-removal'); ?></h1>
        
        <?php if (isset($_GET['import_success'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php echo intval($_GET['import_success']); ?> clinics imported successfully!</strong></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['import_error'])) : ?>
            <div class="notice notice-error is-dismissible">
                <p><strong>Error:</strong> <?php echo esc_html(urldecode($_GET['import_error'])); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Upload Excel or CSV File', 'search-tattoo-removal'); ?></h2>
            
            <form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="str_import_clinics">
                <?php wp_nonce_field('str_import_clinics', 'str_import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="clinic_file"><?php _e('Select File', 'search-tattoo-removal'); ?></label>
                        </th>
                        <td>
                            <input type="file" name="clinic_file" id="clinic_file" accept=".csv,.xlsx,.xls" required>
                            <p class="description">
                                <?php _e('Upload a CSV file (.csv). Excel files (.xlsx, .xls) require additional PHP extensions.', 'search-tattoo-removal'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="import_mode"><?php _e('Import Mode', 'search-tattoo-removal'); ?></label>
                        </th>
                        <td>
                            <select name="import_mode" id="import_mode">
                                <option value="create"><?php _e('Create new clinics only', 'search-tattoo-removal'); ?></option>
                                <option value="update"><?php _e('Update existing (match by title)', 'search-tattoo-removal'); ?></option>
                                <option value="overwrite"><?php _e('Create or Update (overwrite existing)', 'search-tattoo-removal'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Import Clinics', 'search-tattoo-removal'); ?>">
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Download Template', 'search-tattoo-removal'); ?></h2>
            <p><?php _e('Download the CSV template file with all required columns:', 'search-tattoo-removal'); ?></p>
            <a href="<?php echo admin_url('admin-post.php?action=str_download_template'); ?>" class="button button-secondary">
                <?php _e('Download CSV Template', 'search-tattoo-removal'); ?>
            </a>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('CSV Column Guide', 'search-tattoo-removal'); ?></h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Column Name', 'search-tattoo-removal'); ?></th>
                        <th><?php _e('Description', 'search-tattoo-removal'); ?></th>
                        <th><?php _e('Example', 'search-tattoo-removal'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>title</strong></td>
                        <td>Clinic name (required)</td>
                        <td>Tattoo Gone LA</td>
                    </tr>
                    <tr>
                        <td><strong>content</strong></td>
                        <td>Full description</td>
                        <td>Professional tattoo removal services...</td>
                    </tr>
                    <tr>
                        <td><strong>state</strong></td>
                        <td>US State name (must exist in taxonomy)</td>
                        <td>California</td>
                    </tr>
                    <tr>
                        <td><strong>city</strong></td>
                        <td>City name (must exist under state)</td>
                        <td>Los Angeles</td>
                    </tr>
                    <tr>
                        <td><strong>street</strong></td>
                        <td>Street address</td>
                        <td>123 Main St, Suite 100</td>
                    </tr>
                    <tr>
                        <td><strong>zip_code</strong></td>
                        <td>ZIP code</td>
                        <td>90001</td>
                    </tr>
                    <tr>
                        <td><strong>phone</strong></td>
                        <td>Phone number</td>
                        <td>(555) 123-4567</td>
                    </tr>
                    <tr>
                        <td><strong>website</strong></td>
                        <td>Website URL</td>
                        <td>https://example.com</td>
                    </tr>
                    <tr>
                        <td><strong>google_maps_url</strong></td>
                        <td>Google Maps link</td>
                        <td>https://maps.google.com/...</td>
                    </tr>
                    <tr>
                        <td><strong>rating</strong></td>
                        <td>Rating (0-5)</td>
                        <td>4.5</td>
                    </tr>
                    <tr>
                        <td><strong>reviews_count</strong></td>
                        <td>Number of reviews</td>
                        <td>152</td>
                    </tr>
                    <tr>
                        <td><strong>reviews_summary</strong></td>
                        <td>Summary of what people say</td>
                        <td>Patients love the professional service...</td>
                    </tr>
                    <tr>
                        <td><strong>min_price</strong></td>
                        <td>Minimum price ($)</td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td><strong>max_price</strong></td>
                        <td>Maximum price ($)</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td><strong>consultation_price</strong></td>
                        <td>Consultation price text</td>
                        <td>Free</td>
                    </tr>
                    <tr>
                        <td><strong>price_range_display</strong></td>
                        <td>Display price text</td>
                        <td>$150 range</td>
                    </tr>
                    <tr>
                        <td><strong>operating_hours_raw</strong></td>
                        <td>Operating hours (multi-line ok)</td>
                        <td>Mon-Fri: 9AM-5PM</td>
                    </tr>
                    <tr>
                        <td><strong>open_status</strong></td>
                        <td>Current status text</td>
                        <td>Open Now</td>
                    </tr>
                    <tr>
                        <td><strong>years_in_business</strong></td>
                        <td>Years operating</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td><strong>is_verified</strong></td>
                        <td>1 for yes, 0 for no</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td><strong>is_featured</strong></td>
                        <td>1 for yes, 0 for no</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td><strong>logo</strong></td>
                        <td>Logo image URL</td>
                        <td>https://example.com/logo.png</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * Process the clinic import
 */
function str_process_clinic_import() {
    // Verify nonce and permissions
    if (!isset($_POST['str_import_nonce']) || !wp_verify_nonce($_POST['str_import_nonce'], 'str_import_clinics')) {
        wp_die(__('Security check failed', 'search-tattoo-removal'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(__('Insufficient permissions', 'search-tattoo-removal'));
    }

    // Check if file was uploaded
    if (!isset($_FILES['clinic_file']) || $_FILES['clinic_file']['error'] !== UPLOAD_ERR_OK) {
        wp_redirect(admin_url('edit.php?post_type=clinic&page=clinic-importer&import_error=' . urlencode('File upload failed')));
        exit;
    }

    $file = $_FILES['clinic_file'];
    $import_mode = isset($_POST['import_mode']) ? sanitize_text_field($_POST['import_mode']) : 'create';
    
    // Determine file type
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    try {
        if ($file_ext === 'csv') {
            $imported = str_import_csv($file['tmp_name'], $import_mode);
        } else {
            throw new Exception('Only CSV files are supported. Please convert your Excel file to CSV format.');
        }

        wp_redirect(admin_url('edit.php?post_type=clinic&page=clinic-importer&import_success=' . $imported));
        exit;
    } catch (Exception $e) {
        wp_redirect(admin_url('edit.php?post_type=clinic&page=clinic-importer&import_error=' . urlencode($e->getMessage())));
        exit;
    }
}
add_action('admin_post_str_import_clinics', 'str_process_clinic_import');

/**
 * Import clinics from CSV file
 */
function str_import_csv($file_path, $import_mode) {
    $handle = fopen($file_path, 'r');
    if ($handle === false) {
        throw new Exception('Could not open file');
    }

    // Read header row
    $headers = fgetcsv($handle);
    if ($headers === false) {
        fclose($handle);
        throw new Exception('Empty file or invalid format');
    }

    // Normalize headers (trim and lowercase)
    $headers = array_map(function($h) {
        return strtolower(trim($h));
    }, $headers);

    $imported = 0;
    $row_number = 1;

    // Process each row
    while (($data = fgetcsv($handle)) !== false) {
        $row_number++;
        
        // Skip empty rows
        if (empty(array_filter($data))) {
            continue;
        }

        // Combine headers with data
        $row = array_combine($headers, $data);
        
        try {
            str_import_single_clinic($row, $import_mode);
            $imported++;
        } catch (Exception $e) {
            // Log error but continue with next row
            error_log("Row $row_number import failed: " . $e->getMessage());
        }
    }

    fclose($handle);
    return $imported;
}

/**
 * Import a single clinic from row data
 */
function str_import_single_clinic($row, $import_mode) {
    // Required field: title
    if (empty($row['title'])) {
        throw new Exception('Title is required');
    }

    $title = sanitize_text_field($row['title']);
    
    // Check if clinic exists
    $existing = get_page_by_title($title, OBJECT, 'clinic');
    
    if ($existing && $import_mode === 'create') {
        // Skip if exists and mode is create only
        return;
    }

    // Prepare post data
    $post_data = array(
        'post_title'   => $title,
        'post_content' => isset($row['content']) ? wp_kses_post($row['content']) : '',
        'post_status'  => 'publish',
        'post_type'    => 'clinic',
    );

    if ($existing && ($import_mode === 'update' || $import_mode === 'overwrite')) {
        $post_data['ID'] = $existing->ID;
        $post_id = wp_update_post($post_data);
    } else {
        $post_id = wp_insert_post($post_data);
    }

    if (is_wp_error($post_id)) {
        throw new Exception('Failed to create/update post: ' . $post_id->get_error_message());
    }

    // Set taxonomy (US Location - State → City)
    if (!empty($row['state']) && !empty($row['city'])) {
        str_set_clinic_location($post_id, $row['state'], $row['city']);
    }

    // Set meta fields
    $meta_fields = array(
        'website' => 'website',
        'phone' => 'phone',
        'google_maps_url' => 'google_maps_url',
        'rating' => 'rating',
        'reviews_count' => 'reviews_count',
        'reviews_summary' => 'reviews_summary',
        'street' => 'street',
        'zip_code' => 'zip_code',
        'full_address' => 'full_address',
        'operating_hours_raw' => 'operating_hours_raw',
        'min_price' => 'min_price',
        'max_price' => 'max_price',
        'consultation_price' => 'consultation_price',
        'price_range_display' => 'price_range_display',
        'logo' => 'logo',
        'before_after_gallery' => 'before_after_gallery',
        'years_in_business' => 'years_in_business',
        'open_status' => 'open_status',
    );

    foreach ($meta_fields as $csv_field => $meta_key) {
        if (isset($row[$csv_field]) && $row[$csv_field] !== '') {
            update_post_meta($post_id, '_' . $meta_key, sanitize_text_field($row[$csv_field]));
        }
    }

    // Handle boolean fields
    if (isset($row['is_verified'])) {
        update_post_meta($post_id, '_is_verified', ($row['is_verified'] == '1' || strtolower($row['is_verified']) === 'yes') ? '1' : '0');
    }

    if (isset($row['is_featured'])) {
        update_post_meta($post_id, '_is_featured', ($row['is_featured'] == '1' || strtolower($row['is_featured']) === 'yes') ? '1' : '0');
    }

    return $post_id;
}

/**
 * Set clinic location taxonomy (State → City)
 */
function str_set_clinic_location($post_id, $state_name, $city_name) {
    $state_name = trim($state_name);
    $city_name = trim($city_name);

    // Find or create state (parent term)
    $state = get_term_by('name', $state_name, 'us_location');
    if (!$state) {
        $state_result = wp_insert_term($state_name, 'us_location', array('parent' => 0));
        if (is_wp_error($state_result)) {
            error_log('Failed to create state: ' . $state_result->get_error_message());
            return;
        }
        $state_id = $state_result['term_id'];
    } else {
        $state_id = $state->term_id;
    }

    // Find or create city (child term)
    $city = get_term_by('name', $city_name, 'us_location');
    
    // Check if city exists under this specific state
    if ($city && $city->parent != $state_id) {
        // City exists but under different state, create new one
        $city = false;
    }

    if (!$city) {
        $city_result = wp_insert_term($city_name, 'us_location', array('parent' => $state_id));
        if (is_wp_error($city_result)) {
            error_log('Failed to create city: ' . $city_result->get_error_message());
            return;
        }
        $city_id = $city_result['term_id'];
    } else {
        $city_id = $city->term_id;
    }

    // Set the taxonomy term (only the city, as it implies the state)
    wp_set_object_terms($post_id, array($city_id), 'us_location', false);
}

/**
 * Download CSV template
 */
function str_download_template() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Insufficient permissions', 'search-tattoo-removal'));
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=clinic-import-template.csv');
    
    $output = fopen('php://output', 'w');
    
    // CSV Headers
    $headers = array(
        'title', 'content', 'state', 'city', 'street', 'zip_code', 'phone', 
        'website', 'google_maps_url', 'rating', 'reviews_count', 'reviews_summary',
        'min_price', 'max_price', 'consultation_price', 'price_range_display',
        'operating_hours_raw', 'open_status', 'years_in_business', 'is_verified', 
        'is_featured', 'logo'
    );
    
    fputcsv($output, $headers);
    
    // Sample row
    $sample = array(
        'Tattoo Gone LA',
        'Professional tattoo removal services using advanced laser technology.',
        'California',
        'Los Angeles',
        '123 Main St, Suite 100',
        '90001',
        '(555) 123-4567',
        'https://example.com',
        'https://maps.google.com/...',
        '4.5',
        '152',
        'Patients love the professional service and effective results',
        '100',
        '500',
        'Free',
        '$150 range',
        'Mon-Fri: 9AM-5PM, Sat: 10AM-3PM',
        'Open Now',
        '15',
        '1',
        '0',
        'https://example.com/logo.png'
    );
    
    fputcsv($output, $sample);
    
    fclose($output);
    exit;
}
add_action('admin_post_str_download_template', 'str_download_template');
