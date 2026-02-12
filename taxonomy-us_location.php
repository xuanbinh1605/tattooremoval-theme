<?php
/**
 * Taxonomy Template: US Location Archive
 * Displays clinics for a specific US state or city
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();

// Get current term and check for URL parameters
$current_term = get_queried_object();
$location_state = isset($_GET['location_state']) ? sanitize_text_field($_GET['location_state']) : '';
$location_city = isset($_GET['location_city']) ? sanitize_text_field($_GET['location_city']) : '';

// Determine if using URL parameters or taxonomy routing
$using_url_params = !empty($location_state) || !empty($location_city);

// Initialize location data
$location_name = '';
$is_state = false;
$location_term_ids = array();

if ($using_url_params) {
    // Using URL parameters - find matching terms
    if (!empty($location_city) && !empty($location_state)) {
        // Looking for a specific city in a state
        $location_name = $location_city;
        $state_term = get_term_by('name', $location_state, 'us_location');
        if ($state_term) {
            $city_term = get_term_by('name', $location_city, 'us_location');
            if ($city_term && $city_term->parent == $state_term->term_id) {
                $location_term_ids[] = $city_term->term_id;
            }
        }
    } elseif (!empty($location_state)) {
        // Looking for all clinics in a state
        $location_name = $location_state;
        $is_state = true;
        $state_term = get_term_by('name', $location_state, 'us_location');
        if ($state_term) {
            $location_term_ids[] = $state_term->term_id;
            // Get all child cities
            $cities = get_terms(array(
                'taxonomy'   => 'us_location',
                'hide_empty' => false,
                'parent'     => $state_term->term_id,
                'fields'     => 'ids',
            ));
            if (!empty($cities)) {
                $location_term_ids = array_merge($location_term_ids, $cities);
            }
        }
    }
} else {
    // Using standard taxonomy routing
    $location_name = $current_term->name;
    $is_state = ($current_term->parent == 0);
    
    // If it's a state, get all child cities
    $location_term_ids = array($current_term->term_id);
    if ($is_state) {
        $cities = get_terms(array(
            'taxonomy'   => 'us_location',
            'hide_empty' => false,
            'parent'     => $current_term->term_id,
            'fields'     => 'ids',
        ));
        if (!empty($cities)) {
            $location_term_ids = array_merge($location_term_ids, $cities);
        }
    }
}

// Build query args
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query_args = array(
    'post_type'      => 'clinic',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'meta_key'       => '_clinic_rating',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
);

// Add taxonomy query if we have location terms
if (!empty($location_term_ids)) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'us_location',
            'field'    => 'term_id',
            'terms'    => $location_term_ids,
            'operator' => 'IN',
        ),
    );
}

// Query clinics
$clinics_query = new WP_Query($query_args);
$total_clinics = $clinics_query->found_posts;

// Debug information (remove after testing)
if (current_user_can('administrator') && isset($_GET['debug'])) {
    echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 2px solid #333;">';
    echo '<h3>Debug Information</h3>';
    echo '<p><strong>Current Term:</strong> ' . ($current_term ? $current_term->name . ' (ID: ' . $current_term->term_id . ')' : 'None') . '</p>';
    echo '<p><strong>Using URL Params:</strong> ' . ($using_url_params ? 'Yes' : 'No') . '</p>';
    echo '<p><strong>Location Name:</strong> ' . esc_html($location_name) . '</p>';
    echo '<p><strong>Is State:</strong> ' . ($is_state ? 'Yes' : 'No') . '</p>';
    echo '<p><strong>Location Term IDs:</strong> ' . implode(', ', $location_term_ids) . '</p>';
    echo '<p><strong>Total Clinics Found:</strong> ' . $total_clinics . '</p>';
    echo '<p><strong>Query Args:</strong></p>';
    echo '<pre>' . print_r($query_args, true) . '</pre>';
    echo '<p><strong>SQL Query:</strong></p>';
    echo '<pre>' . $clinics_query->request . '</pre>';
    echo '</div>';
}
?>

<main class="flex-grow">
    <div class="min-h-screen bg-white">
        
        <!-- Breadcrumb & Header -->
        <div class="border-b border-gray-light bg-white py-4">
            <div class="max-w-[1440px] mx-auto px-4 md:px-8">
                <!-- Breadcrumb -->
                <nav class="flex text-[11px] font-bold text-graphite space-x-2 uppercase tracking-tight mb-2">
                    <a class="hover:underline" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>›</span>
                    <a class="hover:underline" href="#">Beauty &amp; Spas</a>
                    <span>›</span>
                    <span class="text-charcoal font-black">Tattoo Removal</span>
                </nav>
                
                <!-- Page Title & Sort -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <h1 class="text-2xl md:text-3xl font-black text-charcoal tracking-tight">
                        <?php 
                        if ($total_clinics > 0) {
                            echo 'Top ' . min($total_clinics, 10) . ' Best';
                        } else {
                            echo 'Best';
                        }
                        ?> Tattoo Removal Near <?php echo esc_html($location_name); ?>
                    </h1>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="text-graphite font-bold">Sort:</span>
                        <button class="flex items-center font-black text-charcoal border border-gray-light px-3 py-1.5 rounded-lg hover:bg-offwhite">
                            Recommended
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down ml-2 w-4 h-4" aria-hidden="true">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Filters Sidebar -->
                <div class="hidden lg:block lg:col-span-2 space-y-8 pr-4 border-r border-gray-light">
                    <div>
                        <h2 class="text-sm font-black text-charcoal mb-4">Filters</h2>
                        <div class="space-y-6">
                            
                            <!-- Price Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Price</h3>
                                <div class="flex bg-offwhite p-1 rounded-xl border border-gray-light">
                                    <button class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all text-graphite hover:text-charcoal">$</button>
                                    <button class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all text-graphite hover:text-charcoal">$$</button>
                                    <button class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all text-graphite hover:text-charcoal">$$$</button>
                                    <button class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all text-graphite hover:text-charcoal">$$$$</button>
                                </div>
                            </div>

                            <!-- Suggested Filters -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Suggested</h3>
                                <div class="space-y-2.5">
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox">
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Open Now</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox">
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Verified License</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox">
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Online Booking</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Rating Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Rating</h3>
                                <div class="space-y-2.5">
                                    <?php for ($i = 5; $i >= 3; $i--) : ?>
                                        <label class="flex items-center group cursor-pointer">
                                            <input class="w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox">
                                            <div class="ml-3 flex items-center">
                                                <?php for ($s = 1; $s <= 5; $s++) : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-3 h-3 <?php echo $s <= $i ? 'text-amber fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                                    </svg>
                                                <?php endfor; ?>
                                                <span class="ml-1.5 text-[10px] font-black text-graphite">&amp; Up</span>
                                            </div>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <!-- Features Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Features</h3>
                                <div class="space-y-2.5">
                                    <?php
                                    $features = get_terms(array(
                                        'taxonomy'   => 'clinic_feature',
                                        'hide_empty' => false,
                                        'number'     => 5,
                                    ));
                                    if (!empty($features)) :
                                        foreach ($features as $feature) :
                                    ?>
                                        <label class="flex items-center group cursor-pointer">
                                            <input class="w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox">
                                            <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors"><?php echo esc_html($feature->name); ?></span>
                                        </label>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Clinic Listings -->
                <div class="col-span-1 lg:col-span-7">
                    <?php if ($clinics_query->have_posts()) : ?>
                        <div class="space-y-12">
                            <?php 
                            $counter = 1;
                            while ($clinics_query->have_posts()) : $clinics_query->the_post();
                                $clinic_id = get_the_ID();
                                $rating = get_post_meta($clinic_id, '_clinic_rating', true) ?: 0;
                                $review_count = get_post_meta($clinic_id, '_clinic_reviews_count', true) ?: 0;
                                $city = get_post_meta($clinic_id, '_clinic_city', true);
                                $price_range = get_post_meta($clinic_id, '_clinic_price_range_display', true);
                                $open_status = get_post_meta($clinic_id, '_clinic_open_status', true) ?: 'Open Now';
                                $years_in_business = get_post_meta($clinic_id, '_clinic_years_in_business', true);
                                $thumbnail = str_get_clinic_thumbnail($clinic_id, 'large', 'https://picsum.photos/400/300?random=' . $counter);
                            ?>
                                <div class="flex flex-col md:flex-row gap-6 pb-10 border-b border-gray-light hover:bg-offwhite/30 transition-colors p-4 -m-4 rounded-2xl group cursor-pointer">
                                    <!-- Clinic Image -->
                                    <div class="w-full md:w-[240px] h-[240px] shrink-0 rounded-2xl overflow-hidden shadow-sm relative">
                                        <img alt="<?php echo esc_attr(get_the_title()); ?>" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                             src="<?php echo esc_url($thumbnail); ?>">
                                    </div>

                                    <!-- Clinic Info -->
                                    <div class="flex-1 flex flex-col min-w-0 pt-4 px-4 pb-1">
                                        <div class="mb-4 space-y-2">
                                            <h2 class="text-xl font-black text-charcoal group-hover:text-brand transition-colors">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h2>
                                            
                                            <div class="flex items-center gap-2">
                                                <div class="flex text-amber">
                                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-4 h-4 <?php echo $i <= round((float)$rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                                        </svg>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="text-sm font-black text-charcoal"><?php echo number_format((float)$rating, 1); ?></span>
                                                <span class="text-sm text-graphite font-bold">(<?php echo $review_count; ?> reviews)</span>
                                            </div>
                                            
                                            <div class="flex items-center text-sm font-bold text-charcoal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 mr-1.5 text-gray-light" aria-hidden="true">
                                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                <span><?php echo esc_html($city ?: $location_name); ?></span>
                                                <span class="mx-3 text-gray-light">•</span>
                                                <span class="<?php echo (strpos(strtolower($open_status), 'closed') !== false) ? 'text-red-500' : 'text-teal'; ?>">
                                                    <?php echo esc_html($open_status); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col space-y-5 py-0.5">
                                            <div class="text-charcoal font-black tracking-widest uppercase text-[10px]"><?php echo $price_range ?: 'CONTACT FOR'; ?> PRICING</div>
                                            
                                            <div class="flex items-center text-xs font-black text-charcoal uppercase tracking-wider">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-3.5 h-3.5 mr-2 text-teal" aria-hidden="true">
                                                    <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                                                </svg>
                                                Advanced Laser Technology
                                            </div>
                                            
                                            <?php 
                                            $clinic_features = wp_get_post_terms($clinic_id, 'clinic_feature', array('number' => 3));
                                            if (!empty($clinic_features)) :
                                            ?>
                                                <div class="flex flex-wrap gap-x-6 gap-y-2 border-t border-offwhite pt-3">
                                                    <?php if ($years_in_business) : ?>
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-4.5 h-4.5 rounded-full bg-teal/10 flex items-center justify-center shrink-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-2.5 h-2.5 text-teal" aria-hidden="true">
                                                                    <path d="M20 6 9 17l-5-5"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-[10px] font-black text-charcoal uppercase tracking-widest leading-none"><?php echo esc_html($years_in_business); ?> years in business</span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php foreach ($clinic_features as $feature) : ?>
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-4.5 h-4.5 rounded-full bg-teal/10 flex items-center justify-center shrink-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-2.5 h-2.5 text-teal" aria-hidden="true">
                                                                    <path d="M20 6 9 17l-5-5"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-[10px] font-black text-charcoal uppercase tracking-widest leading-none"><?php echo esc_html($feature->name); ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-offwhite">
                                            <a href="<?php the_permalink(); ?>" class="flex items-center text-[10px] font-black text-charcoal uppercase tracking-widest hover:text-brand transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image w-4 h-4 mr-2" aria-hidden="true">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                                </svg>
                                                See Portfolio
                                            </a>
                                        </div>
                                    </div>

                                    <!-- CTA Button -->
                                    <div class="md:w-48 shrink-0 flex flex-col justify-between">
                                        <button class="w-full bg-brand text-white py-3 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-hover transition-all shadow-md shadow-brand/10 mb-4">
                                            Request Quote
                                        </button>
                                    </div>
                                </div>
                            <?php 
                                $counter++;
                            endwhile; 
                            wp_reset_postdata();
                            ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($clinics_query->max_num_pages > 1) : ?>
                            <div class="mt-12 flex justify-center">
                                <?php
                                echo paginate_links(array(
                                    'total'     => $clinics_query->max_num_pages,
                                    'current'   => $paged,
                                    'prev_text' => '← Previous',
                                    'next_text' => 'Next →',
                                    'type'      => 'list',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-graphite">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-charcoal mb-2">No Clinics Found</h2>
                            <p class="text-graphite">There are no clinics in <?php echo esc_html($location_name); ?> yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Sidebar -->
                <div class="hidden lg:block lg:col-span-3 space-y-8">
                    <div class="sticky top-24 space-y-6">
                        
                        <!-- Partner Listing CTA -->
                        <div class="bg-charcoal text-white rounded-3xl p-8 shadow-2xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand/10 blur-3xl group-hover:bg-brand/20 transition-all"></div>
                            <div class="relative z-10">
                                <div class="flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-5 h-5 text-brand mr-3" aria-hidden="true">
                                        <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-brand">Partner Listing</span>
                                </div>
                                <h3 class="text-xl font-black mb-4 leading-tight">Clinic Owners</h3>
                                <p class="text-slate-300 text-xs font-medium leading-relaxed mb-6">
                                    Add your clinic and get found by <span class="text-white font-black underline decoration-brand decoration-2 underline-offset-4">13,000 monthly visitors</span> looking for tattoo removal in <span class="text-brand font-black"><?php echo esc_html($location_name); ?></span>.
                                </p>
                                <button class="w-full bg-brand hover:bg-brand-hover text-white py-4 rounded-xl font-black uppercase tracking-widest text-[10px] transition-all shadow-xl shadow-brand/20 flex items-center justify-center">
                                    Let customers find you
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4 ml-2" aria-hidden="true">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Map -->
                        <div class="bg-white rounded-3xl border border-gray-light overflow-hidden shadow-sm h-64 relative group">
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-graphite mb-2">
                                        <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                                        <path d="M15 5.764v15"></path>
                                        <path d="M9 3.236v15"></path>
                                    </svg>
                                    <p class="text-sm text-graphite font-bold">Map View</p>
                                </div>
                            </div>
                            <button class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white text-charcoal px-6 py-2.5 rounded-full shadow-2xl border border-gray-light text-[10px] font-black uppercase tracking-widest flex items-center hover:bg-brand hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map w-3.5 h-3.5 mr-2" aria-hidden="true">
                                    <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                                    <path d="M15 5.764v15"></path>
                                    <path d="M9 3.236v15"></path>
                                </svg>
                                Expand Map
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<?php
get_footer();
