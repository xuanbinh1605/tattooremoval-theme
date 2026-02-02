<?php
/**
 * Template for displaying clinic archives
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main clinic-archive">
    <div class="container">
        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="clinic-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'clinic');
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
