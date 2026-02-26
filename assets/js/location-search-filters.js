/**
 * Location Search Filters
 * Handles filter interactions with URL query parameters
 * 
 * @package SearchTattooRemoval
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    console.log('Location Search Filters: Initializing...');
    
    // Add loading overlay during page transitions
    function showLoading() {
        const overlay = document.createElement('div');
        overlay.id = 'filterLoadingOverlay';
        overlay.className = 'fixed inset-0 bg-white/75 backdrop-blur-sm z-[100] flex items-center justify-center opacity-0 transition-opacity duration-300';
        overlay.innerHTML = `
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-brand mb-4"></div>
                <p class="text-sm font-black text-charcoal uppercase tracking-widest">Updating Results...</p>
            </div>
        `;
        document.body.appendChild(overlay);
        
        // Fade in
        setTimeout(() => {
            overlay.style.opacity = '1';
        }, 10);
        
        // Disable scrolling
        document.body.style.overflow = 'hidden';
    }
    
    // Remove a specific filter
    window.removeFilter = function(filterName, value = null) {
        console.log('Removing filter:', filterName, value);
        const urlParams = new URLSearchParams(window.location.search);
        
        if (value) {
            // Remove specific value from array filter (price[], features[])
            const values = urlParams.getAll(filterName + '[]');
            urlParams.delete(filterName + '[]');
            values.filter(v => v !== value).forEach(v => urlParams.append(filterName + '[]', v));
        } else {
            // Remove entire filter parameter
            urlParams.delete(filterName);
        }
        
        // Reset pagination
        urlParams.delete('paged');
        
        // Show loading and navigate
        showLoading();
        window.location.search = urlParams.toString();
    };

    // Clear all filters but keep location
    window.clearAllFilters = function() {
        console.log('Clearing all filters');
        const urlParams = new URLSearchParams(window.location.search);
        const locationState = urlParams.get('location_state');
        const locationCity = urlParams.get('location_city');
        
        const newParams = new URLSearchParams();
        if (locationState) newParams.set('location_state', locationState);
        if (locationCity) newParams.set('location_city', locationCity);
        
        // Show loading and navigate
        showLoading();
        window.location.search = newParams.toString();
    };

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Location Search Filters: DOM Ready');
        
        // Mobile filter toggle
        const mobileFilterToggle = document.getElementById('mobileFilterToggle');
        const filterSidebar = document.getElementById('filterSidebar');
        const filterCount = document.getElementById('filterCount');
        const closeFilters = document.getElementById('closeFilters');
        
        console.log('Elements found:', {
            priceButtons: document.querySelectorAll('[data-filter="price"]').length,
            checkboxes: document.querySelectorAll('.filter-checkbox').length,
            mobileToggle: !!mobileFilterToggle,
            sidebar: !!filterSidebar
        });
        
        // Mobile filter functions
        function openMobileFilters() {
            filterSidebar.classList.remove('hidden');
            filterSidebar.classList.add('fixed', 'inset-0', 'z-50', 'bg-white', 'p-8', 'overflow-y-auto');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileFilters() {
            filterSidebar.classList.add('hidden');
            filterSidebar.classList.remove('fixed', 'inset-0', 'z-50', 'bg-white', 'p-8', 'overflow-y-auto');
            document.body.style.overflow = '';
        }
        
        if (mobileFilterToggle && filterSidebar) {
            mobileFilterToggle.addEventListener('click', openMobileFilters);
        }
        
        if (closeFilters) {
            closeFilters.addEventListener('click', closeMobileFilters);
        }
        
        // Update filter count badge
        function updateFilterCount() {
            const urlParams = new URLSearchParams(window.location.search);
            const priceFilters = urlParams.getAll('price[]').length;
            const featureFilters = urlParams.getAll('features[]').length;
            const openNow = urlParams.has('open_now') ? 1 : 0;
            const verified = urlParams.has('verified') ? 1 : 0;
            const onlineBooking = urlParams.has('online_booking') ? 1 : 0;
            const minRating = urlParams.has('min_rating') ? 1 : 0;
            
            const totalFilters = priceFilters + featureFilters + openNow + verified + onlineBooking + minRating;
            
            if (filterCount && totalFilters > 0) {
                filterCount.textContent = totalFilters;
                filterCount.classList.remove('hidden');
            } else if (filterCount) {
                filterCount.classList.add('hidden');
            }
        }
        
        updateFilterCount();
        
        // Price filter buttons
        const priceButtons = document.querySelectorAll('[data-filter="price"]');
        console.log('Attaching listeners to', priceButtons.length, 'price buttons');
        
        priceButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Price filter clicked:', this.dataset.value);
                
                const urlParams = new URLSearchParams(window.location.search);
                const value = this.dataset.value;
                const priceArray = urlParams.getAll('price[]');
                
                console.log('Current price filters:', priceArray);
                
                if (priceArray.includes(value)) {
                    // Remove this price
                    console.log('Removing price:', value);
                    urlParams.delete('price[]');
                    priceArray.filter(p => p !== value).forEach(p => {
                        urlParams.append('price[]', p);
                    });
                } else {
                    // Add this price
                    console.log('Adding price:', value);
                    urlParams.append('price[]', value);
                }
                
                // Reset to page 1
                urlParams.delete('paged');
                
                // Build new URL
                const newUrl = window.location.pathname + '?' + urlParams.toString();
                console.log('Navigating to:', newUrl);
                
                // Show loading and navigate
                showLoading();
                window.location.href = newUrl;
            });
        });
        
        // Checkbox filters (open_now, verified, online_booking, features, min_rating)
        const checkboxes = document.querySelectorAll('.filter-checkbox');
        console.log('Attaching listeners to', checkboxes.length, 'checkboxes');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                console.log('Checkbox changed:', this.dataset.filter, 'checked:', this.checked, 'value:', this.dataset.value);
                
                const urlParams = new URLSearchParams(window.location.search);
                const filter = this.dataset.filter;
                const value = this.dataset.value;
                
                if (filter === 'features') {
                    // Handle array of features
                    if (this.checked) {
                        console.log('Adding feature:', value);
                        urlParams.append('features[]', value);
                    } else {
                        console.log('Removing feature:', value);
                        const features = urlParams.getAll('features[]');
                        urlParams.delete('features[]');
                        features.filter(f => f !== value).forEach(f => {
                            urlParams.append('features[]', f);
                        });
                    }
                } else if (filter === 'min_rating') {
                    // Handle rating filter (only one can be selected)
                    if (this.checked) {
                        console.log('Setting min_rating:', value);
                        // Uncheck other rating checkboxes
                        document.querySelectorAll('[data-filter="min_rating"]').forEach(cb => {
                            if (cb !== this) cb.checked = false;
                        });
                        urlParams.set('min_rating', value);
                    } else {
                        console.log('Removing min_rating');
                        urlParams.delete('min_rating');
                    }
                } else {
                    // Handle boolean filters (open_now, verified, online_booking)
                    if (this.checked) {
                        console.log('Setting', filter, 'to 1');
                        urlParams.set(filter, '1');
                    } else {
                        console.log('Removing', filter);
                        urlParams.delete(filter);
                    }
                }
                
                // Reset to page 1
                urlParams.delete('paged');
                
                // Build new URL
                const newUrl = window.location.pathname + '?' + urlParams.toString();
                console.log('Navigating to:', newUrl);
                
                // Show loading and navigate
                showLoading();
                window.location.href = newUrl;
            });
        });
        
        console.log('Location Search Filters: Initialization complete');
    });
})();
