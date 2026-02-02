<?php
/**
 * Template for displaying single clinic posts
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main single-clinic">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="container">
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php str_clinic_rating(get_the_ID()); ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="clinic-featured-image">
                        <?php the_post_thumbnail('str-featured'); ?>
                    </div>
                <?php endif; ?>

                <div class="clinic-info-grid">
                    <div class="clinic-main-content">
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <?php
                        // Display treatment types
                        $treatments = get_the_terms(get_the_ID(), 'treatment_type');
                        if ($treatments && !is_wp_error($treatments)) :
                        ?>
                            <div class="clinic-treatments">
                                <h3><?php esc_html_e('Treatment Types', 'search-tattoo-removal'); ?></h3>
                                <ul class="treatment-list">
                                    <?php foreach ($treatments as $treatment) : ?>
                                        <li><?php echo esc_html($treatment->name); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <aside class="clinic-sidebar">
                        <div class="clinic-contact-card">
                            <h3><?php esc_html_e('Contact Information', 'search-tattoo-removal'); ?></h3>
                            
                            <?php 
                            $address = str_get_clinic_address(get_the_ID());
                            if ($address) :
                            ?>
                                <div class="contact-item">
                                    <strong><?php esc_html_e('Address:', 'search-tattoo-removal'); ?></strong>
                                    <p><?php echo esc_html($address); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $phone = str_get_clinic_contact(get_the_ID(), 'phone');
                            if ($phone) :
                            ?>
                                <div class="contact-item">
                                    <strong><?php esc_html_e('Phone:', 'search-tattoo-removal'); ?></strong>
                                    <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $email = str_get_clinic_contact(get_the_ID(), 'email');
                            if ($email) :
                            ?>
                                <div class="contact-item">
                                    <strong><?php esc_html_e('Email:', 'search-tattoo-removal'); ?></strong>
                                    <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $website = str_get_clinic_contact(get_the_ID(), 'website');
                            if ($website) :
                            ?>
                                <div class="contact-item">
                                    <strong><?php esc_html_e('Website:', 'search-tattoo-removal'); ?></strong>
                                    <p><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($website); ?></a></p>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $hours = get_post_meta(get_the_ID(), '_clinic_hours', true);
                            if ($hours) :
                            ?>
                                <div class="contact-item">
                                    <strong><?php esc_html_e('Hours:', 'search-tattoo-removal'); ?></strong>
                                    <p><?php echo nl2br(esc_html($hours)); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $price_range = get_post_meta(get_the_ID(), '_clinic_price_range', true);
                            if ($price_range) :
                            ?>
                                <div class="contact-item">
                                    <strong><?php esc_html_e('Price Range:', 'search-tattoo-removal'); ?></strong>
                                    <p><?php echo esc_html($price_range); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="clinic-cta">
                            <?php if ($phone) : ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="btn btn-primary btn-block">
                                    <?php esc_html_e('Call Now', 'search-tattoo-removal'); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($website) : ?>
                                <a href="<?php echo esc_url($website); ?>" class="btn btn-secondary btn-block" target="_blank" rel="noopener noreferrer">
                                    <?php esc_html_e('Visit Website', 'search-tattoo-removal'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </aside>
                </div>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
