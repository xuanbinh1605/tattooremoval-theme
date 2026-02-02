<?php
/**
 * Template for displaying search results
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main search-results">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'search-tattoo-removal'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="search-results-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    
                    if (get_post_type() === 'clinic') {
                        get_template_part('template-parts/content', 'clinic');
                    } else {
                        get_template_part('template-parts/content', get_post_type());
                    }
                endwhile;
                ?>
            </div>

            <?php str_pagination(); ?>

        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
