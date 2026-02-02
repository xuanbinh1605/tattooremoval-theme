<?php
/**
 * Register Custom Post Types
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Clinic Post Type
 */
function str_register_clinic_post_type() {
    $labels = array(
        'name'                  => _x('Clinics', 'Post Type General Name', 'search-tattoo-removal'),
        'singular_name'         => _x('Clinic', 'Post Type Singular Name', 'search-tattoo-removal'),
        'menu_name'             => __('Clinics', 'search-tattoo-removal'),
        'name_admin_bar'        => __('Clinic', 'search-tattoo-removal'),
        'archives'              => __('Clinic Archives', 'search-tattoo-removal'),
        'attributes'            => __('Clinic Attributes', 'search-tattoo-removal'),
        'parent_item_colon'     => __('Parent Clinic:', 'search-tattoo-removal'),
        'all_items'             => __('All Clinics', 'search-tattoo-removal'),
        'add_new_item'          => __('Add New Clinic', 'search-tattoo-removal'),
        'add_new'               => __('Add New', 'search-tattoo-removal'),
        'new_item'              => __('New Clinic', 'search-tattoo-removal'),
        'edit_item'             => __('Edit Clinic', 'search-tattoo-removal'),
        'update_item'           => __('Update Clinic', 'search-tattoo-removal'),
        'view_item'             => __('View Clinic', 'search-tattoo-removal'),
        'view_items'            => __('View Clinics', 'search-tattoo-removal'),
        'search_items'          => __('Search Clinic', 'search-tattoo-removal'),
        'not_found'             => __('Not found', 'search-tattoo-removal'),
        'not_found_in_trash'    => __('Not found in Trash', 'search-tattoo-removal'),
        'featured_image'        => __('Clinic Thumbnail', 'search-tattoo-removal'),
        'set_featured_image'    => __('Set clinic thumbnail', 'search-tattoo-removal'),
        'remove_featured_image' => __('Remove clinic thumbnail', 'search-tattoo-removal'),
        'use_featured_image'    => __('Use as clinic thumbnail', 'search-tattoo-removal'),
        'insert_into_item'      => __('Insert into clinic', 'search-tattoo-removal'),
        'uploaded_to_this_item' => __('Uploaded to this clinic', 'search-tattoo-removal'),
        'items_list'            => __('Clinics list', 'search-tattoo-removal'),
        'items_list_navigation' => __('Clinics list navigation', 'search-tattoo-removal'),
        'filter_items_list'     => __('Filter clinics list', 'search-tattoo-removal'),
    );

    $args = array(
        'label'               => __('Clinic', 'search-tattoo-removal'),
        'description'         => __('US Tattoo Removal Clinics Directory', 'search-tattoo-removal'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-location-alt',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rest_base'           => 'clinics',
        'rewrite'             => array('slug' => 'clinic', 'with_front' => false),
    );

    register_post_type('clinic', $args);
}
add_action('init', 'str_register_clinic_post_type', 0);

/**
 * Register Laser Technology Post Type
 */
function str_register_laser_tech_post_type() {
    $labels = array(
        'name'                  => _x('Laser Technologies', 'Post Type General Name', 'search-tattoo-removal'),
        'singular_name'         => _x('Laser Technology', 'Post Type Singular Name', 'search-tattoo-removal'),
        'menu_name'             => __('Laser Tech', 'search-tattoo-removal'),
        'name_admin_bar'        => __('Laser Technology', 'search-tattoo-removal'),
        'archives'              => __('Laser Technology Archives', 'search-tattoo-removal'),
        'attributes'            => __('Laser Technology Attributes', 'search-tattoo-removal'),
        'parent_item_colon'     => __('Parent Technology:', 'search-tattoo-removal'),
        'all_items'             => __('All Technologies', 'search-tattoo-removal'),
        'add_new_item'          => __('Add New Technology', 'search-tattoo-removal'),
        'add_new'               => __('Add New', 'search-tattoo-removal'),
        'new_item'              => __('New Technology', 'search-tattoo-removal'),
        'edit_item'             => __('Edit Technology', 'search-tattoo-removal'),
        'update_item'           => __('Update Technology', 'search-tattoo-removal'),
        'view_item'             => __('View Technology', 'search-tattoo-removal'),
        'view_items'            => __('View Technologies', 'search-tattoo-removal'),
        'search_items'          => __('Search Technology', 'search-tattoo-removal'),
        'not_found'             => __('Not found', 'search-tattoo-removal'),
        'not_found_in_trash'    => __('Not found in Trash', 'search-tattoo-removal'),
        'featured_image'        => __('Technology Image', 'search-tattoo-removal'),
        'set_featured_image'    => __('Set technology image', 'search-tattoo-removal'),
        'remove_featured_image' => __('Remove technology image', 'search-tattoo-removal'),
        'use_featured_image'    => __('Use as technology image', 'search-tattoo-removal'),
        'insert_into_item'      => __('Insert into technology', 'search-tattoo-removal'),
        'uploaded_to_this_item' => __('Uploaded to this technology', 'search-tattoo-removal'),
        'items_list'            => __('Technologies list', 'search-tattoo-removal'),
        'items_list_navigation' => __('Technologies list navigation', 'search-tattoo-removal'),
        'filter_items_list'     => __('Filter technologies list', 'search-tattoo-removal'),
    );

    $args = array(
        'label'               => __('Laser Technology', 'search-tattoo-removal'),
        'description'         => __('Laser Removal Technologies', 'search-tattoo-removal'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-admin-tools',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rest_base'           => 'laser-technologies',
        'rewrite'             => array('slug' => 'laser-technology', 'with_front' => false),
    );

    register_post_type('laser_tech', $args);
}
add_action('init', 'str_register_laser_tech_post_type', 0);

/**
 * Flush rewrite rules on theme activation
 */
function str_rewrite_flush() {
    str_register_clinic_post_type();
    str_register_laser_tech_post_type();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'str_rewrite_flush');
