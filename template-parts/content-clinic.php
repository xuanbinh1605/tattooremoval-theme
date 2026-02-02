<?php
/**
 * Template part for displaying clinic posts
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clinic-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="clinic-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('str-clinic-card'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="clinic-content">
        <header class="clinic-header">
            <h2 class="clinic-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            
            <?php 
            $rating = get_post_meta(get_the_ID(), '_clinic_rating', true);
            if ($rating) :
                str_clinic_rating(get_the_ID());
            endif;
            ?>
        </header>

        <div class="clinic-details">
            <?php 
            $address = str_get_clinic_address(get_the_ID());
            if ($address) :
            ?>
                <div class="clinic-address">
                    <span class="icon">📍</span>
                    <span><?php echo esc_html($address); ?></span>
                </div>
            <?php endif; ?>

            <?php 
            $phone = str_get_clinic_contact(get_the_ID(), 'phone');
            if ($phone) :
            ?>
                <div class="clinic-phone">
                    <span class="icon">📞</span>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php 
            $price_range = get_post_meta(get_the_ID(), '_clinic_price_range', true);
            if ($price_range) :
            ?>
                <div class="clinic-price">
                    <span class="icon">💰</span>
                    <span><?php echo esc_html($price_range); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="clinic-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="clinic-actions">
            <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                <?php esc_html_e('View Details', 'search-tattoo-removal'); ?>
            </a>
        </div>
    </div>
</article>
