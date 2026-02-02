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
 * Register State Taxonomy
 */
function str_register_state_taxonomy() {
    $labels = array(
        'name'                       => _x('States', 'Taxonomy General Name', 'search-tattoo-removal'),
        'singular_name'              => _x('State', 'Taxonomy Singular Name', 'search-tattoo-removal'),
        'menu_name'                  => __('States', 'search-tattoo-removal'),
        'all_items'                  => __('All States', 'search-tattoo-removal'),
        'parent_item'                => __('Parent State', 'search-tattoo-removal'),
        'parent_item_colon'          => __('Parent State:', 'search-tattoo-removal'),
        'new_item_name'              => __('New State Name', 'search-tattoo-removal'),
        'add_new_item'               => __('Add New State', 'search-tattoo-removal'),
        'edit_item'                  => __('Edit State', 'search-tattoo-removal'),
        'update_item'                => __('Update State', 'search-tattoo-removal'),
        'view_item'                  => __('View State', 'search-tattoo-removal'),
        'separate_items_with_commas' => __('Separate states with commas', 'search-tattoo-removal'),
        'add_or_remove_items'        => __('Add or remove states', 'search-tattoo-removal'),
        'choose_from_most_used'      => __('Choose from the most used', 'search-tattoo-removal'),
        'popular_items'              => __('Popular States', 'search-tattoo-removal'),
        'search_items'               => __('Search States', 'search-tattoo-removal'),
        'not_found'                  => __('Not Found', 'search-tattoo-removal'),
        'no_terms'                   => __('No states', 'search-tattoo-removal'),
        'items_list'                 => __('States list', 'search-tattoo-removal'),
        'items_list_navigation'      => __('States list navigation', 'search-tattoo-removal'),
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
        'rest_base'         => 'states',
        'rewrite'           => array('slug' => 'state', 'with_front' => false),
    );

    register_taxonomy('state', array('clinic'), $args);
}
add_action('init', 'str_register_state_taxonomy', 0);

/**
 * Register City Taxonomy
 */
function str_register_city_taxonomy() {
    $labels = array(
        'name'                       => _x('Cities', 'Taxonomy General Name', 'search-tattoo-removal'),
        'singular_name'              => _x('City', 'Taxonomy Singular Name', 'search-tattoo-removal'),
        'menu_name'                  => __('Cities', 'search-tattoo-removal'),
        'all_items'                  => __('All Cities', 'search-tattoo-removal'),
        'parent_item'                => __('Parent City', 'search-tattoo-removal'),
        'parent_item_colon'          => __('Parent City:', 'search-tattoo-removal'),
        'new_item_name'              => __('New City Name', 'search-tattoo-removal'),
        'add_new_item'               => __('Add New City', 'search-tattoo-removal'),
        'edit_item'                  => __('Edit City', 'search-tattoo-removal'),
        'update_item'                => __('Update City', 'search-tattoo-removal'),
        'view_item'                  => __('View City', 'search-tattoo-removal'),
        'separate_items_with_commas' => __('Separate cities with commas', 'search-tattoo-removal'),
        'add_or_remove_items'        => __('Add or remove cities', 'search-tattoo-removal'),
        'choose_from_most_used'      => __('Choose from the most used', 'search-tattoo-removal'),
        'popular_items'              => __('Popular Cities', 'search-tattoo-removal'),
        'search_items'               => __('Search Cities', 'search-tattoo-removal'),
        'not_found'                  => __('Not Found', 'search-tattoo-removal'),
        'no_terms'                   => __('No cities', 'search-tattoo-removal'),
        'items_list'                 => __('Cities list', 'search-tattoo-removal'),
        'items_list_navigation'      => __('Cities list navigation', 'search-tattoo-removal'),
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
        'rest_base'         => 'cities',
        'rewrite'           => array('slug' => 'city', 'with_front' => false),
    );

    register_taxonomy('city', array('clinic'), $args);
}
add_action('init', 'str_register_city_taxonomy', 0);

/**
 * Register Treatment Type Taxonomy
 */
function str_register_treatment_taxonomy() {
    $labels = array(
        'name'                       => _x('Treatment Types', 'Taxonomy General Name', 'search-tattoo-removal'),
        'singular_name'              => _x('Treatment Type', 'Taxonomy Singular Name', 'search-tattoo-removal'),
        'menu_name'                  => __('Treatment Types', 'search-tattoo-removal'),
        'all_items'                  => __('All Treatment Types', 'search-tattoo-removal'),
        'parent_item'                => __('Parent Treatment Type', 'search-tattoo-removal'),
        'parent_item_colon'          => __('Parent Treatment Type:', 'search-tattoo-removal'),
        'new_item_name'              => __('New Treatment Type Name', 'search-tattoo-removal'),
        'add_new_item'               => __('Add New Treatment Type', 'search-tattoo-removal'),
        'edit_item'                  => __('Edit Treatment Type', 'search-tattoo-removal'),
        'update_item'                => __('Update Treatment Type', 'search-tattoo-removal'),
        'view_item'                  => __('View Treatment Type', 'search-tattoo-removal'),
        'separate_items_with_commas' => __('Separate treatment types with commas', 'search-tattoo-removal'),
        'add_or_remove_items'        => __('Add or remove treatment types', 'search-tattoo-removal'),
        'choose_from_most_used'      => __('Choose from the most used', 'search-tattoo-removal'),
        'popular_items'              => __('Popular Treatment Types', 'search-tattoo-removal'),
        'search_items'               => __('Search Treatment Types', 'search-tattoo-removal'),
        'not_found'                  => __('Not Found', 'search-tattoo-removal'),
        'no_terms'                   => __('No treatment types', 'search-tattoo-removal'),
        'items_list'                 => __('Treatment types list', 'search-tattoo-removal'),
        'items_list_navigation'      => __('Treatment types list navigation', 'search-tattoo-removal'),
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
        'rest_base'         => 'treatment-types',
        'rewrite'           => array('slug' => 'treatment', 'with_front' => false),
    );

    register_taxonomy('treatment_type', array('clinic'), $args);
}
add_action('init', 'str_register_treatment_taxonomy', 0);
