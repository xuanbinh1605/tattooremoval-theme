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
