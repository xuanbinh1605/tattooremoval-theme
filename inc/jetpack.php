<?php
/**
 * Jetpack Compatibility
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Jetpack setup function
 */
function str_jetpack_setup() {
    // Add theme support for Infinite Scroll
    add_theme_support('infinite-scroll', array(
        'container' => 'primary',
        'render'    => 'str_infinite_scroll_render',
        'footer'    => 'page',
    ));
}
add_action('after_setup_theme', 'str_jetpack_setup');

/**
 * Custom render function for Infinite Scroll
 */
function str_infinite_scroll_render() {
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content', get_post_type());
    }
}
