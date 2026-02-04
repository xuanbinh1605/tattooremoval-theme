<?php
/**
 * Template Name: Front Page
 * Description: The front page template for the tattoo removal search site
 */

get_header();
?>

<main class="flex-grow">
    <div class="flex flex-col w-full bg-offwhite">
        
        <!-- Hero Section -->
        <section class="relative bg-charcoal text-white py-24 lg:py-40 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img alt="Laser Technician performing tattoo removal" class="w-full h-full object-cover opacity-40 object-center" src="https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&amp;w=2070&amp;auto=format&amp;fit=crop">
                <div class="absolute inset-0 bg-gradient-to-r from-charcoal/90 via-charcoal/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-charcoal/20 to-charcoal"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left z-10">
                <div class="max-w-4xl">
                    <h1 class="text-4xl md:text-7xl font-extrabold tracking-tighter mb-8 leading-[1.1] animate-in fade-in slide-in-from-left-4 duration-700">
                        Clear Your Canvas <br class="hidden lg:block">
                        <span class="text-brand">Find the Best</span> Tattoo Removal
                    </h1>
                    <p class="text-xl md:text-2xl text-slate-300 max-w-2xl mb-12 font-medium leading-relaxed animate-in fade-in slide-in-from-left-6 duration-700">
                        Locate the highest-rated medical laser specialists in your city. Compare clinic technology, read verified case studies, and book a free consultation.
                    </p>
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-200">
                        <form class="max-w-2xl mx-auto flex flex-col md:flex-row bg-white rounded-lg p-1.5 flex shadow-xl gap-2 relative border border-gray-light">
                            <div class="flex-grow flex items-center px-3 py-3 bg-offwhite rounded-md relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-5 w-5 text-graphite mr-2 flex-shrink-0" aria-hidden="true">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <input placeholder="City, State, or Zip" class="w-full bg-transparent border-none focus:ring-0 text-charcoal placeholder-graphite font-medium focus:outline-none text-base" autocomplete="off" type="text" value="">
                            </div>
                            <button type="submit" class="px-8 py-3 text-lg bg-brand hover:bg-brand-hover text-white font-bold rounded-md transition-colors flex-shrink-0 flex items-center justify-center">
                                <span class="inline">Search</span>
                            </button>
                        </form>
                    </div>
                    <div class="mt-8 flex flex-wrap justify-center md:justify-start items-center gap-6 opacity-60">
                        <div class="flex items-center text-xs font-black uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-4 h-4 text-brand mr-2" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg> 
                            Medical Grade Lasers
                        </div>
                        <div class="flex items-center text-xs font-black uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-4 h-4 text-brand mr-2" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg> 
                            Board Certified Directors
                        </div>
                        <div class="flex items-center text-xs font-black uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-4 h-4 text-brand mr-2" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg> 
                            FDA Cleared Technology
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

<?php get_footer(); ?>
