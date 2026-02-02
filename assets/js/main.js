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
