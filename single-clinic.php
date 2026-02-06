<?php
/**
 * Single Clinic Template
 * Displays individual clinic details
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();

while (have_posts()) : the_post();
    $clinic_id = get_the_ID();
    
    // Get meta data
    $rating = get_post_meta($clinic_id, '_rating', true) ?: 4.5;
    $review_count = get_post_meta($clinic_id, '_reviews_count', true) ?: 0;
    $phone = get_post_meta($clinic_id, '_phone', true);
    $website = get_post_meta($clinic_id, '_website', true);
    $is_verified = get_post_meta($clinic_id, '_is_verified', true);
    $open_status = get_post_meta($clinic_id, '_open_status', true) ?: 'Open Now';
    $min_price = get_post_meta($clinic_id, '_min_price', true) ?: 90;
    $max_price = get_post_meta($clinic_id, '_max_price', true) ?: 250;
    $street = get_post_meta($clinic_id, '_street', true);
    $zip_code = get_post_meta($clinic_id, '_zip_code', true);
    $full_address = get_post_meta($clinic_id, '_full_address', true);
    $operating_hours = get_post_meta($clinic_id, '_operating_hours_raw', true);
    $years_in_business = get_post_meta($clinic_id, '_years_in_business', true);
    
    // Get location
    $locations = wp_get_post_terms($clinic_id, 'us_location');
    $city_term = !empty($locations) ? $locations[0] : null;
    $city = $city_term ? $city_term->name : '';
    $state_term = null;
    $state_acronym = '';
    
    if ($city_term && $city_term->parent != 0) {
        $state_term = get_term($city_term->parent, 'us_location');
        $state_acronym = get_term_meta($state_term->term_id, 'us_location_acronym', true);
    }
    
    // Get features
    $features = wp_get_post_terms($clinic_id, 'clinic_feature', array('number' => 4));
    
    // Get laser technologies
    $laser_tech_ids = get_post_meta($clinic_id, '_laser_technologies', true);
    $laser_techs = array();
    if ($laser_tech_ids) {
        $ids = explode(',', $laser_tech_ids);
        foreach ($ids as $id) {
            $tech = get_post($id);
            if ($tech) {
                $laser_techs[] = array(
                    'title' => $tech->post_title,
                    'description' => get_post_meta($id, '_short_description', true),
                );
            }
        }
    }
    
    // Get gallery images
    $gallery_ids = get_post_meta($clinic_id, '_before_after_gallery', true);
    $gallery_images = array();
    if ($gallery_ids) {
        $ids = explode(',', $gallery_ids);
        foreach ($ids as $id) {
            $img_url = wp_get_attachment_url($id);
            if ($img_url) {
                $gallery_images[] = $img_url;
            }
        }
    }
    
    // Fallback images
    if (empty($gallery_images)) {
        $gallery_images = array(
            get_the_post_thumbnail_url($clinic_id, 'large') ?: 'https://placehold.co/800x600',
        );
    }
    
    $thumbnail = get_the_post_thumbnail_url($clinic_id, 'large') ?: 'https://placehold.co/400x300';
?>

<main class="flex-grow">
    <div class="bg-white min-h-screen">
        
        <!-- Breadcrumb -->
        <div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex text-[11px] font-bold text-graphite space-x-1 uppercase tracking-wider">
                    <a class="hover:text-brand transition-colors" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="text-gray-light">›</span>
                    <span class="hover:text-brand transition-colors">Tattoo Removal</span>
                    <?php if ($state_acronym && $state_term) : ?>
                        <span class="text-gray-light">›</span>
                        <a class="hover:text-brand transition-colors" href="<?php echo esc_url(get_term_link($state_term)); ?>"><?php echo esc_html($state_acronym); ?></a>
                    <?php endif; ?>
                    <span class="text-gray-light">›</span>
                    <span class="text-charcoal"><?php the_title(); ?></span>
                </nav>
            </div>

            <!-- Header Section -->
            <div class="bg-white border-b border-gray-light pb-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    <h1 class="text-3xl md:text-5xl font-black text-charcoal mb-3 tracking-tight"><?php the_title(); ?></h1>
                    <div class="flex flex-wrap items-center text-sm gap-4">
                        <!-- Rating -->
                        <div class="flex items-center text-amber">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 <?php echo $i <= round($rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                            <?php endfor; ?>
                            <span class="ml-1 font-black text-charcoal text-lg"><?php echo number_format($rating, 1); ?></span>
                            <span class="ml-1 text-graphite font-bold">(<?php echo esc_html($review_count); ?> reviews)</span>
                        </div>
                        
                        <span class="text-gray-light">|</span>
                        
                        <!-- Open Status -->
                        <div class="flex items-center font-bold text-teal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 mr-2" aria-hidden="true">
                                <path d="M12 6v6l4 2"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            <?php echo esc_html($open_status); ?>
                        </div>
                        
                        <?php if ($is_verified) : ?>
                            <span class="text-gray-light">|</span>
                            <div class="flex items-center font-bold text-teal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 mr-1.5" aria-hidden="true">
                                    <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                                    <path d="m9 11 3 3L22 4"></path>
                                </svg>
                                Verified Clinic
                            </div>
                        <?php endif; ?>
                        
                        <span class="text-gray-light">|</span>
                        
                        <!-- Price Range -->
                        <div class="font-bold text-charcoal uppercase tracking-widest text-[11px]">
                            $<?php echo esc_html($min_price); ?> - $<?php echo esc_html($max_price); ?> per session
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-offwhite">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Content -->
                <div class="lg:col-span-2 space-y-12">
                    
                    <!-- Gallery Section -->
                    <div id="gallery-section" class="scroll-mt-32 space-y-6">
                        <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-light bg-white">
                            <div class="relative w-full h-[350px] md:h-[450px] bg-slate-900">
                                <img alt="<?php the_title(); ?>" class="w-full h-full object-cover" src="<?php echo esc_url($gallery_images[0]); ?>">
                                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                                <?php if (count($gallery_images) > 1) : ?>
                                    <div class="absolute top-4 right-4 px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-[10px] font-black text-white uppercase tracking-widest border border-white/10 z-10">
                                        1 / <?php echo count($gallery_images); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Features Grid -->
                        <?php if (!empty($features)) : ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-6 px-4 bg-white rounded-2xl border border-gray-light">
                                <?php foreach ($features as $feature) : ?>
                                    <div class="flex items-center text-charcoal font-black text-xs uppercase tracking-wider">
                                        <div class="bg-teal/10 p-1.5 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <?php echo esc_html($feature->name); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Portfolio Section -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-light shadow-sm">
                        <div class="flex items-center mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-split-vertical w-6 h-6 text-brand mr-3" aria-hidden="true">
                                <path d="M5 8V5c0-1 1-2 2-2h10c1 0 2 1 2 2v3"></path>
                                <path d="M19 16v3c0 1-1 2-2 2H7c-1 0-2-1-2-2v-3"></path>
                                <line x1="4" x2="20" y1="12" y2="12"></line>
                            </svg>
                            <h2 class="text-2xl font-black text-charcoal uppercase tracking-tight">About</h2>
                        </div>
                        <div class="text-graphite leading-relaxed font-medium">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <!-- Laser Technology Section -->
                    <?php if (!empty($laser_techs)) : ?>
                        <section id="tech-section" class="scroll-mt-32 bg-white p-8 rounded-2xl border border-gray-light shadow-sm">
                            <div class="flex items-center mb-8">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-6 h-6 text-brand mr-3" aria-hidden="true">
                                    <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                                </svg>
                                <h2 class="text-2xl font-black text-charcoal uppercase tracking-tight">Laser Technology</h2>
                            </div>
                            <?php foreach ($laser_techs as $tech) : ?>
                                <div class="bg-brand-light/20 p-8 rounded-2xl border border-brand/10 mb-6 last:mb-0">
                                    <h3 class="text-xl font-black text-brand mb-3 uppercase tracking-wider"><?php echo esc_html($tech['title']); ?></h3>
                                    <?php if ($tech['description']) : ?>
                                        <p class="text-graphite leading-relaxed font-medium"><?php echo esc_html($tech['description']); ?></p>
                                    <?php endif; ?>
                                    <div class="mt-6 flex flex-wrap gap-3">
                                        <span class="bg-white px-3 py-1 rounded-full text-[9px] font-black text-brand uppercase tracking-widest border border-brand/20 shadow-sm">FDA Cleared</span>
                                        <span class="bg-white px-3 py-1 rounded-full text-[9px] font-black text-brand uppercase tracking-widest border border-brand/20 shadow-sm">Safe for all skin types</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- What People Say Section -->
                    <section class="bg-charcoal text-white p-8 rounded-3xl shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-brand/10 blur-[100px] -mr-24 -mt-24"></div>
                        <div class="relative z-10">
                            <div class="flex items-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square w-6 h-6 text-brand mr-3" aria-hidden="true">
                                    <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <h2 class="text-2xl font-black uppercase tracking-tight">What People Say</h2>
                            </div>
                            <div class="bg-white/5 border border-white/10 p-6 rounded-2xl">
                                <p class="text-slate-200 leading-relaxed text-lg font-medium italic">
                                    Patients consistently praise this clinic for its exceptional cleanliness and professional staff. Reviews highlight the effectiveness of their laser technology and high safety standards.
                                </p>
                            </div>
                            <div class="mt-6 flex items-center">
                                <div class="flex -space-x-3 mr-4">
                                    <?php for ($i = 11; $i <= 14; $i++) : ?>
                                        <img class="w-8 h-8 rounded-full border-2 border-charcoal" alt="user" src="https://i.pravatar.cc/100?u=<?php echo $i; ?>">
                                    <?php endfor; ?>
                                </div>
                                <span class="text-xs font-black uppercase tracking-widest text-slate-400">Based on <?php echo esc_html($review_count); ?> verified patient reviews</span>
                            </div>
                        </div>
                    </section>

                    <!-- Reviews Section -->
                    <section id="reviews-section" class="scroll-mt-32 bg-white p-8 rounded-2xl border border-gray-light shadow-sm">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 pb-6 border-b border-offwhite">
                            <h2 class="text-2xl font-black text-charcoal uppercase tracking-tight flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-6 h-6 text-amber mr-3 fill-current" aria-hidden="true">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                                Reviews
                            </h2>
                        </div>
                        
                        <div class="text-center py-8">
                            <p class="text-graphite">Review features coming soon.</p>
                        </div>
                    </section>

                    <!-- Hours Section -->
                    <section id="hours-section" class="scroll-mt-32 bg-white p-8 md:p-12 rounded-2xl border border-gray-light shadow-sm w-full">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 border-b border-offwhite pb-6 gap-4">
                            <h2 class="text-2xl font-black text-charcoal uppercase tracking-tight flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-6 h-6 text-brand mr-3" aria-hidden="true">
                                    <path d="M12 6v6l4 2"></path>
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                                Working Hours
                            </h2>
                            <div class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-[0.15em] border flex items-center bg-teal/10 border-teal text-teal">
                                <span class="w-2 h-2 rounded-full mr-2 bg-teal animate-pulse"></span>
                                <?php echo esc_html($open_status); ?>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-12">
                            <div class="flex-1 space-y-3">
                                <?php if ($operating_hours) : ?>
                                    <div class="text-sm font-medium text-charcoal whitespace-pre-line"><?php echo esc_html($operating_hours); ?></div>
                                <?php else : ?>
                                    <p class="text-graphite">Hours not available</p>
                                <?php endif; ?>
                                
                                <div class="mt-8">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-graphite mb-2">Location Details</p>
                                    <p class="text-charcoal font-bold text-sm leading-relaxed">
                                        <?php 
                                        if ($full_address) {
                                            echo nl2br(esc_html($full_address));
                                        } else {
                                            echo esc_html($street);
                                            if ($city) echo '<br>' . esc_html($city);
                                            if ($zip_code) echo ', ' . esc_html($zip_code);
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 aspect-video md:aspect-auto h-[300px] rounded-2xl overflow-hidden border border-gray-light shadow-inner relative">
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-graphite mb-2">
                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <p class="text-sm text-graphite font-bold">Map</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        
                        <!-- Contact Card -->
                        <div class="bg-white border border-gray-light rounded-2xl shadow-2xl p-8 space-y-8">
                            <div>
                                <h3 class="text-[10px] font-black text-graphite uppercase tracking-[0.2em] mb-6">Contact & Location</h3>
                                <div class="space-y-6">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-5 h-5 text-brand mr-4 mt-1 flex-shrink-0" aria-hidden="true">
                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <div>
                                            <p class="font-black text-charcoal text-base">
                                                <?php echo $street ? esc_html($street) : '123 Main St'; ?>
                                            </p>
                                            <p class="text-graphite font-bold">
                                                <?php 
                                                echo $city ? esc_html($city) : 'City';
                                                if ($state_acronym) echo ', ' . esc_html($state_acronym);
                                                if ($zip_code) echo ' ' . esc_html($zip_code);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php if ($phone) : ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-5 h-5 text-brand mr-4 flex-shrink-0" aria-hidden="true">
                                                <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path>
                                            </svg>
                                            <a href="tel:<?php echo esc_attr($phone); ?>" class="font-black text-charcoal text-base hover:text-brand transition-colors">
                                                <?php echo esc_html($phone); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button class="w-full bg-brand text-white py-5 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-brand-hover transition-all shadow-xl shadow-brand/20">
                                Request A Quote
                            </button>
                        </div>

                        <!-- Nearby Clinics -->
                        <div class="bg-white border border-gray-light rounded-3xl p-6 shadow-sm">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Nearby Clinics</h3>
                            <div class="flex items-center text-graphite mb-6">
                                <span class="text-xs font-bold uppercase tracking-widest">Sponsored</span>
                            </div>
                            <div class="space-y-8">
                                <?php
                                // Get related clinics
                                $related = get_posts(array(
                                    'post_type' => 'clinic',
                                    'posts_per_page' => 3,
                                    'post__not_in' => array($clinic_id),
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'us_location',
                                            'field' => 'term_id',
                                            'terms' => $city_term ? $city_term->term_id : 0,
                                        ),
                                    ),
                                ));
                                
                                if (empty($related)) {
                                    $related = get_posts(array(
                                        'post_type' => 'clinic',
                                        'posts_per_page' => 3,
                                        'post__not_in' => array($clinic_id),
                                        'orderby' => 'rand',
                                    ));
                                }
                                
                                foreach ($related as $rel_clinic) :
                                    $rel_rating = get_post_meta($rel_clinic->ID, '_rating', true) ?: 4.5;
                                    $rel_thumb = get_the_post_thumbnail_url($rel_clinic->ID, 'thumbnail') ?: 'https://placehold.co/100x100';
                                ?>
                                    <div class="group cursor-pointer">
                                        <a href="<?php echo esc_url(get_permalink($rel_clinic->ID)); ?>" class="flex gap-4 items-start">
                                            <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
                                                <img alt="<?php echo esc_attr($rel_clinic->post_title); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform" src="<?php echo esc_url($rel_thumb); ?>">
                                            </div>
                                            <div class="flex-grow">
                                                <h4 class="text-[15px] font-black text-charcoal line-clamp-1 leading-tight mb-1 group-hover:text-brand transition-colors"><?php echo esc_html($rel_clinic->post_title); ?></h4>
                                                <div class="flex items-center gap-1.5 mb-0.5">
                                                    <div class="flex text-brand">
                                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-3.5 h-3.5 <?php echo $i <= round($rel_rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                                                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="text-xs font-black text-charcoal"><?php echo number_format($rel_rating, 1); ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<?php
endwhile;
get_footer();
