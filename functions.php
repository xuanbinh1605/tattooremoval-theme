<?php
/**
 * Search Tattoo Removal Theme Functions
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Theme version
define('STR_VERSION', '1.0.0');

// Theme directory paths
define('STR_DIR', get_template_directory());
define('STR_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function str_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain('search-tattoo-removal', STR_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    
    // Set default thumbnail size
    set_post_thumbnail_size(1200, 675, true);
    
    // Add custom image sizes
    add_image_size('str-featured', 1200, 675, true);
    add_image_size('str-clinic-card', 600, 400, true);
    add_image_size('str-clinic-thumbnail', 300, 200, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'search-tattoo-removal'),
        'footer' => esc_html__('Footer Menu', 'search-tattoo-removal'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'str_theme_setup');

/**
 * Set the content width in pixels
 */
function str_content_width() {
    $GLOBALS['content_width'] = apply_filters('str_content_width', 1200);
}
add_action('after_setup_theme', 'str_content_width', 0);

/**
 * Register Widget Areas
 */
function str_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'search-tattoo-removal'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'search-tattoo-removal'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area 1', 'search-tattoo-removal'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Appears in the footer section of the site.', 'search-tattoo-removal'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area 2', 'search-tattoo-removal'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Appears in the footer section of the site.', 'search-tattoo-removal'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area 3', 'search-tattoo-removal'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Appears in the footer section of the site.', 'search-tattoo-removal'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'str_widgets_init');

/**
 * Enqueue scripts and styles
 */
function str_scripts() {
    // Main stylesheet
    wp_enqueue_style('str-style', get_stylesheet_uri(), array(), STR_VERSION);
    
    // Theme styles
    wp_enqueue_style('str-main', STR_URI . '/assets/css/main.css', array(), STR_VERSION);

    // Theme scripts
    wp_enqueue_script('str-main', STR_URI . '/assets/js/main.js', array('jquery'), STR_VERSION, true);

    // Localize script with AJAX URL
    wp_localize_script('str-main', 'strAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('str_nonce'),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'str_scripts');

/**
 * Include additional theme files
 */
require_once STR_DIR . '/inc/custom-post-types.php';
require_once STR_DIR . '/inc/custom-taxonomies.php';
require_once STR_DIR . '/inc/custom-fields.php';
require_once STR_DIR . '/inc/template-functions.php';
require_once STR_DIR . '/inc/rest-api.php';
require_once STR_DIR . '/inc/admin-functions.php';
require_once STR_DIR . '/inc/clinic-importer.php';

/**
 * Load Jetpack compatibility file if Jetpack is active
 */
if (defined('JETPACK__VERSION')) {
    require STR_DIR . '/inc/jetpack.php';
}
