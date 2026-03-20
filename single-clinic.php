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
    $rating = get_post_meta($clinic_id, '_clinic_rating', true) ?: 4.5;
    $review_count = get_post_meta($clinic_id, '_clinic_reviews_count', true) ?: 0;
    $phone = get_post_meta($clinic_id, '_clinic_phone', true);
    $website = get_post_meta($clinic_id, '_clinic_website', true);
    $is_verified = get_post_meta($clinic_id, '_clinic_is_verified', true);
    $min_price = get_post_meta($clinic_id, '_clinic_min_price', true);
    $max_price = get_post_meta($clinic_id, '_clinic_max_price', true);
    $street = get_post_meta($clinic_id, '_clinic_street', true);
    $zip_code = get_post_meta($clinic_id, '_clinic_zip_code', true);
    $full_address = get_post_meta($clinic_id, '_clinic_full_address', true);
    $operating_hours = get_post_meta($clinic_id, '_clinic_operating_hours_raw', true);
    $years_in_business = get_post_meta($clinic_id, '_clinic_years_in_business', true);
    $reviews_summary = get_post_meta($clinic_id, '_clinic_reviews_summary', true);
    $google_maps_url = get_post_meta($clinic_id, '_clinic_google_maps_url', true);
    
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
    
    // DEBUG: Remove after testing
    echo '<!-- DEBUG Laser Tech Meta Value: "' . esc_html($laser_tech_ids) . '" -->';
    echo '<!-- DEBUG Clinic ID: ' . esc_html($clinic_id) . ' -->';
    
    if ($laser_tech_ids) {
        $ids = array_map('trim', explode(',', $laser_tech_ids));
        echo '<!-- DEBUG IDs Array: ' . esc_html(print_r($ids, true)) . ' -->';
        
        foreach ($ids as $id) {
            if (empty($id)) {
                echo '<!-- DEBUG: Skipped empty ID -->';
                continue;
            }
            
            $tech = get_post(intval($id));
            echo '<!-- DEBUG ID ' . esc_html($id) . ': Post exists=' . ($tech ? 'yes' : 'no') . ', Status=' . ($tech ? esc_html($tech->post_status) : 'N/A') . ' -->';
            
            if ($tech && $tech->post_status === 'publish') {
                $laser_techs[] = array(
                    'id' => $id,
                    'title' => $tech->post_title,
                    'description' => get_post_meta($id, '_short_description', true),
                    'technical_notes' => get_post_meta($id, '_technical_notes', true),
                    'official_website' => get_post_meta($id, '_official_website', true),
                    'image' => get_the_post_thumbnail_url($id, 'medium'),
                    'content' => $tech->post_content,
                );
                echo '<!-- DEBUG: Added laser tech "' . esc_html($tech->post_title) . '" -->';
            }
        }
    } else {
        echo '<!-- DEBUG: No laser tech IDs found in meta -->';
    }
    
    echo '<!-- DEBUG Total Laser Techs Found: ' . count($laser_techs) . ' -->';
    
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
            str_get_clinic_thumbnail($clinic_id, 'large', 'https://placehold.co/800x600'),
        );
    }
    

?>

<main class="flex-grow">
    <div class="bg-white min-h-screen">
        
        <!-- Sticky Navigation -->
        <div class="fixed top-20 left-0 right-0 z-40 bg-white border-b border-gray-light shadow-md transition-all duration-300 transform -translate-y-full opacity-0 pointer-events-none" id="sticky-nav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <div class="flex items-center space-x-8">
                        <span class="text-sm font-black text-charcoal hidden lg:block truncate max-w-[200px]"><?php the_title(); ?></span>
                        <div class="flex space-x-6 overflow-x-auto no-scrollbar py-2">
                            <a href="#gallery-section" class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-colors py-1 border-b-2 text-brand border-brand">Gallery</a>
                            <a href="#reviews-section" class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-colors py-1 border-b-2 text-graphite border-transparent hover:text-charcoal">Reviews</a>
                            <a href="#hours-section" class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-colors py-1 border-b-2 text-graphite border-transparent hover:text-charcoal">Hours</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 <?php echo $i <= round((float)$rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                            <?php endfor; ?>
                            <span class="ml-1 font-black text-charcoal text-lg"><?php echo number_format((float)$rating, 1); ?></span>
                            <span class="ml-1 text-graphite font-bold">(<?php echo esc_html($review_count); ?> reviews)</span>
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
                        
                        <?php if ($min_price || $max_price) : ?>
                            <span class="text-gray-light">|</span>
                            
                            <!-- Price Range -->
                            <div class="font-bold text-charcoal uppercase tracking-widest text-[11px]">
                                <?php if ($min_price) : ?>$<?php echo esc_html($min_price); ?><?php endif; ?>
                                <?php if ($min_price && $max_price) : ?> - <?php endif; ?>
                                <?php if ($max_price && $min_price != $max_price) : ?>$<?php echo esc_html($max_price); ?><?php endif; ?>
                                <?php if ($min_price && !$max_price) : ?> min<?php endif; ?>
                                per session
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Open/Closed Status -->
                    <div class="mt-4">
                        <?php $open_info = str_get_clinic_open_status($clinic_id); ?>
                        <span class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-widest <?php echo esc_attr($open_info['class']); ?>">
                            <span class="w-2 h-2 rounded-full <?php echo $open_info['status'] === 'open' ? 'bg-teal' : ($open_info['status'] === 'closing_soon' ? 'bg-amber' : 'bg-red-500'); ?>"></span>
                            <?php echo esc_html($open_info['text']); ?>
                        </span>
                    </div>
                    
                    <!-- Laser Technologies Names -->
                    <?php if (!empty($laser_techs)) : ?>
                    <div class="mt-6 pt-4 border-t border-gray-light">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-[10px] font-black text-graphite uppercase tracking-widest">Technologies:</span>
                            <?php foreach ($laser_techs as $tech) : ?>
                                <span class="inline-flex items-center px-3 py-1.5 bg-brand/10 text-brand rounded-full text-[10px] font-black uppercase tracking-wider border border-brand/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap mr-1.5" aria-hidden="true">
                                        <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                                    </svg>
                                    <?php echo esc_html($tech['title']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
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
                                            <svg

 xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <?php echo esc_html($feature->name); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Patient Reviews Section -->
                    <?php 
                    // Get all reviews for this clinic
                    $clinic_reviews = new WP_Query(array(
                        'post_type' => 'review',
                        'posts_per_page' => -1,
                        'meta_key' => '_review_clinic_id',
                        'meta_value' => $clinic_id,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post_status' => 'publish'
                    ));

                    if ($clinic_reviews->have_posts() || $reviews_summary) : 
                    ?>
                        <section id="reviews-section" class="scroll-mt-32 bg-white p-8 rounded-3xl border border-gray-light shadow-sm">
                            <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-light">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square w-6 h-6 text-brand mr-3" aria-hidden="true">
                                        <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <h2 class="text-2xl font-black uppercase tracking-tight text-charcoal">Patient Reviews</h2>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex text-amber">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star <?php echo $i <= round((float)$rating) ? 'fill-current' : 'text-gray-light'; ?>">
                                                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-lg font-black text-charcoal"><?php echo number_format((float)$rating, 1); ?></span>
                                    <span class="text-sm text-graphite font-bold">(<?php echo esc_html($clinic_reviews->found_posts ?: $review_count); ?> reviews)</span>
                                </div>
                            </div>

                            <?php if ($reviews_summary) : ?>
                                <!-- Overall Summary -->
                                <div class="bg-gradient-to-br from-brand-light/10 to-offwhite p-6 rounded-2xl mb-8 border border-brand/10">
                                    <p class="text-charcoal leading-relaxed text-base font-medium italic">
                                        "<?php echo esc_html($reviews_summary); ?>"
                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if ($clinic_reviews->have_posts()) : ?>
                                <!-- Individual Reviews -->
                                <div class="space-y-6">
                                    <?php while ($clinic_reviews->have_posts()) : $clinic_reviews->the_post(); 
                                        $review_id = get_the_ID();
                                        $reviewer_name = get_post_meta($review_id, '_review_reviewer_name', true) ?: 'Anonymous';
                                        $review_rating = get_post_meta($review_id, '_review_rating', true) ?: 5;
                                        $review_date = get_post_meta($review_id, '_review_date', true) ?: get_the_date('Y-m-d');
                                        $is_verified = get_post_meta($review_id, '_review_is_verified', true);
                                        $helpful_count = get_post_meta($review_id, '_review_helpful_count', true) ?: 0;
                                        $review_content = get_the_content();
                                        $review_title = get_the_title();
                                        $reviewer_avatar = get_the_post_thumbnail_url($review_id, 'thumbnail') ?: 'https://i.pravatar.cc/100?u=' . $review_id;
                                    ?>
                                        <div class="bg-offwhite p-6 rounded-xl border border-gray-light hover:border-brand/30 transition-all">
                                            <div class="flex items-start gap-4 mb-4">
                                                <!-- Reviewer Avatar -->
                                                <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border-2 border-brand/20">
                                                    <img src="<?php echo esc_url($reviewer_avatar); ?>" alt="<?php echo esc_attr($reviewer_name); ?>" class="w-full h-full object-cover">
                                                </div>
                                                
                                                <!-- Reviewer Info -->
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div>
                                                            <h3 class="text-lg font-black text-charcoal flex items-center gap-2">
                                                                <?php echo esc_html($reviewer_name); ?>
                                                                <?php if ($is_verified) : ?>
                                                                    <span class="bg-teal text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Verified</span>
                                                                <?php endif; ?>
                                                            </h3>
                                                            <p class="text-xs text-graphite font-bold"><?php echo date('F j, Y', strtotime($review_date)); ?></p>
                                                        </div>
                                                        <div class="flex text-amber">
                                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star <?php echo $i <= $review_rating ? 'fill-current' : 'text-gray-light'; ?>">
                                                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                                                </svg>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if ($review_title && $review_title !== 'Auto Draft') : ?>
                                                        <h4 class="text-base font-black text-charcoal mb-2"><?php echo esc_html($review_title); ?></h4>
                                                    <?php endif; ?>
                                                    
                                                    <div class="text-charcoal leading-relaxed text-sm font-medium">
                                                        <?php echo wpautop($review_content); ?>
                                                    </div>
                                                    
                                                    <?php if ($helpful_count > 0) : ?>
                                                        <div class="mt-4 flex items-center text-xs text-graphite font-bold">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-thumbs-up mr-1.5">
                                                                <path d="M7 10v12"></path>
                                                                <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"></path>
                                                            </svg>
                                                            <?php echo esc_html($helpful_count); ?> people found this helpful
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            <?php else : ?>
                                <div class="text-center py-8">
                                    <p class="text-graphite font-medium">No reviews yet. Be the first to share your experience!</p>
                                </div>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>



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
                            <?php $open_info = str_get_clinic_open_status($clinic_id); ?>
                            <span class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-widest <?php echo esc_attr($open_info['class']); ?>">
                                <span class="w-2 h-2 rounded-full <?php echo $open_info['status'] === 'open' ? 'bg-teal' : ($open_info['status'] === 'closing_soon' ? 'bg-amber' : 'bg-red-500'); ?>"></span>
                                <?php echo esc_html($open_info['text']); ?>
                            </span>
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
                            <div class="w-full md:w-1/2 aspect-video md:aspect-auto h-[300px] rounded-2xl overflow-hidden border border-gray-light shadow-inner relative group">
                                <?php if ($google_maps_url) : ?>
                                    <iframe width="100%" height="100%" frameborder="0" src="<?php echo esc_url($google_maps_url); ?>" allowfullscreen class="grayscale group-hover:grayscale-0 transition-all duration-500" style="border: 0;"></iframe>
                                <?php else : ?>
                                    <iframe width="100%" height="100%" frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=<?php echo urlencode($full_address ? $full_address : ($street . ', ' . $city . ', ' . $state_acronym . ' ' . $zip_code)); ?>" allowfullscreen class="grayscale group-hover:grayscale-0 transition-all duration-500" style="border: 0;"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        
                        <!-- Contact Card -->
                        <div class="bg-white border border-gray-light rounded-2xl shadow-2xl p-8 space-y-8">
                            <?php
                            $logo = get_post_meta($clinic_id, '_clinic_logo', true);
                            if ($logo) :
                            ?>
                            <div class="flex justify-center pb-6 border-b border-gray-light">
                                <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?> Logo" class="max-h-20 w-auto object-contain">
                            </div>
                            <?php endif; ?>
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
                            
                            <!-- Social Media Section -->
                            <?php 
                            $facebook = get_post_meta($clinic_id, '_clinic_facebook', true);
                            $twitter = get_post_meta($clinic_id, '_clinic_twitter', true);
                            $instagram = get_post_meta($clinic_id, '_clinic_instagram', true);
                            $youtube = get_post_meta($clinic_id, '_clinic_youtube', true);
                            $linkedin = get_post_meta($clinic_id, '_clinic_linkedin', true);
                            $tiktok = get_post_meta($clinic_id, '_clinic_tiktok', true);
                            
                            // Helper function to normalize social media URLs
                            $normalize_social_url = function($value, $platform) {
                                if (empty($value)) return '';
                                
                                // If it's already a URL, return as is
                                if (preg_match('/^https?:\/\//i', $value)) {
                                    return $value;
                                }
                                
                                // Remove @ and # symbols
                                $handle = ltrim($value, '@#');
                                
                                // Build URL based on platform
                                switch ($platform) {
                                    case 'facebook':
                                        return 'https://facebook.com/' . $handle;
                                    case 'twitter':
                                        return 'https://twitter.com/' . $handle;
                                    case 'instagram':
                                        return 'https://instagram.com/' . $handle;
                                    case 'youtube':
                                        // Handle both @channel and regular channel formats
                                        $prefix = strpos($handle, '@') === false ? '@' : '';
                                        return 'https://youtube.com/' . $prefix . $handle;
                                    case 'linkedin':
                                        return 'https://linkedin.com/company/' . $handle;
                                    case 'tiktok':
                                        $prefix = strpos($handle, '@') === false ? '@' : '';
                                        return 'https://tiktok.com/' . $prefix . $handle;
                                    default:
                                        return $value;
                                }
                            };
                            
                            // Normalize all social media URLs
                            $facebook_url = $normalize_social_url($facebook, 'facebook');
                            $twitter_url = $normalize_social_url($twitter, 'twitter');
                            $instagram_url = $normalize_social_url($instagram, 'instagram');
                            $youtube_url = $normalize_social_url($youtube, 'youtube');
                            $linkedin_url = $normalize_social_url($linkedin, 'linkedin');
                            $tiktok_url = $normalize_social_url($tiktok, 'tiktok');
                            
                            $has_social_media = $facebook || $twitter || $instagram || $youtube || $linkedin || $tiktok;
                            
                            if ($has_social_media) : ?>
                            <div class="pt-6 border-t border-gray-light">
                                <h4 class="text-[10px] font-black text-graphite uppercase tracking-[0.2em] mb-4">Follow Us</h4>
                                <div class="flex flex-wrap gap-3">
                                    <?php if ($facebook) : ?>
                                        <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-[#1877F2] text-white rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter_url); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-[#000000] text-white rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-[#833AB4] via-[#FD1D1D] to-[#F77737] text-white rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($youtube) : ?>
                                        <a href="<?php echo esc_url($youtube_url); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-[#FF0000] text-white rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($linkedin) : ?>
                                        <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-[#0A66C2] text-white rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($tiktok) : ?>
                                        <a href="<?php echo esc_url($tiktok_url); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-[#000000] text-white rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Laser Technology Section -->
                            <?php if (!empty($laser_techs)) : ?>
                            <div class="pt-6 border-t border-gray-light">
                                <div class="flex items-center justify-between mb-6">
                                    <h4 class="text-[10px] font-black text-graphite uppercase tracking-[0.2em]">Laser Technology</h4>
                                    <span class="text-xs font-black text-brand uppercase tracking-widest bg-brand/10 px-2 py-1 rounded-full"><?php echo count($laser_techs); ?></span>
                                </div>
                                <div class="space-y-4">
                                    <?php foreach ($laser_techs as $tech) : ?>
                                        <div class="bg-gradient-to-br from-brand-light/10 to-offwhite p-4 rounded-xl border border-brand/10">
                                            <?php if ($tech['image']) : ?>
                                            <div class="mb-4">
                                                <div class="aspect-square rounded-lg overflow-hidden border-2 border-brand/20 shadow-md">
                                                    <img src="<?php echo esc_url($tech['image']); ?>" alt="<?php echo esc_attr($tech['title']); ?>" class="w-full h-full object-cover">
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <h5 class="text-sm font-black text-brand mb-2 uppercase tracking-wider flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap text-brand mr-1.5 flex-shrink-0" aria-hidden="true">
                                                    <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                                                </svg>
                                                <?php echo esc_html($tech['title']); ?>
                                            </h5>
                                            
                                            <?php if ($tech['description']) : ?>
                                                <p class="text-xs text-graphite leading-relaxed font-medium mb-3"><?php echo nl2br(esc_html($tech['description'])); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if ($tech['technical_notes']) : ?>
                                                <div class="bg-white/60 border border-brand/20 rounded-lg p-3 mb-3">
                                                    <p class="text-[9px] font-black text-brand uppercase tracking-widest mb-1">Technical Specs</p>
                                                    <p class="text-xs text-charcoal leading-relaxed font-medium"><?php echo nl2br(esc_html($tech['technical_notes'])); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="flex flex-wrap gap-1.5">
                                                <span class="bg-white px-2 py-1 rounded-full text-[8px] font-black text-brand uppercase tracking-widest border border-brand/20 shadow-sm flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check mr-1">
                                                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                                        <path d="m9 12 2 2 4-4"></path>
                                                    </svg>
                                                    FDA Cleared
                                                </span>
                                                <span class="bg-white px-2 py-1 rounded-full text-[8px] font-black text-teal uppercase tracking-widest border border-teal/20 shadow-sm flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-snowflake mr-1">
                                                        <line x1="2" x2="22" y1="12" y2="12"></line>
                                                        <line x1="12" x2="12" y1="2" y2="22"></line>
                                                        <path d="m20 16-4-4 4-4"></path>
                                                        <path d="m4 8 4 4-4 4"></path>
                                                        <path d="m16 4-4 4-4-4"></path>
                                                        <path d="m8 20 4-4 4 4"></path>
                                                    </svg>
                                                    Advanced Cooling
                                                </span>
                                                <span class="bg-white px-2 py-1 rounded-full text-[8px] font-black text-charcoal uppercase tracking-widest border border-gray-light shadow-sm flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-palette mr-1">
                                                        <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"></circle>
                                                        <circle cx="17.5" cy="10.5" r=".5" fill="currentColor"></circle>
                                                        <circle cx="8.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                                        <circle cx="6.5" cy="12.5" r=".5" fill="currentColor"></circle>
                                                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path>
                                                    </svg>
                                                    All Skin Types
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Request Quote Button -->
                        <button onclick="openQuoteModal()" class="w-full bg-brand hover:bg-brand-dark text-white font-black uppercase tracking-widest py-4 rounded-lg transition-colors shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Request a Quote
                        </button>

                        <!-- Payment & Services -->
                        <?php
                        // Get payment & service meta data
                        $accepts_credit_cards = get_post_meta($clinic_id, '_clinic_accepts_credit_cards', true);
                        $accepts_debit_cards = get_post_meta($clinic_id, '_clinic_accepts_debit_cards', true);
                        $cash_only = get_post_meta($clinic_id, '_clinic_cash_only', true);
                        $accepts_checks = get_post_meta($clinic_id, '_clinic_accepts_checks', true);
                        $accepts_mobile_payments = get_post_meta($clinic_id, '_clinic_accepts_mobile_payments', true);
                        $financing = get_post_meta($clinic_id, '_clinic_financing', true);
                        $consultation_price = get_post_meta($clinic_id, '_clinic_consultation_price', true);
                        $medical_supervision = get_post_meta($clinic_id, '_clinic_medical_supervision', true);
                        $offers_packages = get_post_meta($clinic_id, '_clinic_offers_packages', true);
                        $online_scheduling = get_post_meta($clinic_id, '_clinic_online_scheduling', true);
                        $military_discount = get_post_meta($clinic_id, '_clinic_military_discount', true);
                        $wheelchair_accessible = get_post_meta($clinic_id, '_clinic_wheelchair_accessible', true);
                        
                        // Check if any payment/service data exists
                        $has_payment_info = $accepts_credit_cards || $accepts_debit_cards || $cash_only || $accepts_checks || $accepts_mobile_payments || $financing;
                        $has_service_info = $consultation_price || $medical_supervision || $offers_packages || $online_scheduling || $military_discount;
                        
                        if ($has_payment_info || $has_service_info) :
                        ?>
                        <div class="bg-white border border-gray-light rounded-2xl shadow-sm p-8 space-y-6">
                            <?php if ($has_payment_info) : ?>
                            <div>
                                <h3 class="text-[10px] font-black text-graphite uppercase tracking-[0.2em] mb-6">Payment Options</h3>
                                <div class="space-y-4">
                                    <div class="flex flex-wrap gap-3">
                                        <?php if ($accepts_credit_cards) : ?>
                                        <div class="flex items-center px-3 py-2 bg-offwhite rounded-lg border border-gray-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card w-4 h-4 text-brand mr-2" aria-hidden="true">
                                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                                <line x1="2" x2="22" y1="10" y2="10"></line>
                                            </svg>
                                            <span class="text-xs font-black text-charcoal">Credit Card</span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($accepts_debit_cards) : ?>
                                        <div class="flex items-center px-3 py-2 bg-offwhite rounded-lg border border-gray-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card w-4 h-4 text-brand mr-2" aria-hidden="true">
                                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                                <line x1="2" x2="22" y1="10" y2="10"></line>
                                            </svg>
                                            <span class="text-xs font-black text-charcoal">Debit Card</span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($cash_only || (!$accepts_credit_cards && !$accepts_debit_cards && !$accepts_checks && !$accepts_mobile_payments)) : ?>
                                        <div class="flex items-center px-3 py-2 bg-offwhite rounded-lg border border-gray-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote w-4 h-4 text-brand mr-2" aria-hidden="true">
                                                <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                                                <circle cx="12" cy="12" r="2"></circle>
                                                <path d="M6 12h.01M18 12h.01"></path>
                                            </svg>
                                            <span class="text-xs font-black text-charcoal"><?php echo $cash_only ? 'Cash Only' : 'Cash'; ?></span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($accepts_checks) : ?>
                                        <div class="flex items-center px-3 py-2 bg-offwhite rounded-lg border border-gray-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark w-4 h-4 text-brand mr-2" aria-hidden="true">
                                                <line x1="3" x2="21" y1="22" y2="22"></line>
                                                <line x1="6" x2="6" y1="18" y2="11"></line>
                                                <line x1="10" x2="10" y1="18" y2="11"></line>
                                                <line x1="14" x2="14" y1="18" y2="11"></line>
                                                <line x1="18" x2="18" y1="18" y2="11"></line>
                                                <polygon points="12 2 20 7 4 7"></polygon>
                                            </svg>
                                            <span class="text-xs font-black text-charcoal">Check</span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($accepts_mobile_payments) : ?>
                                        <div class="flex items-center px-3 py-2 bg-offwhite rounded-lg border border-gray-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone w-4 h-4 text-brand mr-2" aria-hidden="true">
                                                <rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect>
                                                <path d="M12 18h.01"></path>
                                            </svg>
                                            <span class="text-xs font-black text-charcoal">Mobile Pay</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($financing) : ?>
                                    <div class="bg-teal/5 border border-teal/20 rounded-xl p-4 mt-4">
                                        <div class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dollar-sign w-5 h-5 text-teal mr-3 mt-0.5 flex-shrink-0" aria-hidden="true">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                                <path d="M12 18V6"></path>
                                            </svg>
                                            <div>
                                                <p class="text-xs font-black text-teal uppercase tracking-wider mb-1">Financing Available</p>
                                                <p class="text-xs text-graphite font-medium leading-relaxed">Flexible payment plans to fit your budget</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="<?php echo $has_payment_info ? 'border-t border-gray-light pt-6' : ''; ?>">
                                <h3 class="text-[10px] font-black text-graphite uppercase tracking-[0.2em] mb-4">Services Offered</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <div class="bg-brand/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-brand" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Laser Tattoo Removal</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="bg-brand/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-brand" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Color Tattoo Removal</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="bg-brand/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-brand" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Tattoo Lightening</span>
                                    </div>
                                    <?php if ($consultation_price && (stripos($consultation_price, 'free') !== false || $consultation_price === '0')) : ?>
                                    <div class="flex items-center">
                                        <div class="bg-brand/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-brand" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Free Consultation</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($medical_supervision || $offers_packages || $online_scheduling || $military_discount || $wheelchair_accessible) : ?>
                                <div class="mt-4 pt-4 border-t border-gray-light space-y-2">
                                    <?php if ($medical_supervision) : ?>
                                    <div class="flex items-center">
                                        <div class="bg-teal/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Medical Supervision</span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($offers_packages) : ?>
                                    <div class="flex items-center">
                                        <div class="bg-teal/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Package Deals Available</span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($online_scheduling) : ?>
                                    <div class="flex items-center">
                                        <div class="bg-teal/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Online Scheduling</span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($military_discount) : ?>
                                    <div class="flex items-center">
                                        <div class="bg-teal/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Military Discount</span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($wheelchair_accessible) : ?>
                                    <div class="flex items-center">
                                        <div class="bg-teal/10 p-1 rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-3 h-3 text-teal" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-charcoal">Wheelchair Accessible</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Nearby Clinics -->
                        <div class="bg-white border border-gray-light rounded-3xl p-6 shadow-sm overflow-hidden">
                            <h3 class="text-xl font-black text-charcoal mb-0.5 tracking-tight">Top Rated Tattoo Removal Clinics Near <?php echo $city ? esc_html($city) : 'You'; ?></h3>
                            <div class="flex items-center text-graphite mb-6">
                                <span class="text-xs font-bold uppercase tracking-widest">Sponsored</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info w-3.5 h-3.5 ml-1.5 opacity-60" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 16v-4"></path>
                                    <path d="M12 8h.01"></path>
                                </svg>
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
                                
                                $distances = array(4.1, 6.4, 8.7); // Sample distances
                                foreach ($related as $idx => $rel_clinic) :
                                    $rel_rating = get_post_meta($rel_clinic->ID, '_clinic_rating', true) ?: 4.5;
                                    $rel_thumb = str_get_clinic_thumbnail($rel_clinic->ID, 'thumbnail', 'https://picsum.photos/400/300?random=' . ($idx + 1));
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
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-3.5 h-3.5 <?php echo $i <= round((float)$rel_rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                                                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="text-xs font-black text-charcoal"><?php echo number_format((float)$rel_rating, 1); ?></span>
                                                </div>
                                                <p class="text-[13px] font-black text-charcoal tracking-tight"><?php echo $distances[$idx]; ?> miles</p>
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

<!-- Quote Request Modal -->
<div id="quoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-charcoal uppercase tracking-tight">Request a Quote</h2>
                <button onclick="closeQuoteModal()" class="text-graphite hover:text-charcoal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="quoteForm" enctype="multipart/form-data">
                <input type="hidden" name="clinic_id" value="<?php echo $clinic_id; ?>">
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-black text-charcoal uppercase tracking-widest mb-4">Your Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Name *</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Email *</label>
                                <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Phone *</label>
                                <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Location (City, State) *</label>
                            <input type="text" name="location" required placeholder="e.g., Los Angeles, CA" class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                        </div>
                    </div>
                </div>

                <!-- Tattoo Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-black text-charcoal uppercase tracking-widest mb-4">Tattoo Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Tattoo Size *</label>
                            <select name="tattoo_size" required class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                                <option value="">Select size</option>
                                <option value="Small (< 2 inches)">Small (< 2 inches)</option>
                                <option value="Medium (2-6 inches)">Medium (2-6 inches)</option>
                                <option value="Large (6-12 inches)">Large (6-12 inches)</option>
                                <option value="Extra Large (> 12 inches)">Extra Large (> 12 inches)</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Colors</label>
                                <select name="tattoo_colors" class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                                    <option value="">Select colors</option>
                                    <option value="Black only">Black only</option>
                                    <option value="Black & Grey">Black & Grey</option>
                                    <option value="Multi-color">Multi-color</option>
                                    <option value="Colored (No Black)">Colored (No Black)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Body Location</label>
                                <input type="text" name="tattoo_location" placeholder="e.g., Arm, Back, Leg" class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Upload Tattoo Image</label>
                            <input type="file" name="tattoo_image" accept="image/*" class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand">
                            <p class="text-xs text-graphite mt-1">Accepted formats: JPG, PNG, GIF (Max 5MB)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-graphite uppercase tracking-wider mb-2">Additional Information</label>
                            <textarea name="additional_info" rows="3" placeholder="Any other details about your tattoo or questions..." class="w-full px-4 py-3 border border-gray-light rounded-lg focus:outline-none focus:border-brand"></textarea>
                        </div>
                    </div>
                </div>

                <div id="quoteFormMessage" class="mb-4 hidden"></div>

                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-black uppercase tracking-widest py-4 rounded-lg transition-colors">
                    Submit Quote Request
                </button>
            </form>
        </div>
    </div>
</div>

<?php
endwhile;
get_footer();
