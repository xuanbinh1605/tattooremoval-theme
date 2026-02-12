<?php
/**
 * Template Name: Location Search
 * Description: Search clinics by location using URL parameters
 * Usage: Create a page and assign this template, then use ?location_state=StateName&location_city=CityName
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

get_header();

// Get location parameters from URL
$location_state = isset($_GET['location_state']) ? sanitize_text_field($_GET['location_state']) : '';
$location_city = isset($_GET['location_city']) ? sanitize_text_field($_GET['location_city']) : '';

// Initialize variables
$location_name = '';
$is_state = false;
$location_term_ids = array();

if (!empty($location_state)) {
    // Find the state term
    $state_term = get_term_by('name', $location_state, 'us_location');
    
    if ($state_term) {
        if (!empty($location_city)) {
            // Looking for a specific city in a state
            $location_name = $location_city . ', ' . $location_state;
            $city_term = get_term_by('name', $location_city, 'us_location');
            if ($city_term && $city_term->parent == $state_term->term_id) {
                $location_term_ids[] = $city_term->term_id;
            }
        } else {
            // Looking for all clinics in a state
            $location_name = $location_state;
            $is_state = true;
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
}

// Build query args
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query_args = array(
    'post_type'      => 'clinic',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'meta_key'       => '_rating',
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

// DEBUG MODE - Add ?debug=1 to URL to see this
if (current_user_can('administrator') && isset($_GET['debug'])) {
    echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 2px solid #333; font-family: monospace; font-size: 12px;">';
    echo '<h2 style="margin-top: 0;">🔍 DEBUG INFORMATION</h2>';
    
    echo '<h3>1. URL Parameters</h3>';
    echo '<p><strong>location_state:</strong> ' . esc_html($location_state ?: '(empty)') . '</p>';
    echo '<p><strong>location_city:</strong> ' . esc_html($location_city ?: '(empty)') . '</p>';
    
    echo '<h3>2. Query Setup</h3>';
    echo '<p><strong>Location Name:</strong> ' . esc_html($location_name ?: '(empty)') . '</p>';
    echo '<p><strong>Is State:</strong> ' . ($is_state ? 'Yes' : 'No') . '</p>';
    echo '<p><strong>Location Term IDs:</strong> ' . (!empty($location_term_ids) ? implode(', ', $location_term_ids) : '(none)') . '</p>';
    
    if (!empty($location_state)) {
        $state_term = get_term_by('name', $location_state, 'us_location');
        if ($state_term) {
            echo '<p><strong>State Term Found:</strong> ' . $state_term->name . ' (ID: ' . $state_term->term_id . ')</p>';
            
            $cities = get_terms(array(
                'taxonomy' => 'us_location',
                'hide_empty' => false,
                'parent' => $state_term->term_id,
            ));
            echo '<p><strong>Cities in State:</strong> ' . count($cities) . '</p>';
            if (!empty($cities)) {
                echo '<ul style="margin: 5px 0;">';
                foreach (array_slice($cities, 0, 10) as $city) {
                    echo '<li>' . esc_html($city->name) . ' (ID: ' . $city->term_id . ')</li>';
                }
                if (count($cities) > 10) echo '<li>... and ' . (count($cities) - 10) . ' more</li>';
                echo '</ul>';
            }
        } else {
            echo '<p style="color: red;"><strong>State Term NOT FOUND:</strong> ' . esc_html($location_state) . '</p>';
        }
    }
    
    echo '<h3>3. Query Results</h3>';
    echo '<p><strong>Total Clinics Found:</strong> ' . $total_clinics . '</p>';
    
    echo '<h3>4. SQL Query</h3>';
    echo '<pre style="background: white; padding: 10px; overflow-x: auto; font-size: 11px;">' . esc_html($clinics_query->request) . '</pre>';
    
    echo '<h3>5. All Published Clinics (Sample Check)</h3>';
    $all_clinics = get_posts(array(
        'post_type' => 'clinic',
        'posts_per_page' => 5,
        'post_status' => 'publish',
    ));
    
    if (empty($all_clinics)) {
        echo '<p style="color: red;"><strong>NO CLINICS EXIST IN DATABASE!</strong></p>';
    } else {
        echo '<p>Found ' . count($all_clinics) . ' clinics (showing first 5):</p>';
        echo '<table style="width: 100%; border-collapse: collapse; background: white;" border="1">';
        echo '<tr><th>Clinic</th><th>Locations</th><th>_rating</th><th>_clinic_rating</th></tr>';
        foreach ($all_clinics as $clinic) {
            $locations = wp_get_post_terms($clinic->ID, 'us_location');
            $location_names = array();
            foreach ($locations as $loc) {
                $parent = $loc->parent ? get_term($loc->parent, 'us_location') : null;
                $location_names[] = $loc->name . ($parent ? ' (' . $parent->name . ')' : ' [STATE]');
            }
            
            $rating = get_post_meta($clinic->ID, '_rating', true);
            $clinic_rating = get_post_meta($clinic->ID, '_clinic_rating', true);
            
            echo '<tr>';
            echo '<td>' . esc_html($clinic->post_title) . ' (ID: ' . $clinic->ID . ')</td>';
            echo '<td>' . (!empty($location_names) ? implode(', ', $location_names) : '<span style="color: red;">NONE!</span>') . '</td>';
            echo '<td>' . ($rating ? $rating : '<span style="color: orange;">empty</span>') . '</td>';
            echo '<td>' . ($clinic_rating ? $clinic_rating : '<span style="color: orange;">empty</span>') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    
    echo '<h3>6. Meta Key Test</h3>';
    echo '<p><strong>Query uses meta_key:</strong> _rating</p>';
    echo '<p><strong>Template reads:</strong> _clinic_rating</p>';
    echo '<p style="color: red;"><strong>⚠ If _rating is empty but _clinic_rating has values, this is a META KEY MISMATCH!</strong></p>';
    
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
                        if (!empty($location_name)) {
                            if ($total_clinics > 0) {
                                echo 'Top ' . min($total_clinics, 10) . ' Best';
                            } else {
                                echo 'Best';
                            }
                            echo ' Tattoo Removal Near ' . esc_html($location_name);
                        } else {
                            echo 'Find Tattoo Removal Clinics';
                        }
                        ?>
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
                    <?php if (!empty($location_name)) : ?>
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
                                    $thumbnail = str_get_clinic_thumbnail($clinic_id, 'large', 'https://placehold.co/400x300');
                                ?>
                                    <div class="flex flex-col md:flex-row gap-6 pb-10 border-b border-gray-light hover:bg-offwhite/30 transition-colors p-4 -m-4 rounded-2xl group cursor-pointer">
                                        <!-- Clinic Image -->
                                        <div class="w-full md:w-[240px] h-[240px] shrink-0 rounded-2xl overflow-hidden shadow-sm relative">
                                            <img alt="<?php echo esc_attr(get_the_title()); ?>" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                                 src="<?php echo esc_url($thumbnail); ?>">
                                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest text-charcoal border border-gray-light shadow-sm">
                                                <?php echo $counter; ?>
                                            </div>
                                        </div>

                                        <!-- Clinic Info -->
                                        <div class="flex-1 flex flex-col min-w-0">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h2 class="text-xl font-black text-charcoal group-hover:text-brand transition-colors">
                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                    </h2>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <div class="flex text-brand">
                                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-4 h-4 <?php echo $i <= round($rating) ? 'fill-current' : 'text-gray-light'; ?>" aria-hidden="true">
                                                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                                                </svg>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span class="text-sm font-black text-charcoal"><?php echo number_format($rating, 1); ?></span>
                                                        <span class="text-sm text-graphite font-bold">(<?php echo $review_count; ?> reviews)</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-bold text-graphite mb-4">
                                                <span>•</span>
                                                <span><?php echo esc_html($city ?: $location_name); ?></span>
                                                <span>•</span>
                                                <span class="text-charcoal font-black"><?php echo $price_range ?: 'N/A'; ?></span>
                                            </div>

                                            <!-- Features -->
                                            <?php 
                                            $clinic_features = wp_get_post_terms($clinic_id, 'clinic_feature', array('number' => 2));
                                            if (!empty($clinic_features)) :
                                            ?>
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    <?php foreach ($clinic_features as $feature) : ?>
                                                        <span class="bg-offwhite text-graphite px-2 py-1 rounded text-[10px] font-black uppercase border border-gray-light tracking-widest">
                                                            <?php echo esc_html($feature->name); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Portfolio Images -->
                                            <div class="flex gap-2 mt-auto">
                                                <?php for ($i = 1; $i <= 4; $i++) : ?>
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-light bg-offwhite flex-shrink-0 relative group/thumb">
                                                        <img alt="Portfolio" class="w-full h-full object-cover grayscale group-hover/thumb:grayscale-0 transition-all" src="https://placehold.co/100x100">
                                                        <?php if ($i === 4) : ?>
                                                            <div class="absolute inset-0 bg-charcoal/40 flex items-center justify-center text-[8px] font-black text-white uppercase tracking-tighter">See All</div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endfor; ?>
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
                    <?php else : ?>
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-graphite">
                                    <path d="M3 12a8.999 8.999 0 1 1 .001 0h-.001Z"></path>
                                    <circle cx="12" cy="12" r="2"></circle>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-charcoal mb-2">Search for Clinics</h2>
                            <p class="text-graphite">Please add location parameters to search: ?location_state=YourState&location_city=YourCity</p>
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
                                    Add your clinic and get found by <span class="text-white font-black underline decoration-brand decoration-2 underline-offset-4">13,000 monthly visitors</span> looking for tattoo removal<?php if (!empty($location_name)) : ?> in <span class="text-brand font-black"><?php echo esc_html($location_name); ?></span><?php endif; ?>.
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
