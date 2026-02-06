<?php
/**
 * Template Name: States Directory
 * Description: Display all US states with clinic counts
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    
    <!-- Header Section -->
    <header class="bg-white border-b border-gray-light py-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center justify-center p-3 bg-brand/10 rounded-2xl mb-6 text-brand">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map w-8 h-8" aria-hidden="true">
                    <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                    <path d="M15 5.764v15"></path>
                    <path d="M9 3.236v15"></path>
                </svg>
            </div>
            <h1 class="text-5xl font-black text-charcoal mb-4 uppercase tracking-tight">State Directory</h1>
            <p class="text-xl text-graphite font-medium max-w-2xl mx-auto">Browse high-rated laser tattoo removal centers across all 50 U.S. states.</p>
        </div>
    </header>

    <!-- States Grid Section -->
    <section class="bg-gray-50 py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <?php
            // Get all parent states (terms with no parent)
            $states = get_terms(array(
                'taxonomy'   => 'us_location',
                'hide_empty' => false,
                'parent'     => 0,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));

            if (!empty($states) && !is_wp_error($states)) :
            ?>
                <!-- Summary Stats -->
                <div class="mb-12 text-center">
                    <div class="inline-flex items-center gap-8 px-8 py-4 bg-white border border-gray-light rounded-xl">
                        <div>
                            <p class="text-3xl font-black text-brand"><?php echo count($states); ?></p>
                            <p class="text-sm text-graphite font-medium">States</p>
                        </div>
                        <div class="w-px h-12 bg-gray-light"></div>
                        <div>
                            <?php
                            // Get total number of cities
                            $all_cities = get_terms(array(
                                'taxonomy'   => 'us_location',
                                'hide_empty' => false,
                                'parent__not_in' => array(0),
                                'fields'     => 'count',
                            ));
                            ?>
                            <p class="text-3xl font-black text-brand"><?php echo esc_html($all_cities); ?></p>
                            <p class="text-sm text-graphite font-medium">Cities</p>
                        </div>
                        <div class="w-px h-12 bg-gray-light"></div>
                        <div>
                            <?php
                            // Get total number of clinics
                            $total_clinics = wp_count_posts('clinic');
                            $total_count = isset($total_clinics->publish) ? $total_clinics->publish : 0;
                            ?>
                            <p class="text-3xl font-black text-brand"><?php echo esc_html($total_count); ?></p>
                            <p class="text-sm text-graphite font-medium">Total Clinics</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <?php foreach ($states as $state) :
                        // Get clinic count for this state (including all child cities)
                        $cities = get_terms(array(
                            'taxonomy'   => 'us_location',
                            'hide_empty' => false,
                            'parent'     => $state->term_id,
                            'fields'     => 'ids',
                        ));
                        
                        // Count clinics in all cities of this state
                        $clinic_count = 0;
                        if (!empty($cities)) {
                            $clinic_count = get_posts(array(
                                'post_type'      => 'clinic',
                                'posts_per_page' => -1,
                                'fields'         => 'ids',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'us_location',
                                        'field'    => 'term_id',
                                        'terms'    => $cities,
                                    ),
                                ),
                            ));
                            $clinic_count = is_array($clinic_count) ? count($clinic_count) : 0;
                        }
                        
                        $state_link = get_term_link($state);
                        $city_count = count($cities);
                        $acronym = get_term_meta($state->term_id, 'us_location_acronym', true);
                    ?>
                        <a href="<?php echo esc_url($state_link); ?>" 
                           class="group bg-white border border-gray-light rounded-xl p-6 hover:border-brand hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <?php if ($acronym) : ?>
                                    <div class="text-[10px] font-black text-brand uppercase tracking-[0.2em] mb-1">
                                        <?php echo esc_html($acronym); ?>
                                    </div>
                                <?php endif; ?>
                                <h2 class="text-xl font-bold text-charcoal mb-2 group-hover:text-brand transition-colors">
                                    <?php echo esc_html($state->name); ?>
                                </h2>
                                <?php if ($city_count > 0) : ?>
                                    <p class="flex items-center gap-2 text-sm text-graphite">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-graphite">
                                            <path d="M3 21h18"></path>
                                            <path d="M9 8h1"></path>
                                            <path d="M9 12h1"></path>
                                            <path d="M9 16h1"></path>
                                            <path d="M14 8h1"></path>
                                            <path d="M14 12h1"></path>
                                            <path d="M14 16h1"></path>
                                            <path d="M6 3v18"></path>
                                            <path d="M18 3v18"></path>
                                            <path d="M6 3h12"></path>
                                        </svg>
                                        <span><?php echo esc_html($city_count); ?></span>
                                        <?php echo $city_count === 1 ? 'City' : 'Cities'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-offwhite">
                                <div class="text-[10px] font-bold text-graphite uppercase tracking-widest">
                                    <span class="text-charcoal font-black"><?php echo esc_html($clinic_count); ?></span> <?php echo $clinic_count === 1 ? 'Clinic' : 'Clinics'; ?>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right w-4 h-4 text-gray-light group-hover:text-brand group-hover:translate-x-1 transition-all" aria-hidden="true">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php else : ?>
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-graphite">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-charcoal mb-2">No States Found</h2>
                    <p class="text-graphite">Please add US location states in the WordPress admin.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php
get_footer();
