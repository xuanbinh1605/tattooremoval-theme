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
    $open_status = get_post_meta($clinic_id, '_clinic_open_status', true) ?: 'Open Now';
    $min_price = get_post_meta($clinic_id, '_clinic_min_price', true) ?: 90;
    $max_price = get_post_meta($clinic_id, '_clinic_max_price', true) ?: 250;
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
                            <a href="#tech-section" class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-colors py-1 border-b-2 text-graphite border-transparent hover:text-charcoal">Technology</a>
                            <a href="#director-section" class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-colors py-1 border-b-2 text-graphite border-transparent hover:text-charcoal">Director</a>
                            <a href="#hours-section" class="text-xs font-black uppercase tracking-widest whitespace-nowrap transition-colors py-1 border-b-2 text-graphite border-transparent hover:text-charcoal">Hours</a>
                        </div>
                    </div>
                    <button class="bg-brand text-white px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-brand-hover transition-all shadow-md ml-4">Get Quote</button>
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
                            $<?php echo esc_html($min_price); ?> <?php if ($max_price) : ?>- $<?php echo esc_html($max_price); ?><?php endif; ?> <?php echo ($min_price && !$max_price) ? 'min' : ''; ?> per session
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

                    <!-- Portfolio Section -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-light shadow-sm overflow-hidden">
                        <div class="flex items-center mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-split-vertical w-6 h-6 text-brand mr-3" aria-hidden="true">
                                <path d="M5 8V5c0-1 1-2 2-2h10c1 0 2 1 2 2v3"></path>
                                <path d="M19 16v3c0 1-1 2-2 2H7c-1 0-2-1-2-2v-3"></path>
                                <line x1="4" x2="20" y1="12" y2="12"></line>
                            </svg>
                            <h2 class="text-2xl font-black text-charcoal uppercase tracking-tight">Portfolio</h2>
                        </div>
                        <div class="rounded-xl overflow-hidden border border-gray-light">
                            <div class="relative w-full h-[350px] md:h-[450px] bg-slate-900 group">
                                <img alt="Portfolio Image" class="w-full h-full object-cover transition-opacity duration-500" src="<?php echo esc_url($gallery_images[0]); ?>">
                                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-12 inset-x-0 px-8 pb-4 animate-in fade-in slide-in-from-bottom-2 duration-500">
                                    <div class="flex items-start bg-black/40 backdrop-blur-md border border-white/10 rounded-xl p-4 max-w-2xl mx-auto shadow-2xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info w-4 h-4 text-brand mr-3 mt-0.5 shrink-0" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 16v-4"></path>
                                            <path d="M12 8h.01"></path>
                                        </svg>
                                        <p class="text-[11px] md:text-xs font-black text-white uppercase tracking-[0.1em] leading-relaxed italic">
                                            Case Study: Medium Tattoo • 6 Sessions to Complete Clearance
                                        </p>
                                    </div>
                                </div>
                                <?php if (count($gallery_images) > 1) : ?>
                                    <button class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/40 transition-all opacity-0 group-hover:opacity-100 z-20">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left" aria-hidden="true">
                                            <path d="m15 18-6-6 6-6"></path>
                                        </svg>
                                    </button>
                                    <button class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/40 transition-all opacity-0 group-hover:opacity-100 z-20">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right" aria-hidden="true">
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-6 w-full flex justify-center items-center space-x-2 z-10">
                                        <?php foreach ($gallery_images as $idx => $img) : ?>
                                            <button class="h-1.5 rounded-full transition-all duration-300 <?php echo $idx === 0 ? 'bg-white w-8' : 'bg-white/40 w-2 hover:bg-white/60'; ?> shadow-sm" aria-label="Go to slide <?php echo $idx + 1; ?>"></button>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="absolute top-4 right-4 px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-[10px] font-black text-white uppercase tracking-widest border border-white/10 z-10">
                                        1 / <?php echo count($gallery_images); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
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
                                        <span class="bg-white px-3 py-1 rounded-full text-[9px] font-black text-brand uppercase tracking-widest border border-brand/20 shadow-sm">Painless Cooling System</span>
                                        <span class="bg-white px-3 py-1 rounded-full text-[9px] font-black text-brand uppercase tracking-widest border border-brand/20 shadow-sm">Safe for all skin types</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- What People Say Section -->
                    <?php if ($reviews_summary) : ?>
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
                                        "<?php echo esc_html($reviews_summary); ?>"
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
                    <?php endif; ?>

                    <!-- Director Section -->
                    <section id="director-section" class="scroll-mt-32 bg-white p-8 md:p-12 rounded-2xl border border-gray-light shadow-sm w-full overflow-hidden">
                        <h2 class="text-2xl font-black text-charcoal mb-10 uppercase tracking-tight border-b border-offwhite pb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-6 h-6 text-brand mr-3" aria-hidden="true">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Clinic Director
                        </h2>
                        <div class="flex flex-col md:flex-row gap-10 items-start">
                            <div class="flex-1 space-y-4">
                                <h3 class="text-2xl font-black text-charcoal uppercase tracking-tight">Dr. <?php echo esc_html(get_bloginfo('name')); ?></h3>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="px-3 py-1 bg-teal/10 text-teal text-[10px] font-black uppercase tracking-widest rounded-full flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-3 h-3 mr-1.5" aria-hidden="true">
                                            <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                            <circle cx="12" cy="8" r="6"></circle>
                                        </svg>
                                        Board Certified
                                    </span>
                                    <span class="px-3 py-1 bg-brand/10 text-brand text-[10px] font-black uppercase tracking-widest rounded-full flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open w-3 h-3 mr-1.5" aria-hidden="true">
                                            <path d="M12 7v14"></path>
                                            <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                                        </svg>
                                        MD, FAAD
                                    </span>
                                </div>
                                <p class="text-graphite leading-relaxed text-base font-medium">Board-certified dermatologist with extensive experience in laser tattoo removal and skin rejuvenation procedures.</p>
                            </div>
                            <div class="w-full md:w-72 flex-shrink-0">
                                <div class="aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl border-4 border-offwhite relative group">
                                    <img alt="Clinic Director" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1559839734-2b71f1536783?q=80&w=1000&auto=format&fit=crop">
                                </div>
                            </div>
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

<?php
endwhile;
get_footer();
