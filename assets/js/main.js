/**
 * Main JavaScript file for Search Tattoo Removal Theme
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const navigation = $('.primary-menu-container');

        menuToggle.on('click', function() {
            navigation.toggleClass('active');
            $(this).attr('aria-expanded', navigation.hasClass('active'));
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        $('a[href^="#"]:not([href="#"])').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    /**
     * Sticky Header on Scroll
     */
    function initStickyHeader() {
        const header = $('.site-header');
        const headerHeight = header.outerHeight();

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > headerHeight) {
                header.addClass('sticky');
            } else {
                header.removeClass('sticky');
            }
        });
    }

    /**
     * Back to Top Button
     */
    function initBackToTop() {
        // Create back to top button if it doesn't exist
        if (!$('.back-to-top').length) {
            $('body').append('<button class="back-to-top" aria-label="Back to top">↑</button>');
        }

        const backToTop = $('.back-to-top');

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                backToTop.addClass('visible');
            } else {
                backToTop.removeClass('visible');
            }
        });

        backToTop.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    }

    /**
     * Clinic Search with AJAX (if needed)
     */
    function initClinicSearch() {
        const searchForm = $('.clinic-search-form');

        if (!searchForm.length) {
            return;
        }

        searchForm.on('submit', function(e) {
            // Can add AJAX search functionality here if needed
        });
    }

    /**
     * Initialize Star Rating Display
     */
    function initStarRating() {
        $('.clinic-rating').each(function() {
            const rating = parseFloat($(this).data('rating'));
            const stars = $(this).find('.stars');
            
            if (!rating || stars.children().length) {
                return; // Skip if already rendered
            }

            const fullStars = Math.floor(rating);
            const halfStar = (rating % 1) >= 0.5 ? 1 : 0;
            const emptyStars = 5 - fullStars - halfStar;

            let starsHtml = '';
            
            for (let i = 0; i < fullStars; i++) {
                starsHtml += '<span class="star full">★</span>';
            }
            
            if (halfStar) {
                starsHtml += '<span class="star half">★</span>';
            }
            
            for (let i = 0; i < emptyStars; i++) {
                starsHtml += '<span class="star empty">☆</span>';
            }
            
            stars.html(starsHtml);
        });
    }

    /**
     * Image Lazy Loading Enhancement
     */
    function initLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.dataset.src || img.src;
            });
        }
    }

    /**
     * Form Validation
     */
    function initFormValidation() {
        $('form[data-validate]').on('submit', function(e) {
            let isValid = true;
            
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    }

    /**
     * Hero Search Autocomplete
     */
    function initHeroSearchAutocomplete() {
        const $input = $('#hero-search-input');
        const $suggestions = $('#hero-search-suggestions');
        const $form = $('#hero-search-form');

        if (!$input.length || typeof strAjax === 'undefined') {
            return;
        }

        let debounceTimer = null;
        let activeIndex = -1;
        let currentResults = [];
        let selectedLocation = null;

        function showSuggestions(results) {
            currentResults = results;
            activeIndex = -1;

            if (!results.length) {
                $suggestions.empty().removeClass('active');
                $input.attr('aria-expanded', 'false');
                return;
            }

            let html = '';
            results.forEach(function(item, i) {
                const icon = item.type === 'state'
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="str-suggest-icon"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="str-suggest-icon"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>';
                const label = item.type === 'state' ? 'State' : 'City';
                const countText = item.count > 0 ? item.count + ' clinic' + (item.count !== 1 ? 's' : '') : '';
                html += '<div class="str-suggest-item" role="option" data-index="' + i + '">'
                    + icon
                    + '<div class="str-suggest-text">'
                    + '<span class="str-suggest-name">' + $('<span>').text(item.name).html() + '</span>'
                    + '<span class="str-suggest-meta">' + label + (countText ? ' &middot; ' + countText : '') + '</span>'
                    + '</div>'
                    + '</div>';
            });

            $suggestions.html(html).addClass('active');
            $input.attr('aria-expanded', 'true');
        }

        function selectItem(index) {
            if (index < 0 || index >= currentResults.length) return;
            const item = currentResults[index];
            $input.val(item.name);
            selectedLocation = item;
            $suggestions.empty().removeClass('active');
            $input.attr('aria-expanded', 'false');
        }

        function navigateTo(item) {
            if (!item) return;
            // Build URL to the us-location taxonomy archive or location-search page
            if (item.type === 'state') {
                window.location.href = '/us-location/' + encodeURIComponent(item.slug) + '/';
            } else {
                // City: get parent state slug for the path
                var stateSlug = item.state.toLowerCase().replace(/\s+/g, '-');
                window.location.href = '/us-location/' + encodeURIComponent(stateSlug) + '/' + encodeURIComponent(item.slug) + '/';
            }
        }

        // Keyboard input with debounce
        $input.on('input', function() {
            var q = $.trim($(this).val());
            selectedLocation = null;

            if (debounceTimer) clearTimeout(debounceTimer);

            if (q.length < 2) {
                $suggestions.empty().removeClass('active');
                $input.attr('aria-expanded', 'false');
                return;
            }

            debounceTimer = setTimeout(function() {
                $.ajax({
                    url: strAjax.restUrl + 'locations/suggest',
                    data: { q: q },
                    dataType: 'json',
                    success: function(resp) {
                        showSuggestions(resp.results || []);
                    }
                });
            }, 250);
        });

        // Keyboard navigation
        $input.on('keydown', function(e) {
            if (!$suggestions.hasClass('active')) return;

            var items = $suggestions.find('.str-suggest-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = Math.min(activeIndex + 1, items.length - 1);
                items.removeClass('highlighted');
                items.eq(activeIndex).addClass('highlighted');
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                items.removeClass('highlighted');
                items.eq(activeIndex).addClass('highlighted');
            } else if (e.key === 'Enter' && activeIndex >= 0) {
                e.preventDefault();
                selectItem(activeIndex);
                navigateTo(selectedLocation);
            } else if (e.key === 'Escape') {
                $suggestions.empty().removeClass('active');
                $input.attr('aria-expanded', 'false');
            }
        });

        // Click on suggestion
        $suggestions.on('click', '.str-suggest-item', function() {
            selectItem(parseInt($(this).data('index'), 10));
            navigateTo(selectedLocation);
        });

        // Hover highlight
        $suggestions.on('mouseenter', '.str-suggest-item', function() {
            $suggestions.find('.str-suggest-item').removeClass('highlighted');
            $(this).addClass('highlighted');
            activeIndex = parseInt($(this).data('index'), 10);
        });

        // Close on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#hero-search-form').length) {
                $suggestions.empty().removeClass('active');
                $input.attr('aria-expanded', 'false');
            }
        });

        // Form submit — navigate to selected or search by text
        $form.on('submit', function(e) {
            e.preventDefault();
            if (selectedLocation) {
                navigateTo(selectedLocation);
            } else {
                var q = $.trim($input.val());
                if (q) {
                    window.location.href = strAjax.searchPage + '?location_state=' + encodeURIComponent(q);
                }
            }
        });
    }

    /**
     * Social Proof Counter Animation
     */
    function initCounterAnimation() {
        var $counters = $('.str-counter');
        if (!$counters.length) return;

        var animated = false;

        function formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function animateCounters() {
            if (animated) return;
            animated = true;

            $counters.each(function() {
                var $el = $(this);
                var target = parseInt($el.data('target'), 10) || 0;
                var duration = 2000;
                var start = 0;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    // Ease-out quad
                    var eased = 1 - (1 - progress) * (1 - progress);
                    var current = Math.floor(eased * target);
                    $el.text(formatNumber(current) + '+');
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        $el.text(formatNumber(target) + '+');
                    }
                }

                requestAnimationFrame(step);
            });
        }

        // Use IntersectionObserver to trigger when stats scroll into view
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.3 });

            var target = document.getElementById('hero-social-proof');
            if (target) observer.observe(target);
        } else {
            // Fallback: animate immediately
            animateCounters();
        }
    }

    /**
     * Initialize all functions when document is ready
     */
    $(document).ready(function() {
        initMobileMenu();
        initSmoothScroll();
        initStickyHeader();
        initBackToTop();
        initClinicSearch();
        initStarRating();
        initLazyLoading();
        initFormValidation();
        initHeroSearchAutocomplete();
        initCounterAnimation();

        // Trigger a custom event for other scripts to hook into
        $(document).trigger('strThemeReady');
    });

    /**
     * Handle window resize
     */
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Add any resize-dependent functions here
        }, 250);
    });

})(jQuery);
