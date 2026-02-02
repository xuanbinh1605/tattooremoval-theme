<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'search-tattoo-removal'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try searching?', 'search-tattoo-removal'); ?></p>

                <?php get_search_form(); ?>

                <div class="helpful-links">
                    <h2><?php esc_html_e('Helpful Links', 'search-tattoo-removal'); ?></h2>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home Page', 'search-tattoo-removal'); ?></a></li>
                        <li><a href="<?php echo esc_url(get_post_type_archive_link('clinic')); ?>"><?php esc_html_e('Browse All Clinics', 'search-tattoo-removal'); ?></a></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
