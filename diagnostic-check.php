<?php
/**
 * Diagnostic Check - Taxonomy and Clinic Data
 * 
 * To use: Navigate to: yoursite.com/wp-content/themes/search-tattoo-removal/diagnostic-check.php
 * 
 * @package SearchTattooRemoval
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('administrator')) {
    die('You must be an administrator to run this script.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Check - Tattoo Removal Site</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #333; }
        h2 { color: #666; margin-top: 30px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .section { background: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #333; }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic Check - Tattoo Removal Site</h1>
    
    <!-- Check 1: US Location Taxonomy -->
    <div class="section">
        <h2>1. US Location Taxonomy Terms</h2>
        <?php
        $location_terms = get_terms(array(
            'taxonomy' => 'us_location',
            'hide_empty' => false,
            'orderby' => 'name',
        ));
        
        if (empty($location_terms)) {
            echo '<p class="error">❌ No location terms found! You need to create state and city terms.</p>';
        } else {
            echo '<p class="success">✓ Found ' . count($location_terms) . ' location terms</p>';
            echo '<table>';
            echo '<tr><th>Term Name</th><th>Term ID</th><th>Parent ID</th><th>Type</th><th>Clinic Count</th></tr>';
            foreach ($location_terms as $term) {
                $is_state = ($term->parent == 0);
                $type = $is_state ? 'State' : 'City';
                echo '<tr>';
                echo '<td>' . esc_html($term->name) . '</td>';
                echo '<td>' . $term->term_id . '</td>';
                echo '<td>' . $term->parent . '</td>';
                echo '<td>' . $type . '</td>';
                echo '<td>' . $term->count . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
    </div>
    
    <!-- Check 2: Clinic Posts -->
    <div class="section">
        <h2>2. Clinic Posts</h2>
        <?php
        $clinics = get_posts(array(
            'post_type' => 'clinic',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));
        
        if (empty($clinics)) {
            echo '<p class="error">❌ No clinic posts found! You need to run the sample-clinic-data.php script.</p>';
        } else {
            echo '<p class="success">✓ Found ' . count($clinics) . ' clinic posts</p>';
            echo '<table>';
            echo '<tr><th>Clinic Name</th><th>ID</th><th>Associated Locations</th><th>Rating</th></tr>';
            foreach ($clinics as $clinic) {
                $clinic_locations = wp_get_post_terms($clinic->ID, 'us_location');
                $location_names = array();
                foreach ($clinic_locations as $loc) {
                    $location_names[] = $loc->name;
                }
                $rating = get_post_meta($clinic->ID, '_rating', true);
                
                echo '<tr>';
                echo '<td>' . esc_html($clinic->post_title) . '</td>';
                echo '<td>' . $clinic->ID . '</td>';
                echo '<td>' . (empty($location_names) ? '<span class="error">None!</span>' : implode(', ', $location_names)) . '</td>';
                echo '<td>' . ($rating ? $rating : 'N/A') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
    </div>
    
    <!-- Check 3: Test Query for Alaska -->
    <div class="section">
        <h2>3. Test Query - Alaska</h2>
        <?php
        $alaska_term = get_term_by('name', 'Alaska', 'us_location');
        
        if (!$alaska_term) {
            echo '<p class="warning">⚠ Alaska term not found in taxonomy</p>';
        } else {
            echo '<p class="success">✓ Alaska term found (ID: ' . $alaska_term->term_id . ')</p>';
            
            // Get child cities
            $alaska_cities = get_terms(array(
                'taxonomy' => 'us_location',
                'hide_empty' => false,
                'parent' => $alaska_term->term_id,
            ));
            
            echo '<p>Alaska has ' . count($alaska_cities) . ' cities in the taxonomy</p>';
            if (!empty($alaska_cities)) {
                echo '<ul>';
                foreach ($alaska_cities as $city) {
                    echo '<li>' . esc_html($city->name) . ' (ID: ' . $city->term_id . ')</li>';
                }
                echo '</ul>';
            }
            
            // Query clinics for Alaska
            $term_ids = array($alaska_term->term_id);
            if (!empty($alaska_cities)) {
                foreach ($alaska_cities as $city) {
                    $term_ids[] = $city->term_id;
                }
            }
            
            $test_query = new WP_Query(array(
                'post_type' => 'clinic',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'us_location',
                        'field' => 'term_id',
                        'terms' => $term_ids,
                    ),
                ),
            ));
            
            echo '<p><strong>Clinics in Alaska:</strong> ' . $test_query->found_posts . '</p>';
            
            if ($test_query->have_posts()) {
                echo '<ul>';
                while ($test_query->have_posts()) {
                    $test_query->the_post();
                    echo '<li>' . get_the_title() . '</li>';
                }
                wp_reset_postdata();
                echo '</ul>';
            } else {
                echo '<p class="warning">⚠ No clinics found associated with Alaska or its cities</p>';
            }
        }
        ?>
    </div>
    
    <!-- Check 4: Recommendations -->
    <div class="section">
        <h2>4. Recommendations</h2>
        <?php
        $issues = array();
        $recommendations = array();
        
        // Check if we have location terms
        if (empty($location_terms)) {
            $issues[] = 'No location terms found';
            $recommendations[] = 'Create state and city terms in the US Locations taxonomy';
        }
        
        // Check if we have clinics
        if (empty($clinics)) {
            $issues[] = 'No clinic posts found';
            $recommendations[] = 'Run the sample-clinic-data.php script to create sample clinics';
        } elseif (!empty($clinics)) {
            // Check if any clinics have no location
            $unassigned_count = 0;
            foreach ($clinics as $clinic) {
                $clinic_locations = wp_get_post_terms($clinic->ID, 'us_location');
                if (empty($clinic_locations)) {
                    $unassigned_count++;
                }
            }
            if ($unassigned_count > 0) {
                $issues[] = $unassigned_count . ' clinic(s) have no location assigned';
                $recommendations[] = 'Edit each clinic and assign a city location (State → City)';
            }
        }
        
        if (empty($issues)) {
            echo '<p class="success">✓ No issues found! Your site should be working correctly.</p>';
            echo '<p><strong>Test URLs:</strong></p>';
            echo '<ul>';
            if (!empty($location_terms)) {
                foreach (array_slice($location_terms, 0, 5) as $term) {
                    if ($term->parent == 0) { // Only show states
                        $term_link = get_term_link($term);
                        echo '<li><a href="' . esc_url($term_link) . '" target="_blank">' . esc_html($term->name) . '</a> (add ?debug=1 to see debug info)</li>';
                    }
                }
            }
            echo '</ul>';
        } else {
            echo '<p class="error"><strong>Issues Found:</strong></p>';
            echo '<ul>';
            foreach ($issues as $issue) {
                echo '<li class="error">' . esc_html($issue) . '</li>';
            }
            echo '</ul>';
            
            echo '<p class="warning"><strong>Recommended Actions:</strong></p>';
            echo '<ol>';
            foreach ($recommendations as $rec) {
                echo '<li>' . esc_html($rec) . '</li>';
            }
            echo '</ol>';
        }
        ?>
    </div>
    
    <hr>
    <p><strong>Next Steps:</strong></p>
    <ol>
        <li>Fix any issues listed above</li>
        <li>Visit a taxonomy page (e.g., /us-location/alaska/) and add ?debug=1 to see debug info</li>
        <li>After fixing issues, you can delete this diagnostic-check.php file</li>
    </ol>
</body>
</html>
