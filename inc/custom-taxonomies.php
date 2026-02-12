<?php
/**
 * Register Custom Taxonomies
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register US Location Taxonomy (State → City hierarchy)
 * CRITICAL: This is the ONLY location taxonomy - hierarchical structure
 */
function str_register_us_location_taxonomy() {
    $labels = array(
        'name'                       => _x('US Locations', 'Taxonomy General Name', 'search-tattoo-removal'),
        'singular_name'              => _x('US Location', 'Taxonomy Singular Name', 'search-tattoo-removal'),
        'menu_name'                  => __('US Locations', 'search-tattoo-removal'),
        'all_items'                  => __('All Locations', 'search-tattoo-removal'),
        'parent_item'                => __('Parent Location (State)', 'search-tattoo-removal'),
        'parent_item_colon'          => __('Parent State:', 'search-tattoo-removal'),
        'new_item_name'              => __('New Location Name', 'search-tattoo-removal'),
        'add_new_item'               => __('Add New Location', 'search-tattoo-removal'),
        'edit_item'                  => __('Edit Location', 'search-tattoo-removal'),
        'update_item'                => __('Update Location', 'search-tattoo-removal'),
        'view_item'                  => __('View Location', 'search-tattoo-removal'),
        'separate_items_with_commas' => __('Separate locations with commas', 'search-tattoo-removal'),
        'add_or_remove_items'        => __('Add or remove locations', 'search-tattoo-removal'),
        'choose_from_most_used'      => __('Choose from the most used', 'search-tattoo-removal'),
        'popular_items'              => __('Popular Locations', 'search-tattoo-removal'),
        'search_items'               => __('Search Locations', 'search-tattoo-removal'),
        'not_found'                  => __('Not Found', 'search-tattoo-removal'),
        'no_terms'                   => __('No locations', 'search-tattoo-removal'),
        'items_list'                 => __('Locations list', 'search-tattoo-removal'),
        'items_list_navigation'      => __('Locations list navigation', 'search-tattoo-removal'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
        'rest_base'         => 'us-locations',
        'rewrite'           => array('slug' => 'us-location', 'with_front' => false, 'hierarchical' => true),
        'meta_box_cb'       => 'str_us_location_meta_box',
    );

    register_taxonomy('us_location', array('clinic'), $args);
}
add_action('init', 'str_register_us_location_taxonomy', 0);

/**
 * Custom meta box for US Location to ensure only cities are selected
 */
function str_us_location_meta_box($post) {
    $terms = get_terms(array(
        'taxonomy'   => 'us_location',
        'hide_empty' => false,
        'parent'     => 0,
    ));
    
    $selected = wp_get_object_terms($post->ID, 'us_location', array('fields' => 'ids'));
    
    echo '<div id="taxonomy-us_location" class="categorydiv">';
    echo '<p><strong>Select ONE city (State → City)</strong></p>';
    
    foreach ($terms as $state) {
        echo '<div style="margin-bottom: 15px;">';
        echo '<strong>' . esc_html($state->name) . '</strong>';
        
        $cities = get_terms(array(
            'taxonomy'   => 'us_location',
            'hide_empty' => false,
            'parent'     => $state->term_id,
        ));
        
        if ($cities) {
            echo '<ul style="margin-left: 20px;">';
            foreach ($cities as $city) {
                $checked = in_array($city->term_id, $selected) ? 'checked' : '';
                echo '<li>';
                echo '<label>';
                echo '<input type="radio" name="tax_input[us_location][]" value="' . $city->term_id . '" ' . $checked . '> ';
                echo esc_html($city->name);
                echo '</label>';
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }
    echo '</div>';
}

/**
 * Register Clinic Feature Taxonomy (replaces boolean options)
 */
function str_register_clinic_feature_taxonomy() {
    $labels = array(
        'name'                       => _x('Clinic Features', 'Taxonomy General Name', 'search-tattoo-removal'),
        'singular_name'              => _x('Clinic Feature', 'Taxonomy Singular Name', 'search-tattoo-removal'),
        'menu_name'                  => __('Features', 'search-tattoo-removal'),
        'all_items'                  => __('All Features', 'search-tattoo-removal'),
        'new_item_name'              => __('New Feature Name', 'search-tattoo-removal'),
        'add_new_item'               => __('Add New Feature', 'search-tattoo-removal'),
        'edit_item'                  => __('Edit Feature', 'search-tattoo-removal'),
        'update_item'                => __('Update Feature', 'search-tattoo-removal'),
        'view_item'                  => __('View Feature', 'search-tattoo-removal'),
        'separate_items_with_commas' => __('Separate features with commas', 'search-tattoo-removal'),
        'add_or_remove_items'        => __('Add or remove features', 'search-tattoo-removal'),
        'choose_from_most_used'      => __('Choose from the most used', 'search-tattoo-removal'),
        'popular_items'              => __('Popular Features', 'search-tattoo-removal'),
        'search_items'               => __('Search Features', 'search-tattoo-removal'),
        'not_found'                  => __('Not Found', 'search-tattoo-removal'),
        'no_terms'                   => __('No features', 'search-tattoo-removal'),
        'items_list'                 => __('Features list', 'search-tattoo-removal'),
        'items_list_navigation'      => __('Features list navigation', 'search-tattoo-removal'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rest_base'         => 'clinic-features',
        'rewrite'           => array('slug' => 'feature', 'with_front' => false),
    );

    register_taxonomy('clinic_feature', array('clinic'), $args);
}
add_action('init', 'str_register_clinic_feature_taxonomy', 0);

/**
 * Register Laser Technology Taxonomies
 */
function str_register_laser_taxonomies() {
    // Laser Brand
    register_taxonomy('laser_brand', array('laser_tech'), array(
        'label'             => __('Laser Brands', 'search-tattoo-removal'),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'laser-brand'),
    ));

    // Laser Wavelength
    register_taxonomy('laser_wavelength', array('laser_tech'), array(
        'label'             => __('Laser Wavelengths', 'search-tattoo-removal'),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'wavelength'),
    ));

    // Pulse Type
    register_taxonomy('laser_pulse_type', array('laser_tech'), array(
        'label'             => __('Pulse Types', 'search-tattoo-removal'),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'pulse-type'),
    ));

    // Target Ink Color
    register_taxonomy('target_ink_color', array('laser_tech'), array(
        'label'             => __('Target Ink Colors', 'search-tattoo-removal'),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'ink-color'),
    ));

    // Safe Skin Type
    register_taxonomy('safe_skin_type', array('laser_tech'), array(
        'label'             => __('Safe Skin Types', 'search-tattoo-removal'),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'skin-type'),
    ));
}
add_action('init', 'str_register_laser_taxonomies', 0);

/**
 * Register Laser Technology Taxonomy (shared between clinics and laser_tech posts)
 */
function str_register_laser_technology_taxonomy() {
    $labels = array(
        'name'                       => _x('Laser Technologies', 'Taxonomy General Name', 'search-tattoo-removal'),
        'singular_name'              => _x('Laser Technology', 'Taxonomy Singular Name', 'search-tattoo-removal'),
        'menu_name'                  => __('Technologies', 'search-tattoo-removal'),
        'all_items'                  => __('All Technologies', 'search-tattoo-removal'),
        'new_item_name'              => __('New Technology Name', 'search-tattoo-removal'),
        'add_new_item'               => __('Add New Technology', 'search-tattoo-removal'),
        'edit_item'                  => __('Edit Technology', 'search-tattoo-removal'),
        'update_item'                => __('Update Technology', 'search-tattoo-removal'),
        'view_item'                  => __('View Technology', 'search-tattoo-removal'),
        'separate_items_with_commas' => __('Separate technologies with commas', 'search-tattoo-removal'),
        'add_or_remove_items'        => __('Add or remove technologies', 'search-tattoo-removal'),
        'choose_from_most_used'      => __('Choose from the most used', 'search-tattoo-removal'),
        'popular_items'              => __('Popular Technologies', 'search-tattoo-removal'),
        'search_items'               => __('Search Technologies', 'search-tattoo-removal'),
        'not_found'                  => __('Not Found', 'search-tattoo-removal'),
        'no_terms'                   => __('No technologies', 'search-tattoo-removal'),
        'items_list'                 => __('Technologies list', 'search-tattoo-removal'),
        'items_list_navigation'      => __('Technologies list navigation', 'search-tattoo-removal'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rest_base'         => 'laser-technologies',
        'rewrite'           => array('slug' => 'laser-technology', 'with_front' => false),
    );

    register_taxonomy('laser_technology', array('laser_tech'), $args);
}
add_action('init', 'str_register_laser_technology_taxonomy', 0);

/**
 * Pre-populate clinic features on theme activation
 */
function str_prepopulate_clinic_features() {
    $features = array(
        // Scheduling
        array('name' => 'Appointment Required', 'slug' => 'appointment-required', 'group' => 'scheduling'),
        array('name' => 'Online Scheduling', 'slug' => 'online-scheduling', 'group' => 'scheduling'),
        
        // Pricing
        array('name' => 'Offers Packages', 'slug' => 'offers-packages', 'group' => 'pricing'),
        array('name' => 'Military Discount', 'slug' => 'military-discount', 'group' => 'pricing'),
        array('name' => 'Financing Available', 'slug' => 'financing', 'group' => 'pricing'),
        
        // Payments
        array('name' => 'Cash Only', 'slug' => 'cash-only', 'group' => 'payments'),
        array('name' => 'Accepts Credit Cards', 'slug' => 'accepts-credit-cards', 'group' => 'payments'),
        array('name' => 'Accepts Debit Cards', 'slug' => 'accepts-debit-cards', 'group' => 'payments'),
        array('name' => 'Accepts Mobile Payments', 'slug' => 'accepts-mobile-payments', 'group' => 'payments'),
        array('name' => 'Accepts Checks', 'slug' => 'accepts-checks', 'group' => 'payments'),
        
        // Accessibility
        array('name' => 'Wheelchair Accessible', 'slug' => 'wheelchair-accessible', 'group' => 'accessibility'),
        
        // Medical
        array('name' => 'Medical Supervision', 'slug' => 'medical-supervision', 'group' => 'medical'),
        
        // Consultations
        array('name' => 'Free Consultations', 'slug' => 'free-consultations', 'group' => 'consultations'),
        
        // Certification
        array('name' => 'Certified Techs', 'slug' => 'certified-techs', 'group' => 'certification'),
    );

    foreach ($features as $feature) {
        if (!term_exists($feature['slug'], 'clinic_feature')) {
            $term = wp_insert_term($feature['name'], 'clinic_feature', array('slug' => $feature['slug']));
            if (!is_wp_error($term)) {
                update_term_meta($term['term_id'], 'feature_group', $feature['group']);
            }
        }
    }
}
add_action('after_switch_theme', 'str_prepopulate_clinic_features');

/**
 * Pre-populate US states in us_location taxonomy
 */
function str_prepopulate_us_states() {
    $states = array(
        array('name' => 'Alabama', 'acronym' => 'AL'),
        array('name' => 'Alaska', 'acronym' => 'AK'),
        array('name' => 'Arizona', 'acronym' => 'AZ'),
        array('name' => 'Arkansas', 'acronym' => 'AR'),
        array('name' => 'California', 'acronym' => 'CA'),
        array('name' => 'Colorado', 'acronym' => 'CO'),
        array('name' => 'Connecticut', 'acronym' => 'CT'),
        array('name' => 'Delaware', 'acronym' => 'DE'),
        array('name' => 'Florida', 'acronym' => 'FL'),
        array('name' => 'Georgia', 'acronym' => 'GA'),
        array('name' => 'Hawaii', 'acronym' => 'HI'),
        array('name' => 'Idaho', 'acronym' => 'ID'),
        array('name' => 'Illinois', 'acronym' => 'IL'),
        array('name' => 'Indiana', 'acronym' => 'IN'),
        array('name' => 'Iowa', 'acronym' => 'IA'),
        array('name' => 'Kansas', 'acronym' => 'KS'),
        array('name' => 'Kentucky', 'acronym' => 'KY'),
        array('name' => 'Louisiana', 'acronym' => 'LA'),
        array('name' => 'Maine', 'acronym' => 'ME'),
        array('name' => 'Maryland', 'acronym' => 'MD'),
        array('name' => 'Massachusetts', 'acronym' => 'MA'),
        array('name' => 'Michigan', 'acronym' => 'MI'),
        array('name' => 'Minnesota', 'acronym' => 'MN'),
        array('name' => 'Mississippi', 'acronym' => 'MS'),
        array('name' => 'Missouri', 'acronym' => 'MO'),
        array('name' => 'Montana', 'acronym' => 'MT'),
        array('name' => 'Nebraska', 'acronym' => 'NE'),
        array('name' => 'Nevada', 'acronym' => 'NV'),
        array('name' => 'New Hampshire', 'acronym' => 'NH'),
        array('name' => 'New Jersey', 'acronym' => 'NJ'),
        array('name' => 'New Mexico', 'acronym' => 'NM'),
        array('name' => 'New York', 'acronym' => 'NY'),
        array('name' => 'North Carolina', 'acronym' => 'NC'),
        array('name' => 'North Dakota', 'acronym' => 'ND'),
        array('name' => 'Ohio', 'acronym' => 'OH'),
        array('name' => 'Oklahoma', 'acronym' => 'OK'),
        array('name' => 'Oregon', 'acronym' => 'OR'),
        array('name' => 'Pennsylvania', 'acronym' => 'PA'),
        array('name' => 'Rhode Island', 'acronym' => 'RI'),
        array('name' => 'South Carolina', 'acronym' => 'SC'),
        array('name' => 'South Dakota', 'acronym' => 'SD'),
        array('name' => 'Tennessee', 'acronym' => 'TN'),
        array('name' => 'Texas', 'acronym' => 'TX'),
        array('name' => 'Utah', 'acronym' => 'UT'),
        array('name' => 'Vermont', 'acronym' => 'VT'),
        array('name' => 'Virginia', 'acronym' => 'VA'),
        array('name' => 'Washington', 'acronym' => 'WA'),
        array('name' => 'West Virginia', 'acronym' => 'WV'),
        array('name' => 'Wisconsin', 'acronym' => 'WI'),
        array('name' => 'Wyoming', 'acronym' => 'WY'),
    );

    foreach ($states as $state) {
        $slug = sanitize_title($state['name']);
        $existing_term = term_exists($slug, 'us_location');
        
        if (!$existing_term) {
            // Create new state term
            $term = wp_insert_term($state['name'], 'us_location', array(
                'slug'   => $slug,
                'parent' => 0, // States are parent terms (no parent)
            ));
            
            // Add acronym as term meta
            if (!is_wp_error($term) && isset($term['term_id'])) {
                update_term_meta($term['term_id'], 'us_location_acronym', $state['acronym']);
            }
        } else {
            // Update existing state with acronym if missing
            $term_id = is_array($existing_term) ? $existing_term['term_id'] : $existing_term;
            $existing_acronym = get_term_meta($term_id, 'us_location_acronym', true);
            
            if (empty($existing_acronym)) {
                update_term_meta($term_id, 'us_location_acronym', $state['acronym']);
            }
        }
    }
}
add_action('after_switch_theme', 'str_prepopulate_us_states');

/**
 * Add Acronym field to US Location taxonomy (Add form)
 */
function str_add_us_location_acronym_field() {
    ?>
    <div class="form-field">
        <label for="us_location_acronym"><?php _e('State Acronym', 'search-tattoo-removal'); ?></label>
        <input type="text" name="us_location_acronym" id="us_location_acronym" maxlength="2" style="text-transform: uppercase;">
        <p class="description"><?php _e('2-letter state abbreviation (e.g., CA, NY, TX)', 'search-tattoo-removal'); ?></p>
    </div>
    <?php
}
add_action('us_location_add_form_fields', 'str_add_us_location_acronym_field');

/**
 * Add Acronym field to US Location taxonomy (Edit form)
 */
function str_edit_us_location_acronym_field($term) {
    $acronym = get_term_meta($term->term_id, 'us_location_acronym', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="us_location_acronym"><?php _e('State Acronym', 'search-tattoo-removal'); ?></label>
        </th>
        <td>
            <input type="text" name="us_location_acronym" id="us_location_acronym" value="<?php echo esc_attr($acronym); ?>" maxlength="2" style="text-transform: uppercase;">
            <p class="description"><?php _e('2-letter state abbreviation (e.g., CA, NY, TX)', 'search-tattoo-removal'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('us_location_edit_form_fields', 'str_edit_us_location_acronym_field');

/**
 * Save Acronym field for US Location taxonomy
 */
function str_save_us_location_acronym_field($term_id) {
    if (isset($_POST['us_location_acronym'])) {
        $acronym = strtoupper(sanitize_text_field($_POST['us_location_acronym']));
        update_term_meta($term_id, 'us_location_acronym', $acronym);
    }
}
add_action('created_us_location', 'str_save_us_location_acronym_field');
add_action('edited_us_location', 'str_save_us_location_acronym_field');
