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

// Get filter parameters from URL
$price_filters = isset($_GET['price']) ? array_map('intval', (array)$_GET['price']) : array();
$verified = isset($_GET['verified']) ? (bool)$_GET['verified'] : false;
$online_booking = isset($_GET['online_booking']) ? (bool)$_GET['online_booking'] : false;
$min_rating = isset($_GET['min_rating']) ? intval($_GET['min_rating']) : 0;
$feature_filters = isset($_GET['features']) ? array_map('intval', (array)$_GET['features']) : array();

// Payment method filters
$accepts_credit_cards = isset($_GET['accepts_credit_cards']) ? (bool)$_GET['accepts_credit_cards'] : false;
$accepts_debit_cards = isset($_GET['accepts_debit_cards']) ? (bool)$_GET['accepts_debit_cards'] : false;
$accepts_mobile_payments = isset($_GET['accepts_mobile_payments']) ? (bool)$_GET['accepts_mobile_payments'] : false;
$cash_only = isset($_GET['cash_only']) ? (bool)$_GET['cash_only'] : false;

// Services filters
$financing = isset($_GET['financing']) ? (bool)$_GET['financing'] : false;
$military_discount = isset($_GET['military_discount']) ? (bool)$_GET['military_discount'] : false;
$online_scheduling = isset($_GET['online_scheduling']) ? (bool)$_GET['online_scheduling'] : false;
$wheelchair_accessible = isset($_GET['wheelchair_accessible']) ? (bool)$_GET['wheelchair_accessible'] : false;

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

// Build tax_query array
$tax_query = array('relation' => 'AND');

// Add location taxonomy query if we have location terms
if (!empty($location_term_ids)) {
    $tax_query[] = array(
        'taxonomy' => 'us_location',
        'field'    => 'term_id',
        'terms'    => $location_term_ids,
        'operator' => 'IN',
    );
}

// Add feature filters
if (!empty($feature_filters)) {
    $tax_query[] = array(
        'taxonomy' => 'clinic_feature',
        'field'    => 'term_id',
        'terms'    => $feature_filters,
        'operator' => 'IN',
    );
}

if (count($tax_query) > 1) {
    $query_args['tax_query'] = $tax_query;
}

// Build meta_query array
$meta_query = array('relation' => 'AND');

// Add rating filter
if ($min_rating > 0) {
    $meta_query[] = array(
        'key'     => '_clinic_rating',
        'value'   => $min_rating,
        'compare' => '>=',
        'type'    => 'NUMERIC',
    );
}

// Add price filter
if (!empty($price_filters)) {
    $meta_query[] = array(
        'key'     => '_clinic_price_range',
        'value'   => $price_filters,
        'compare' => 'IN',
        'type'    => 'NUMERIC',
    );
}

// Add verified license filter
if ($verified) {
    $meta_query[] = array(
        'key'     => '_clinic_verified',
        'value'   => '1',
        'compare' => '=',
    );
}

// Add online booking filter
if ($online_booking) {
    $meta_query[] = array(
        'key'     => '_clinic_online_booking',
        'value'   => '1',
        'compare' => '=',
    );
}

// Add payment method filters
if ($accepts_credit_cards) {
    $meta_query[] = array(
        'key'     => '_clinic_accepts_credit_cards',
        'value'   => '1',
        'compare' => '=',
    );
}

if ($accepts_debit_cards) {
    $meta_query[] = array(
        'key'     => '_clinic_accepts_debit_cards',
        'value'   => '1',
        'compare' => '=',
    );
}

if ($accepts_mobile_payments) {
    $meta_query[] = array(
        'key'     => '_clinic_accepts_mobile_payments',
        'value'   => '1',
        'compare' => '=',
    );
}

if ($cash_only) {
    $meta_query[] = array(
        'key'     => '_clinic_cash_only',
        'value'   => '1',
        'compare' => '=',
    );
}

// Add services filters
if ($financing) {
    $meta_query[] = array(
        'key'     => '_clinic_financing',
        'value'   => '1',
        'compare' => '=',
    );
}

if ($military_discount) {
    $meta_query[] = array(
        'key'     => '_clinic_military_discount',
        'value'   => '1',
        'compare' => '=',
    );
}

if ($online_scheduling) {
    $meta_query[] = array(
        'key'     => '_clinic_online_scheduling',
        'value'   => '1',
        'compare' => '=',
    );
}

if ($wheelchair_accessible) {
    $meta_query[] = array(
        'key'     => '_clinic_wheelchair_accessible',
        'value'   => '1',
        'compare' => '=',
    );
}

if (count($meta_query) > 1) {
    $query_args['meta_query'] = $meta_query;
}

// Query clinics
$clinics_query = new WP_Query($query_args);
$total_clinics = $clinics_query->found_posts;

// Check if any filters are active
$has_filters = !empty($price_filters) || $verified || $online_booking || $min_rating > 0 || !empty($feature_filters) || $accepts_credit_cards || $accepts_debit_cards || $accepts_mobile_payments || $cash_only || $financing || $military_discount || $online_scheduling || $wheelchair_accessible;

// Debug information (remove after testing)
if (current_user_can('administrator') && isset($_GET['debug'])) {
    echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 2px solid #333; font-family: monospace; font-size: 12px;">';
    echo '<h2 style="margin-top: 0;">🔍 DEBUG INFORMATION</h2>';
    echo '<h3>1. URL Parameters</h3>';
    echo '<p><strong>Current Term:</strong> ' . ($current_term ? $current_term->name . ' (ID: ' . $current_term->term_id . ')' : 'None') . '</p>';
    echo '<p><strong>Using URL Params:</strong> ' . ($using_url_params ? 'Yes' : 'No') . '</p>';
    echo '<p><strong>Location Name:</strong> ' . esc_html($location_name) . '</p>';
    echo '<p><strong>Is State:</strong> ' . ($is_state ? 'Yes' : 'No') . '</p>';
    echo '<p><strong>Location Term IDs:</strong> ' . implode(', ', $location_term_ids) . '</p>';
    echo '<p><strong>Raw $_GET:</strong></p>';
    echo '<pre style="background: white; padding: 10px; overflow-x: auto;">' . esc_html(print_r($_GET, true)) . '</pre>';
    echo '<p><strong>price_filters:</strong> ' . (!empty($price_filters) ? implode(', ', $price_filters) : '(empty)') . '</p>';
    echo '<p><strong>verified:</strong> ' . ($verified ? 'true' : 'false') . '</p>';
    echo '<p><strong>online_booking:</strong> ' . ($online_booking ? 'true' : 'false') . '</p>';
    echo '<p><strong>min_rating:</strong> ' . ($min_rating ?: '0') . '</p>';
    echo '<p><strong>feature_filters:</strong> ' . (!empty($feature_filters) ? implode(', ', $feature_filters) : '(empty)') . '</p>';
    echo '<h3>2. Query Results</h3>';
    echo '<p><strong>Total Clinics Found:</strong> ' . $total_clinics . '</p>';
    echo '<p><strong>Has Filters Applied:</strong> ' . ($has_filters ? 'Yes' : 'No') . '</p>';
    echo '<p><strong>tax_query count:</strong> ' . (isset($query_args['tax_query']) ? count($query_args['tax_query']) - 1 : '0') . ' conditions</p>';
    echo '<p><strong>meta_query count:</strong> ' . (isset($query_args['meta_query']) ? count($query_args['meta_query']) - 1 : '0') . ' conditions</p>';
    if (isset($query_args['tax_query'])) {
        echo '<p><strong>tax_query details:</strong></p>';
        echo '<pre style="background: white; padding: 10px; overflow-x: auto; font-size: 11px;">' . esc_html(print_r($query_args['tax_query'], true)) . '</pre>';
    }
    if (isset($query_args['meta_query'])) {
        echo '<p><strong>meta_query details:</strong></p>';
        echo '<pre style="background: white; padding: 10px; overflow-x: auto; font-size: 11px;">' . esc_html(print_r($query_args['meta_query'], true)) . '</pre>';
    }
    echo '<h3>3. SQL Query</h3>';
    echo '<pre style="background: white; padding: 10px; overflow-x: auto; font-size: 11px;">' . esc_html($clinics_query->request) . '</pre>';
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

        <?php 
        if ($has_filters) : 
        ?>
            <div class="border-b border-gray-light bg-offwhite/50 py-3">
                <div class="max-w-[1440px] mx-auto px-4 md:px-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-black text-graphite uppercase tracking-widest">Active Filters:</span>
                        
                        <?php if (!empty($price_filters)) : ?>
                            <?php foreach ($price_filters as $price) : ?>
                                <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                    <?php echo str_repeat('$', $price); ?>
                                    <button onclick="removeFilter('price', '<?php echo $price; ?>')" class="ml-2 text-graphite hover:text-red-500">×</button>
                                </span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if ($verified) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Verified License
                                <button onclick="removeFilter('verified')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($online_booking) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Online Booking
                                <button onclick="removeFilter('online_booking')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($min_rating > 0) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                <?php echo $min_rating; ?>+ Stars
                                <button onclick="removeFilter('min_rating')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($feature_filters)) : ?>
                            <?php foreach ($feature_filters as $feature_id) : 
                                $feature_term = get_term($feature_id, 'clinic_feature');
                                if ($feature_term && !is_wp_error($feature_term)) :
                            ?>
                                <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                    <?php echo esc_html($feature_term->name); ?>
                                    <button onclick="removeFilter('features', '<?php echo $feature_id; ?>')" class="ml-2 text-graphite hover:text-red-500">×</button>
                                </span>
                            <?php 
                                endif;
                            endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if ($accepts_credit_cards) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Credit Cards
                                <button onclick="removeFilter('accepts_credit_cards')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($accepts_debit_cards) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Debit Cards
                                <button onclick="removeFilter('accepts_debit_cards')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($accepts_mobile_payments) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Mobile Payments
                                <button onclick="removeFilter('accepts_mobile_payments')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($cash_only) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Cash Only
                                <button onclick="removeFilter('cash_only')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($financing) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Financing Available
                                <button onclick="removeFilter('financing')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($military_discount) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Military Discount
                                <button onclick="removeFilter('military_discount')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($online_scheduling) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Online Scheduling
                                <button onclick="removeFilter('online_scheduling')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($wheelchair_accessible) : ?>
                            <span class="inline-flex items-center bg-white border border-gray-light rounded-lg px-3 py-1 text-[10px] font-black text-charcoal">
                                Wheelchair Accessible
                                <button onclick="removeFilter('wheelchair_accessible')" class="ml-2 text-graphite hover:text-red-500">×</button>
                            </span>
                        <?php endif; ?>
                        
                        <button onclick="clearAllFilters()" class="ml-2 text-[10px] font-black text-brand uppercase tracking-widest hover:text-brand-hover">
                            Clear All
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 py-8">
            <!-- Mobile Filter Toggle Button -->
            <div class="lg:hidden mb-6">
                <button id="mobileFilterToggle" class="w-full flex items-center justify-center gap-2 bg-white border-2 border-gray-light rounded-xl px-6 py-3 font-black text-charcoal hover:border-brand transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <span class="text-sm uppercase tracking-widest">Filters</span>
                    <span id="filterCount" class="hidden ml-1 bg-brand text-white text-xs font-black px-2 py-0.5 rounded-full"></span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Filters Sidebar -->
                <div id="filterSidebar" class="hidden lg:block lg:col-span-2 space-y-8 pr-4 border-r border-gray-light">
                    <!-- Mobile Close Button -->
                    <div class="lg:hidden flex justify-between items-center mb-6 pb-4 border-b border-gray-light">
                        <h2 class="text-lg font-black text-charcoal">F ilters</h2>
                        <button id="closeFilters" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div>
                        <h2 class="text-sm font-black text-charcoal mb-4">Filters</h2>
                        <div class="space-y-6">
                            
                            <!-- Price Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Price</h3>
                                <div class="flex bg-offwhite p-1 rounded-xl border border-gray-light">
                                    <?php for ($p = 1; $p <= 4; $p++) : 
                                        $is_active = in_array($p, $price_filters);
                                        $price_symbol = str_repeat('$', $p);
                                    ?>
                                        <button data-filter="price" data-value="<?php echo $p; ?>" class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all <?php echo $is_active ? 'bg-brand text-white' : 'text-graphite hover:text-charcoal'; ?>"><?php echo $price_symbol; ?></button>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <!-- Rating Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Rating</h3>
                                <div class="space-y-2.5">
                                    <?php for ($i = 5; $i >= 3; $i--) : ?>
                                        <label class="flex items-center group cursor-pointer">
                                            <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="min_rating" data-value="<?php echo $i; ?>" <?php checked($min_rating, $i); ?>>
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
                                            $is_active = in_array($feature->term_id, $feature_filters);
                                    ?>
                                        <label class="flex items-center group cursor-pointer">
                                            <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="features" data-value="<?php echo $feature->term_id; ?>" <?php checked($is_active); ?>>
                                            <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors"><?php echo esc_html($feature->name); ?></span>
                                        </label>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>

                            <!-- Payment Methods Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Payment Methods</h3>
                                <div class="space-y-2.5">
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="accepts_credit_cards" <?php checked($accepts_credit_cards); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Credit Cards</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="accepts_debit_cards" <?php checked($accepts_debit_cards); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Debit Cards</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="accepts_mobile_payments" <?php checked($accepts_mobile_payments); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Mobile Payments</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="cash_only" <?php checked($cash_only); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Cash Only</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Services Filter -->
                            <div>
                                <h3 class="text-[11px] font-black text-graphite uppercase tracking-widest mb-3">Services</h3>
                                <div class="space-y-2.5">
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="financing" <?php checked($financing); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Financing Available</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="military_discount" <?php checked($military_discount); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Military Discount</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="online_scheduling" <?php checked($online_scheduling); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Online Scheduling</span>
                                    </label>
                                    <label class="flex items-center group cursor-pointer">
                                        <input class="filter-checkbox w-4 h-4 rounded border-gray-light text-brand focus:ring-brand cursor-pointer" type="checkbox" data-filter="wheelchair_accessible" <?php checked($wheelchair_accessible); ?>>
                                        <span class="ml-3 text-xs font-bold text-charcoal group-hover:text-brand transition-colors">Wheelchair Accessible</span>
                                    </label>
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
// Filter functionality is handled by assets/js/location-search-filters.js
// which is enqueued in functions.php for taxonomy archives

get_footer();
