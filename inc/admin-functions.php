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

/**
 * Add Sample Data Generator to Tools menu
 */
function str_add_sample_data_menu() {
    add_management_page(
        __('Sample Clinic Data', 'search-tattoo-removal'),
        __('Sample Clinic Data', 'search-tattoo-removal'),
        'manage_options',
        'sample-clinic-data',
        'str_sample_clinic_data_page'
    );
}
add_action('admin_menu', 'str_add_sample_data_menu');

/**
 * Sample Clinic Data Generator Page
 */
function str_sample_clinic_data_page() {
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    
    // Handle form submission
    $generated = false;
    if (isset($_POST['generate_sample_data']) && check_admin_referer('str_sample_data', 'str_sample_data_nonce')) {
        $generated = str_generate_sample_clinics();
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php if ($generated) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php _e('Success!', 'search-tattoo-removal'); ?></strong> <?php echo sprintf(__('%d sample clinics have been created.', 'search-tattoo-removal'), count($generated)); ?></p>
                <p><a href="<?php echo admin_url('edit.php?post_type=clinic'); ?>" class="button button-primary"><?php _e('View Clinics', 'search-tattoo-removal'); ?></a></p>
            </div>
        <?php endif; ?>
        
        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Generate Sample Clinic Data', 'search-tattoo-removal'); ?></h2>
            <p><?php _e('This tool will create 5 sample clinic posts with complete data including:', 'search-tattoo-removal'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('Full clinic information (title, description)', 'search-tattoo-removal'); ?></li>
                <li><?php _e('All meta fields (rating, reviews, contact info, pricing)', 'search-tattoo-removal'); ?></li>
                <li><?php _e('Location taxonomies (states and cities with acronyms)', 'search-tattoo-removal'); ?></li>
                <li><?php _e('Clinic features taxonomy terms', 'search-tattoo-removal'); ?></li>
            </ul>
            
            <h3><?php _e('Sample Clinics:', 'search-tattoo-removal'); ?></h3>
            <ol>
                <li><strong>Miami Ink Erasers</strong> - Miami, FL</li>
                <li><strong>Clear Skin LA</strong> - Los Angeles, CA</li>
                <li><strong>Fresh Start Tattoo Removal NYC</strong> - New York, NY</li>
                <li><strong>Lone Star Laser Clinic</strong> - Houston, TX</li>
                <li><strong>Phoenix Skin Renewal</strong> - Phoenix, AZ</li>
            </ol>
            
            <form method="post" action="">
                <?php wp_nonce_field('str_sample_data', 'str_sample_data_nonce'); ?>
                <p>
                    <button type="submit" name="generate_sample_data" class="button button-primary button-large">
                        <?php _e('Generate Sample Clinics', 'search-tattoo-removal'); ?>
                    </button>
                </p>
            </form>
            
            <p class="description">
                <?php _e('Note: Running this multiple times will create duplicate clinics. You can delete them manually from the Clinics list.', 'search-tattoo-removal'); ?>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Generate sample clinic data
 */
function str_generate_sample_clinics() {
    $sample_clinics = array(
        array(
            'title' => 'Miami Ink Erasers',
            'content' => 'Miami Ink Erasers is a premier tattoo removal facility specializing in safe, effective laser tattoo removal treatments. Our state-of-the-art clinic uses the latest PicoWay and Enlighten III laser technology to ensure optimal results with minimal discomfort. With over 15 years of combined experience, our certified practitioners are dedicated to helping you achieve clear, tattoo-free skin. We offer free consultations to assess your tattoo and create a personalized treatment plan tailored to your specific needs.',
            'state' => 'Florida',
            'city' => 'Miami',
            'meta' => array(
                '_clinic_rating' => '4.8',
                '_clinic_reviews_count' => '215',
                '_clinic_phone' => '(305) 555-0177',
                '_clinic_city' => 'Miami',
                '_clinic_state' => 'FL',
                '_clinic_website' => 'https://miamiinkerasers.com',
                '_clinic_is_verified' => '1',
                '_clinic_open_status' => 'Open Now',
                '_clinic_street' => '321 Ocean Drive',
                '_clinic_zip_code' => '33139',
                '_clinic_full_address' => "321 Ocean Drive\nMiami, FL 33139",
                '_clinic_operating_hours_raw' => "Mon-Fri: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed",
                '_clinic_min_price' => '150',
                '_clinic_max_price' => '350',
                '_clinic_consultation_price' => '0',
                '_clinic_price_range_display' => '$150-$350',
                '_clinic_years_in_business' => '8',
                '_clinic_is_featured' => '0',
                '_clinic_reviews_summary' => 'Patients consistently praise this clinic for its exceptional cleanliness and professional staff. The majority of reviewers highlight the effectiveness of their laser technology, specifically noting faster fading times compared to previous experiences. While some mention the premium pricing, the consensus is that the high safety standards and medical supervision provide peace of mind that justifies the investment.',
            ),
            'features' => array('Free Initial Consultation', 'Certified Practitioners', 'Latest Laser Technology', 'Safe for All Skin Tones'),
        ),
        array(
            'title' => 'Clear Skin LA',
            'content' => 'Clear Skin LA is Los Angeles\' trusted name in professional tattoo removal. Our medical-grade facility operates under the supervision of board-certified dermatologists, ensuring the highest standards of safety and efficacy. We use cutting-edge PicoSure lasers that can target even the most stubborn ink colors including blues, greens, and fluorescent pigments. Our compassionate team understands the personal journey of tattoo removal and provides a comfortable, judgment-free environment.',
            'state' => 'California',
            'city' => 'Los Angeles',
            'meta' => array(
                '_clinic_rating' => '4.9',
                '_clinic_reviews_count' => '342',
                '_clinic_phone' => '(310) 555-0198',
                '_clinic_city' => 'Los Angeles',
                '_clinic_state' => 'CA',
                '_clinic_website' => 'https://clearskinsla.com',
                '_clinic_is_verified' => '1',
                '_clinic_open_status' => 'Open Now',
                '_clinic_street' => '8500 Wilshire Boulevard',
                '_clinic_zip_code' => '90211',
                '_clinic_full_address' => "8500 Wilshire Boulevard, Suite 920\nBeverly Hills, CA 90211",
                '_clinic_operating_hours_raw' => "Monday: 8:00 AM - 7:00 PM\nTuesday: 8:00 AM - 7:00 PM\nWednesday: 8:00 AM - 7:00 PM\nThursday: 8:00 AM - 7:00 PM\nFriday: 8:00 AM - 7:00 PM\nSaturday: 9:00 AM - 5:00 PM\nSunday: Closed",
                '_clinic_min_price' => '200',
                '_clinic_max_price' => '450',
                '_clinic_consultation_price' => '50',
                '_clinic_price_range_display' => '$200-$450',
                '_clinic_years_in_business' => '12',
                '_clinic_is_featured' => '0',
                '_clinic_reviews_summary' => 'Reviewers love the luxurious environment and attention to detail at this facility. Many note the dermatologist supervision as a key differentiator, with several mentioning successful treatment of stubborn colors like green and blue. The staff receives high marks for compassion and understanding throughout the removal journey.',
            ),
            'features' => array('Board Certified Dermatologist', 'Medical-Grade Facility', 'PicoSure Technology', 'Flexible Payment Plans'),
        ),
        array(
            'title' => 'Fresh Start Tattoo Removal NYC',
            'content' => 'Located in the heart of Manhattan, Fresh Start Tattoo Removal NYC offers premium laser tattoo removal services in a luxurious, spa-like environment. Our team of certified laser specialists has successfully treated thousands of clients, helping them move forward with confidence. We specialize in complete tattoo removal as well as selective fading for cover-up work. Our advanced Q-switched Nd:YAG lasers are safe for all skin types and can effectively treat both professional and amateur tattoos.',
            'state' => 'New York',
            'city' => 'New York',
            'meta' => array(
                '_clinic_rating' => '4.7',
                '_clinic_reviews_count' => '489',
                '_clinic_phone' => '(212) 555-0143',
                '_clinic_city' => 'New York',
                '_clinic_state' => 'NY',
                '_clinic_website' => 'https://freshstartnyc.com',
                '_clinic_is_verified' => '1',
                '_clinic_open_status' => 'Open Now',
                '_clinic_street' => '450 Park Avenue',
                '_clinic_zip_code' => '10022',
                '_clinic_full_address' => "450 Park Avenue, 15th Floor\nNew York, NY 10022",
                '_clinic_operating_hours_raw' => "Mon-Fri: 7:00 AM - 8:00 PM\nSaturday: 9:00 AM - 6:00 PM\nSunday: 10:00 AM - 4:00 PM",
                '_clinic_min_price' => '175',
                '_clinic_max_price' => '400',
                '_clinic_consultation_price' => '0',
                '_clinic_price_range_display' => '$175-$400',
                '_clinic_years_in_business' => '10',
                '_clinic_is_featured' => '0',
                '_clinic_reviews_summary' => 'Clients appreciate the spa-like atmosphere and professionalism of the Manhattan location. The expertise in cover-up fading is frequently mentioned, with tattoo artists recommending this clinic to their own clients. Most reviews highlight the minimal discomfort during treatments and impressive results on all skin tones.',
            ),
            'features' => array('Luxury Spa Environment', 'Expert Laser Specialists', 'Cover-Up Fading', 'Safe for All Skin Types'),
        ),
        array(
            'title' => 'Lone Star Laser Clinic',
            'content' => 'Lone Star Laser Clinic brings world-class tattoo removal expertise to Houston, Texas. Our clinic is equipped with the most advanced laser systems available, including the revolutionary PicoWay Resolve. We pride ourselves on transparent pricing, detailed treatment plans, and exceptional patient care. Whether you have a small symbol or large sleeve, our experienced technicians will guide you through every step of your removal journey.',
            'state' => 'Texas',
            'city' => 'Houston',
            'meta' => array(
                '_rating' => '4.6',
                '_reviews_count' => '178',
                '_phone' => '(713) 555-0162',
                '_city' => 'Houston',
                '_state' => 'TX',
                '_website' => 'https://lonestarlaser.com',
                '_is_verified' => '1',
                '_open_status' => 'Open Now',
                '_street' => '2855 Gramercy Street',
                '_zip_code' => '77025',
                '_full_address' => "2855 Gramercy Street\nHouston, TX 77025",
                '_operating_hours_raw' => "Mon-Thu: 9:00 AM - 6:00 PM\nFriday: 9:00 AM - 5:00 PM\nSat-Sun: Closed",
                '_min_price' => '125',
                '_max_price' => '300',
                '_consultation_price' => '0',
                '_price_range_display' => '$125-$300',
                '_years_in_business' => '6',
                '_is_featured' => '0',
                '_reviews_summary' => 'Customers value the transparent pricing structure and detailed consultations provided by this Houston clinic. Many appreciate the upfront communication about expected number of sessions and realistic timelines. The PicoWay technology receives praise for achieving noticeable fading even after the first treatment.',
            ),
            'features' => array('Transparent Pricing', 'PicoWay Technology', 'Detailed Treatment Plans', 'Experienced Technicians'),
        ),
        array(
            'title' => 'Phoenix Skin Renewal',
            'content' => 'Phoenix Skin Renewal is Arizona\'s leading tattoo removal specialist, offering comprehensive laser treatments in a modern, clinical setting. Our practice is led by Dr. Sarah Martinez, a board-certified dermatologist with specialized training in laser procedures. We use multiple laser wavelengths to effectively target all ink colors and achieve optimal clearance rates. Patient education and comfort are our top priorities.',
            'state' => 'Arizona',
            'city' => 'Phoenix',
            'meta' => array(
                '_clinic_rating' => '4.9',
                '_clinic_reviews_count' => '267',
                '_clinic_phone' => '(602) 555-0189',
                '_clinic_city' => 'Phoenix',
                '_clinic_state' => 'AZ',
                '_clinic_website' => 'https://phoenixskinrenewal.com',
                '_clinic_is_verified' => '1',
                '_clinic_open_status' => 'Open Now',
                '_clinic_street' => '5340 East Camelback Road',
                '_clinic_zip_code' => '85018',
                '_clinic_full_address' => "5340 East Camelback Road, Suite 190\nPhoenix, AZ 85018",
                '_clinic_operating_hours_raw' => "Monday: 8:30 AM - 5:00 PM\nTuesday: 8:30 AM - 5:00 PM\nWednesday: 8:30 AM - 6:00 PM\nThursday: 8:30 AM - 5:00 PM\nFriday: 8:30 AM - 3:00 PM\nSat-Sun: Closed",
                '_clinic_min_price' => '180',
                '_clinic_max_price' => '380',
                '_clinic_consultation_price' => '75',
                '_clinic_price_range_display' => '$180-$380',
                '_clinic_years_in_business' => '9',
                '_clinic_is_featured' => '0',
                '_clinic_reviews_summary' => 'Patients commend Dr. Martinez for her thorough explanations and educational approach to tattoo removal. The practice receives excellent marks for using multiple wavelengths tailored to specific ink colors. Reviews consistently mention feeling well-informed and confident throughout the entire treatment process.',
            ),
            'features' => array('Dermatologist-Led Practice', 'Multiple Laser Wavelengths', 'Patient Education Focus', 'Modern Clinical Setting'),
        ),
    );
    
    $created_ids = array();
    
    foreach ($sample_clinics as $clinic_data) {
        // Create the post
        $post_id = wp_insert_post(array(
            'post_title' => $clinic_data['title'],
            'post_content' => $clinic_data['content'],
            'post_status' => 'publish',
            'post_type' => 'clinic',
            'post_author' => get_current_user_id(),
        ));
        
        if (is_wp_error($post_id)) {
            continue;
        }
        
        // Add meta fields
        foreach ($clinic_data['meta'] as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value);
        }
        
        // Get or create state term
        $state_term = term_exists($clinic_data['state'], 'us_location');
        if (!$state_term) {
            $state_term = wp_insert_term($clinic_data['state'], 'us_location');
        }
        $state_term_id = is_array($state_term) ? $state_term['term_id'] : $state_term;
        
        // Add state acronym if not set
        $state_acronyms = array(
            'Florida' => 'FL',
            'California' => 'CA',
            'New York' => 'NY',
            'Texas' => 'TX',
            'Arizona' => 'AZ',
        );
        if (isset($state_acronyms[$clinic_data['state']])) {
            $existing_acronym = get_term_meta($state_term_id, 'us_location_acronym', true);
            if (empty($existing_acronym)) {
                update_term_meta($state_term_id, 'us_location_acronym', $state_acronyms[$clinic_data['state']]);
            }
        }
        
        // Get or create city term
        $city_term = term_exists($clinic_data['city'], 'us_location', $state_term_id);
        if (!$city_term) {
            $city_term = wp_insert_term($clinic_data['city'], 'us_location', array('parent' => $state_term_id));
        }
        $city_term_id = is_array($city_term) ? $city_term['term_id'] : $city_term;
        
        // Assign location taxonomy
        wp_set_post_terms($post_id, array($city_term_id), 'us_location');
        
        // Add clinic features
        if (!empty($clinic_data['features'])) {
            $feature_ids = array();
            foreach ($clinic_data['features'] as $feature_name) {
                $feature_term = term_exists($feature_name, 'clinic_feature');
                if (!$feature_term) {
                    $feature_term = wp_insert_term($feature_name, 'clinic_feature');
                }
                
                // Check for WP_Error
                if (is_wp_error($feature_term)) {
                    continue;
                }
                
                // Extract term ID properly
                $term_id = is_array($feature_term) ? $feature_term['term_id'] : $feature_term;
                if ($term_id) {
                    $feature_ids[] = $term_id;
                }
            }
            
            // Only set terms if we have valid IDs
            if (!empty($feature_ids)) {
                wp_set_post_terms($post_id, $feature_ids, 'clinic_feature');
            }
        }
        
        $created_ids[] = $post_id;
    }
    
    return $created_ids;
}

/**
 * Add Laser Tech Import submenu
 */
function str_add_laser_tech_import_menu() {
    add_submenu_page(
        'edit.php?post_type=laser_tech',
        __('Import Laser Technologies', 'search-tattoo-removal'),
        __('Import', 'search-tattoo-removal'),
        'manage_options',
        'laser-tech-importer',
        'str_laser_tech_import_page'
    );
}
add_action('admin_menu', 'str_add_laser_tech_import_menu');

/**
 * Laser Tech Import Page
 */
function str_laser_tech_import_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Import Laser Technologies from CSV', 'search-tattoo-removal'); ?></h1>
        
        <?php if (isset($_GET['import_success'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php echo intval($_GET['import_success']); ?> laser technologies imported successfully!</strong></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['import_error'])) : ?>
            <div class="notice notice-error is-dismissible">
                <p><strong>Error:</strong> <?php echo esc_html(urldecode($_GET['import_error'])); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Upload CSV File', 'search-tattoo-removal'); ?></h2>
            
            <form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="str_import_laser_tech">
                <?php wp_nonce_field('str_import_laser_tech', 'str_laser_tech_import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="laser_tech_file"><?php _e('Select CSV File', 'search-tattoo-removal'); ?></label>
                        </th>
                        <td>
                            <input type="file" name="laser_tech_file" id="laser_tech_file" accept=".csv" required>
                            <p class="description">
                                <?php _e('Upload a CSV file (.csv) with laser technology data.', 'search-tattoo-removal'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="import_mode"><?php _e('Import Mode', 'search-tattoo-removal'); ?></label>
                        </th>
                        <td>
                            <select name="import_mode" id="import_mode">
                                <option value="create"><?php _e('Create new technologies only', 'search-tattoo-removal'); ?></option>
                                <option value="update"><?php _e('Update existing (match by title)', 'search-tattoo-removal'); ?></option>
                                <option value="overwrite"><?php _e('Create or Update (overwrite existing)', 'search-tattoo-removal'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Import Laser Technologies', 'search-tattoo-removal'); ?>">
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Download Template', 'search-tattoo-removal'); ?></h2>
            <p><?php _e('Download the CSV template file with all required columns:', 'search-tattoo-removal'); ?></p>
            <a href="<?php echo admin_url('admin-post.php?action=str_download_laser_tech_template'); ?>" class="button button-secondary">
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
                        <td>Technology name (required)</td>
                        <td>PicoWay Laser System</td>
                    </tr>
                    <tr>
                        <td><strong>content</strong></td>
                        <td>Full technology description</td>
                        <td>Revolutionary picosecond laser technology for effective tattoo removal...</td>
                    </tr>
                    <tr>
                        <td><strong>official_website</strong></td>
                        <td>Official technology website URL</td>
                        <td>https://www.picoway.com</td>
                    </tr>
                    <tr>
                        <td><strong>short_description</strong></td>
                        <td>Brief technology summary</td>
                        <td>Picosecond laser for all skin types</td>
                    </tr>
                    <tr>
                        <td><strong>technical_notes</strong></td>
                        <td>Technical specifications and notes</td>
                        <td>755nm, 1064nm, 532nm wavelengths. Safe for all skin types.</td>
                    </tr>
                    <tr>
                        <td><strong>laser_brand</strong></td>
                        <td>Laser brand/manufacturer (comma-separated for multiple)</td>
                        <td>Candela, Syneron Candela</td>
                    </tr>
                    <tr>
                        <td><strong>laser_wavelength</strong></td>
                        <td>Available wavelengths (comma-separated)</td>
                        <td>532nm, 755nm, 1064nm</td>
                    </tr>
                    <tr>
                        <td><strong>laser_pulse_type</strong></td>
                        <td>Pulse duration types (comma-separated)</td>
                        <td>Picosecond, Nanosecond</td>
                    </tr>
                    <tr>
                        <td><strong>target_ink_color</strong></td>
                        <td>Target ink colors (comma-separated)</td>
                        <td>Black, Blue, Green, Red, Yellow</td>
                    </tr>
                    <tr>
                        <td><strong>safe_skin_type</strong></td>
                        <td>Safe for skin types (comma-separated)</td>
                        <td>Type I, Type II, Type III, Type IV, Type V, Type VI</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * Process laser tech import
 */
function str_process_laser_tech_import() {
    // Verify nonce and permissions
    if (!isset($_POST['str_laser_tech_import_nonce']) || !wp_verify_nonce($_POST['str_laser_tech_import_nonce'], 'str_import_laser_tech')) {
        wp_die(__('Security check failed', 'search-tattoo-removal'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(__('Insufficient permissions', 'search-tattoo-removal'));
    }

    // Check if file was uploaded
    if (!isset($_FILES['laser_tech_file']) || $_FILES['laser_tech_file']['error'] !== UPLOAD_ERR_OK) {
        wp_redirect(admin_url('edit.php?post_type=laser_tech&page=laser-tech-importer&import_error=' . urlencode('File upload failed')));
        exit;
    }

    $file = $_FILES['laser_tech_file'];
    $import_mode = isset($_POST['import_mode']) ? sanitize_text_field($_POST['import_mode']) : 'create';
    
    // Check file type
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    try {
        if ($file_ext === 'csv') {
            $imported = str_import_laser_tech_csv($file['tmp_name'], $import_mode);
        } else {
            throw new Exception('Only CSV files are supported.');
        }

        wp_redirect(admin_url('edit.php?post_type=laser_tech&page=laser-tech-importer&import_success=' . $imported));
        exit;
    } catch (Exception $e) {
        wp_redirect(admin_url('edit.php?post_type=laser_tech&page=laser-tech-importer&import_error=' . urlencode($e->getMessage())));
        exit;
    }
}
add_action('admin_post_str_import_laser_tech', 'str_process_laser_tech_import');

/**
 * Import laser technologies from CSV file
 */
function str_import_laser_tech_csv($file_path, $import_mode) {
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
            str_import_single_laser_tech($row, $import_mode);
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
 * Import a single laser technology from row data
 */
function str_import_single_laser_tech($row, $import_mode) {
    // Required field: title
    if (empty($row['title'])) {
        throw new Exception('Title is required');
    }

    $title = sanitize_text_field($row['title']);
    
    // Check if technology exists
    $existing = get_page_by_title($title, OBJECT, 'laser_tech');
    
    if ($existing && $import_mode === 'create') {
        // Skip if exists and mode is create only
        return;
    }

    // Prepare post data
    $post_data = array(
        'post_title'   => $title,
        'post_content' => isset($row['content']) ? wp_kses_post($row['content']) : '',
        'post_status'  => 'publish',
        'post_type'    => 'laser_tech',
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

    // Set meta fields
    $meta_fields = array(
        'official_website' => 'official_website',
        'short_description' => 'short_description',
        'technical_notes' => 'technical_notes',
    );

    foreach ($meta_fields as $csv_field => $meta_key) {
        if (isset($row[$csv_field]) && $row[$csv_field] !== '') {
            if ($meta_key === 'short_description' || $meta_key === 'technical_notes') {
                update_post_meta($post_id, '_' . $meta_key, sanitize_textarea_field($row[$csv_field]));
            } else {
                update_post_meta($post_id, '_' . $meta_key, sanitize_text_field($row[$csv_field]));
            }
        }
    }

    // Set taxonomies
    $taxonomies = array(
        'laser_brand',
        'laser_wavelength',
        'laser_pulse_type',
        'target_ink_color',
        'safe_skin_type',
    );

    foreach ($taxonomies as $taxonomy) {
        if (isset($row[$taxonomy]) && !empty($row[$taxonomy])) {
            // Split by comma for multiple terms
            $terms = array_map('trim', explode(',', $row[$taxonomy]));
            $term_names = array();

            foreach ($terms as $term_name) {
                if (empty($term_name)) continue;

                // Skip if term_name is purely numeric (likely import error)
                if (is_numeric($term_name) && !preg_match('/nm$|Type\s+[IVX]+/i', $term_name)) {
                    continue;
                }

                // Check if term exists
                $existing_term = term_exists($term_name, $taxonomy);

                if (!$existing_term) {
                    // Create the term if it doesn't exist
                    $result = wp_insert_term($term_name, $taxonomy);
                    
                    if (!is_wp_error($result)) {
                        $term_names[] = $term_name;
                    } else {
                        error_log("Failed to create term '$term_name' in taxonomy '$taxonomy': " . $result->get_error_message());
                    }
                } else {
                    // Term exists, add it to our list
                    $term_names[] = $term_name;
                }
            }

            // Set the terms for this post using term names (more reliable than IDs)
            if (!empty($term_names)) {
                wp_set_object_terms($post_id, $term_names, $taxonomy, false);
            }
        }
    }

    return $post_id;
}

/**
 * Download laser tech CSV template
 */
function str_download_laser_tech_template() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Insufficient permissions', 'search-tattoo-removal'));
    }

    $filename = 'laser-tech-import-template.csv';
    
    // Set headers for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Create CSV content
    $output = fopen('php://output', 'w');
    
    // Add headers
    $headers = array(
        'title',
        'content',
        'official_website',
        'short_description',
        'technical_notes',
        'laser_brand',
        'laser_wavelength',
        'laser_pulse_type',
        'target_ink_color',
        'safe_skin_type'
    );
    fputcsv($output, $headers);
    
    // Add sample data
    $sample_data = array(
        array(
            'PicoWay Laser System',
            'Revolutionary picosecond laser technology designed for effective tattoo removal. The PicoWay system delivers ultra-short pulses that shatter tattoo ink into tiny particles.',
            'https://www.picoway.com',
            'Picosecond laser for all skin types',
            '755nm, 1064nm, 532nm wavelengths. Safe for all skin types. Minimal downtime.',
            'Syneron Candela',
            '532nm, 755nm, 1064nm',
            'Picosecond',
            'Black, Blue, Green, Red, Yellow, Orange',
            'Type I, Type II, Type III, Type IV, Type V, Type VI'
        ),
        array(
            'Enlighten III',
            'Advanced picosecond and nanosecond laser platform for comprehensive tattoo removal treatments.',
            'https://www.cutera.com/enlighten-iii',
            'Dual-wavelength picosecond laser',
            '1064nm and 532nm wavelengths. Both picosecond and nanosecond pulse durations.',
            'Cutera',
            '532nm, 1064nm',
            'Picosecond, Nanosecond',
            'Black, Blue, Green, Red',
            'Type I, Type II, Type III, Type IV, Type V, Type VI'
        ),
        array(
            'Q-Switch Nd:YAG',
            'Traditional Q-switched laser technology for tattoo removal with proven effectiveness.',
            'https://example.com',
            'Classic Q-switched laser system',
            '1064nm and 532nm wavelengths. Nanosecond pulse duration. Suitable for dark inks.',
            'Various',
            '532nm, 1064nm',
            'Nanosecond',
            'Black, Blue, Red',
            'Type I, Type II, Type III, Type IV'
        )
    );
    
    foreach ($sample_data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}
add_action('admin_post_str_download_laser_tech_template', 'str_download_laser_tech_template');
