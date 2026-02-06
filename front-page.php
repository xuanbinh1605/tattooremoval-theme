<?php
/**
 * Template Name: Front Page
 * Description: The front page template for the tattoo removal search site
 */

get_header();
?>

<main class="flex-grow">
    <div class="flex flex-col w-full bg-offwhite">
        
        <!-- Hero Section -->
        <section class="relative bg-charcoal text-white py-24 lg:py-40 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img alt="Laser Technician performing tattoo removal" class="w-full h-full object-cover opacity-40 object-center" src="https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&amp;w=2070&amp;auto=format&amp;fit=crop">
                <div class="absolute inset-0 bg-gradient-to-r from-charcoal/90 via-charcoal/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-charcoal/20 to-charcoal"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left z-10">
                <div class="max-w-4xl">
                    <h1 class="text-4xl md:text-7xl font-extrabold tracking-tighter mb-8 leading-[1.1] animate-in fade-in slide-in-from-left-4 duration-700">
                        Clear Your Canvas <br class="hidden lg:block">
                        <span class="text-brand">Find the Best</span> Tattoo Removal
                    </h1>
                    <p class="text-xl md:text-2xl text-slate-300 max-w-2xl mb-12 font-medium leading-relaxed animate-in fade-in slide-in-from-left-6 duration-700">
                        Locate the highest-rated medical laser specialists in your city. Compare clinic technology, read verified case studies, and book a free consultation.
                    </p>
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-200">
                        <form class="max-w-2xl mx-auto flex flex-col md:flex-row bg-white rounded-lg p-1.5 flex shadow-xl gap-2 relative border border-gray-light">
                            <div class="flex-grow flex items-center px-3 py-3 bg-offwhite rounded-md relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-5 w-5 text-graphite mr-2 flex-shrink-0" aria-hidden="true">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <input placeholder="City, State, or Zip" class="w-full bg-transparent border-none focus:ring-0 text-charcoal placeholder-graphite font-medium focus:outline-none text-base" autocomplete="off" type="text" value="">
                            </div>
                            <button type="submit" class="px-8 py-3 text-lg bg-brand hover:bg-brand-hover text-white font-bold rounded-md transition-colors flex-shrink-0 flex items-center justify-center">
                                <span class="inline">Search</span>
                            </button>
                        </form>
                    </div>
                    <div class="mt-8 flex flex-wrap justify-center md:justify-start items-center gap-6 opacity-60">
                        <div class="flex items-center text-xs font-black uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-4 h-4 text-brand mr-2" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg> 
                            Medical Grade Lasers
                        </div>
                        <div class="flex items-center text-xs font-black uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-4 h-4 text-brand mr-2" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg> 
                            Board Certified Directors
                        </div>
                        <div class="flex items-center text-xs font-black uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-4 h-4 text-brand mr-2" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg> 
                            FDA Cleared Technology
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top Rated Clinics Section -->
        <section class="py-20 bg-offwhite">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-extrabold text-charcoal tracking-tight uppercase">Top Rated Clinics</h2>
                    <p class="mt-4 text-xl text-graphite">Highest-rated specialists recommended by patients like you.</p>
                </div>
                
                <?php
                // Query featured clinics
                $featured_clinics = new WP_Query(array(
                    'post_type'      => 'clinic',
                    'posts_per_page' => 6,
                    'meta_query'     => array(
                        array(
                            'key'     => '_is_featured',
                            'value'   => '1',
                            'compare' => '='
                        )
                    ),
                    'meta_key'       => '_rating',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                ));
                ?>
                
                <?php if ($featured_clinics->have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <?php while ($featured_clinics->have_posts()) : $featured_clinics->the_post(); 
                        $clinic_id = get_the_ID();
                        $rating = get_post_meta($clinic_id, '_rating', true) ?: 0;
                        $review_count = get_post_meta($clinic_id, '_reviews_count', true) ?: 0;
                        $phone = get_post_meta($clinic_id, '_phone', true);
                        $is_verified = get_post_meta($clinic_id, '_is_verified', true);
                        $open_status = get_post_meta($clinic_id, '_open_status', true);
                        $city = get_post_meta($clinic_id, '_city', true);
                        $price_range = get_post_meta($clinic_id, '_price_range_display', true);
                        $min_price = get_post_meta($clinic_id, '_min_price', true);
                        $equipment = get_post_meta($clinic_id, '_equipment', true);
                        
                        // Get thumbnail or use placeholder
                        $thumbnail = get_the_post_thumbnail_url($clinic_id, 'str-clinic-card');
                        if (!$thumbnail) {
                            $thumbnail = 'https://picsum.photos/400/300?random=' . $clinic_id;
                        }
                        
                        // Get clinic features (limit to 3)
                        $features = wp_get_post_terms($clinic_id, 'clinic_feature', array('number' => 3));
                        
                        // Get location for link
                        $location_terms = wp_get_post_terms($clinic_id, 'us_location');
                        $location_link = '';
                        $location_name = $city;
                        if (!empty($location_terms)) {
                            $city_term = $location_terms[0];
                            $location_link = get_term_link($city_term);
                            $location_name = $city_term->name;
                        }
                        
                        // Determine open status styling
                        $is_open = (stripos($open_status, 'open') !== false);
                        $status_class = $is_open ? 'text-teal' : 'text-red-500';
                        $status_text = $open_status ?: 'Call for hours';
                    ?>
                    
                    <!-- Clinic Card -->
                    <a href="<?php the_permalink(); ?>" class="group bg-white rounded-xl border border-gray-light shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden cursor-pointer flex flex-col">
                        <div class="relative h-44 w-full overflow-hidden">
                            <img alt="<?php echo esc_attr(get_the_title()); ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="<?php echo esc_url($thumbnail); ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <span class="text-white font-black text-[10px] uppercase tracking-widest flex items-center">
                                    View Listing 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1 w-3.5 h-3.5" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </span>
                            </div>
                            <?php if ($is_verified == '1') : ?>
                            <div class="absolute top-3 right-3 bg-teal text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow-lg uppercase tracking-widest border border-white/20">Verified</div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4 flex flex-col">
                            <div class="mb-2">
                                <h3 class="text-lg font-black text-charcoal line-clamp-1 group-hover:text-brand transition-colors mb-0.5"><?php the_title(); ?></h3>
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center text-amber">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-3.5 h-3.5 <?php echo $i <= round($rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                        </svg>
                                        <?php endfor; ?>
                                        <span class="ml-1 text-charcoal font-black text-xs"><?php echo number_format($rating, 1); ?></span>
                                    </div>
                                    <span class="text-gray-light">•</span>
                                    <span class="text-graphite text-[10px] font-bold uppercase tracking-wider"><?php echo esc_html($review_count); ?> Reviews</span>
                                </div>
                            </div>
                            <div class="space-y-1.5 mb-3">
                                <div class="flex items-center text-xs overflow-hidden">
                                    <?php if ($location_link && !is_wp_error($location_link)) : ?>
                                    <button onclick="event.preventDefault(); window.location='<?php echo esc_url($location_link); ?>';" class="flex items-center text-graphite hover:text-brand transition-colors text-left group/link mr-2 flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-3.5 h-3.5 mr-1 flex-shrink-0 text-gray-light group-hover/link:text-brand" aria-hidden="true">
                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span class="underline decoration-gray-light underline-offset-4 font-bold whitespace-nowrap"><?php echo esc_html($location_name); ?></span>
                                    </button>
                                    <?php else : ?>
                                    <span class="flex items-center text-graphite text-left mr-2 flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-3.5 h-3.5 mr-1 flex-shrink-0 text-gray-light" aria-hidden="true">
                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span class="font-bold whitespace-nowrap"><?php echo esc_html($location_name); ?></span>
                                    </span>
                                    <?php endif; ?>
                                    <span class="text-gray-light mr-2 flex-shrink-0">•</span>
                                    <span class="font-bold whitespace-nowrap <?php echo $status_class; ?>"><?php echo esc_html($status_text); ?></span>
                                </div>
                                <?php if ($phone) : ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>" onclick="event.stopPropagation();" class="flex items-center text-xs text-graphite hover:text-brand transition-colors group/link w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-3.5 h-3.5 mr-2 flex-shrink-0 text-gray-light group-hover/link:text-brand" aria-hidden="true">
                                        <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path>
                                    </svg>
                                    <span class="font-bold"><?php echo esc_html($phone); ?></span>
                                </a>
                                <?php endif; ?>
                                <?php if ($equipment) : ?>
                                <div class="flex items-center text-xs text-charcoal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-3.5 h-3.5 mr-2 flex-shrink-0 text-teal" aria-hidden="true">
                                        <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                                    </svg>
                                    <span class="font-black text-[9px] uppercase tracking-wider truncate"><?php echo esc_html($equipment); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($features)) : ?>
                            <div class="flex flex-wrap gap-1 mb-3 pt-3 border-t border-offwhite">
                                <?php foreach ($features as $feature) : ?>
                                <span class="text-[8px] font-black text-graphite bg-offwhite border border-gray-light px-1.5 py-0.5 rounded uppercase tracking-wider whitespace-nowrap"><?php echo esc_html($feature->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <div class="flex items-center justify-between pt-1 border-t border-offwhite mt-1">
                                <div class="text-[9px] font-bold text-graphite">
                                    <?php 
                                    if ($price_range) {
                                        echo esc_html($price_range);
                                    } elseif ($min_price) {
                                        echo '$' . esc_html($min_price) . ' range';
                                    } else {
                                        echo 'Consultation range';
                                    }
                                    ?>
                                </div>
                                <span class="text-[10px] font-black text-brand uppercase tracking-widest group-hover:underline flex items-center">
                                    View profile 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-0.5 w-3 h-3" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>

                    <?php endwhile; wp_reset_postdata(); ?>
                    
                </div>
                <?php else : ?>
                <div class="text-center py-12">
                    <p class="text-graphite text-lg">No featured clinics available at the moment.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Browse by State Section -->
        <section class="py-20 bg-white border-t border-gray-light">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <h2 class="text-4xl font-extrabold text-charcoal tracking-tight uppercase">Browse by State</h2>
                    <p class="mt-2 text-graphite">Find centers in your specific region of the US.</p>
                </div>

                <!-- Featured States Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Alabama -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Alabama</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">5 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">23 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Alaska -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Alaska</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">2 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">5 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Arizona -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Arizona</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">8 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">56 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Arkansas -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Arkansas</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">4 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">18 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- California -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">California</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">42 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">245 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Colorado -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Colorado</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">9 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">48 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Connecticut -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Connecticut</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">6 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">22 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Delaware -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Delaware</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">2 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">8 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Florida -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Florida</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">25 cities</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">134 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- All States Grid -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 border-t border-gray-light pt-12">
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Georgia</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">52 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Hawaii</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">12 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Idaho</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">11 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Illinois</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">67 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Indiana</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">31 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Iowa</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">15 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Kansas</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">14 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Kentucky</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">21 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Louisiana</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">25 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Maine</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">9 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Maryland</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">34 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Massachusetts</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">41 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Michigan</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">46 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Minnesota</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">29 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Mississippi</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">13 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Missouri</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">28 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Montana</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">6 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Nebraska</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">10 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Nevada</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">38 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">New Hampshire</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">8 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">New Jersey</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">55 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">New Mexico</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">16 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">New York</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">158 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">North Carolina</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">49 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">North Dakota</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">4 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Ohio</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">51 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Oklahoma</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">23 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Oregon</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">33 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Pennsylvania</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">62 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Rhode Island</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">7 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">South Carolina</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">26 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">South Dakota</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">5 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Tennessee</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">35 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Texas</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">187 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Utah</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">19 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Vermont</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">4 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Virginia</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">44 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Washington</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">58 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">West Virginia</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">9 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Wisconsin</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">27 clinics</div>
                    </div>
                    <div class="p-4 border border-gray-light rounded-xl hover:border-brand hover:shadow-lg cursor-pointer transition-all bg-white group flex flex-col justify-center text-center">
                        <div class="font-black text-charcoal text-sm group-hover:text-brand mb-0.5">Wyoming</div>
                        <div class="text-[10px] text-graphite font-bold uppercase tracking-widest">3 clinics</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Major Cities Section -->
        <section class="py-20 bg-offwhite border-t border-gray-light">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <h2 class="text-4xl font-extrabold text-charcoal tracking-tight uppercase">Major Cities</h2>
                    <p class="mt-2 text-graphite">Leading experts in the busiest metropolitan areas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- New York -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">New York</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">NY</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">84 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Los Angeles -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Los Angeles</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">CA</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">92 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Chicago -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Chicago</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">IL</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">55 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Houston -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Houston</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">TX</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">48 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Phoenix -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Phoenix</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">AZ</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">39 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Philadelphia -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Philadelphia</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">PA</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">32 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- San Antonio -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">San Antonio</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">TX</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">29 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- San Diego -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">San Diego</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">CA</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">35 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Dallas -->
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-light hover:shadow-md transition-all duration-300 cursor-pointer group flex flex-col justify-between h-full min-h-[160px]">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-light/40 rounded-full group-hover:bg-brand-light transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Dallas</h3>
                            <div class="flex flex-col mb-4">
                                <span class="text-brand font-black text-sm uppercase tracking-widest">TX</span>
                                <span class="text-graphite font-bold text-xs uppercase tracking-wider">41 Clinics</span>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center text-charcoal font-black text-xs uppercase tracking-[0.15em] hover:text-brand transition-colors mt-auto">
                            Browse directory 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- The Path to Clear Skin Section -->
        <section class="py-24 bg-brand-light relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-4xl font-extrabold text-charcoal tracking-tight uppercase">The Path to Clear Skin</h2>
                    <p class="mt-4 text-xl text-graphite font-medium">We've removed the stress from tattoo removal by providing verified information.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                    <!-- Step 1 -->
                    <div class="bg-white p-10 rounded-3xl shadow-sm border border-brand-light hover:shadow-xl transition-shadow duration-500">
                        <div class="w-16 h-16 bg-brand text-white rounded-2xl flex items-center justify-center text-2xl font-black mx-auto mb-8 shadow-lg rotate-3">1</div>
                        <h3 class="text-2xl font-bold mb-4 uppercase tracking-tight text-charcoal">Search Location</h3>
                        <p class="text-graphite leading-relaxed font-medium">Enter your city or zip code to find the highest-rated clinics closest to you.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white p-10 rounded-3xl shadow-sm border border-brand-light hover:shadow-xl transition-shadow duration-500">
                        <div class="w-16 h-16 bg-brand text-white rounded-2xl flex items-center justify-center text-2xl font-black mx-auto mb-8 shadow-lg -rotate-2">2</div>
                        <h3 class="text-2xl font-bold mb-4 uppercase tracking-tight text-charcoal">Compare Tech</h3>
                        <p class="text-graphite leading-relaxed font-medium">Examine pricing structures, verified patient reviews, and the specific laser technologies used.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white p-10 rounded-3xl shadow-sm border border-brand-light hover:shadow-xl transition-shadow duration-500">
                        <div class="w-16 h-16 bg-brand text-white rounded-2xl flex items-center justify-center text-2xl font-black mx-auto mb-8 shadow-lg rotate-1">3</div>
                        <h3 class="text-2xl font-bold mb-4 uppercase tracking-tight text-charcoal">Book Safely</h3>
                        <p class="text-graphite leading-relaxed font-medium">Schedule your free initial consultation directly with your chosen specialist.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-24 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-extrabold text-charcoal tracking-tight uppercase">Answers You Need</h2>
                    <p class="mt-2 text-graphite font-medium">New to tattoo removal? Here is what most people ask.</p>
                </div>

                <div class="space-y-16">
                    <!-- FAQ 1 -->
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-charcoal tracking-tight uppercase border-l-4 border-brand pl-6">Does tattoo removal hurt?</h3>
                        <div class="pl-6">
                            <p class="text-graphite leading-relaxed text-lg font-medium">Most patients compare the sensation to a rubber band snapping against the skin. Most clinics use cooling systems or numbing cream to minimize discomfort.</p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-charcoal tracking-tight uppercase border-l-4 border-brand pl-6">How many sessions will I need?</h3>
                        <div class="pl-6">
                            <p class="text-graphite leading-relaxed text-lg font-medium">The average is between 5 to 10 sessions, depending on the age, density, color, and location of the tattoo.</p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-charcoal tracking-tight uppercase border-l-4 border-brand pl-6">Will it leave a scar?</h3>
                        <div class="pl-6">
                            <p class="text-graphite leading-relaxed text-lg font-medium">When performed by a certified professional with proper aftercare, scarring is rare. However, some temporary hypopigmentation (lightening of skin) can occur.</p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-charcoal tracking-tight uppercase border-l-4 border-brand pl-6">How much does it cost?</h3>
                        <div class="pl-6">
                            <p class="text-graphite leading-relaxed text-lg font-medium">Prices vary by size, but typically range from $100 to $500 per session. Consultations are usually free.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

<?php get_footer(); ?>
