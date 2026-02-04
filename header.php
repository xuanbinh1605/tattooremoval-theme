<?php
/**
 * The header template
 *
 * @package SearchTattooRemoval
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: {
                DEFAULT: '#2F80ED', // Medical Trust Blue
                hover: '#1a6edb',
                light: '#e9f2fd',
              },
              charcoal: '#2B2E34', // Primary Text
              graphite: '#6B7280', // Secondary Text
              offwhite: '#F7F9FC', // Main Background
              teal: '#2BB0A6',     // Healing Teal (Accents)
              amber: '#F2B705',    // Warm Amber (Ratings)
              'gray-light': '#E5E7EB' // UI Lines
            }
          }
        }
      }
    </script>
    
    <style>
      body {
        background-color: #F7F9FC;
        color: #2B2E34;
      }
    </style>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="flex flex-col min-h-screen font-sans text-charcoal bg-offwhite">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'search-tattoo-removal'); ?></a>

    <nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-light shadow-sm transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-4 md:px-8">
            <div class="flex items-center h-20">
                <!-- Logo -->
                <div class="flex items-center cursor-pointer flex-shrink-0 mr-8">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-brand" aria-hidden="true">
                            <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                        </svg>
                        <div class="ml-2 leading-tight hidden lg:block">
                            <div class="text-lg font-bold tracking-tight text-charcoal">Search</div>
                            <div class="text-xs font-black text-brand uppercase tracking-widest">Tattoo Removal</div>
                        </div>
                        <span class="ml-2 text-xl font-bold tracking-tight text-charcoal lg:hidden">Search <span class="text-brand">TR</span></span>
                    </a>
                </div>

                <!-- Search Form (Initially Hidden) -->
                <div class="flex-grow flex items-center transition-all duration-500 transform opacity-0 -translate-y-4 scale-95 pointer-events-none">
                    <form role="search" method="get" class="max-w-xl mx-4 hidden sm:flex bg-white rounded-lg p-1.5 shadow-xl gap-2 relative border border-gray-light w-full max-w-lg shadow-md" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="flex-grow flex items-center px-3 py-1.5 bg-offwhite rounded-md relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-graphite mr-2 flex-shrink-0" aria-hidden="true">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <input type="text" name="s" placeholder="City, State, or Zip" class="w-full bg-transparent border-none focus:ring-0 text-charcoal placeholder-graphite font-medium focus:outline-none text-sm" autocomplete="off" value="<?php echo get_search_query(); ?>">
                        </div>
                        <button type="submit" class="px-5 py-1.5 text-sm bg-brand hover:bg-brand-hover text-white font-bold rounded-md transition-colors flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2 md:hidden" aria-hidden="true">
                                <path d="m21 21-4.34-4.34"></path>
                                <circle cx="11" cy="11" r="8"></circle>
                            </svg>
                            <span class="hidden md:inline">Search</span>
                            <span class="md:hidden">Go</span>
                        </button>
                    </form>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6 flex-shrink-0 ml-4">
                    <a class="text-sm font-semibold text-graphite hover:text-brand transition-colors" href="<?php echo esc_url(home_url('/for-business')); ?>">For Business</a>
                    <a class="text-sm font-semibold text-graphite hover:text-brand transition-colors" href="<?php echo esc_url(home_url('/states')); ?>">Browse States</a>
                    <a class="bg-charcoal text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-brand transition-all shadow-sm" href="<?php echo esc_url(home_url('/add-clinic')); ?>">Add Clinic</a>
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="flex items-center md:hidden ml-auto">
                    <button class="text-graphite hover:text-charcoal p-2" id="mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
                            <path d="M4 5h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 19h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div id="content" class="site-content">
