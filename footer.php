    </div><!-- #content -->

    <footer class="bg-charcoal text-slate-300 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <!-- Brand Section -->
                <div class="space-y-6">
                    <div class="flex items-center">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-brand" aria-hidden="true">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg>
                            <span class="ml-2 text-2xl font-bold text-white tracking-tight">Search Tattoo Removal</span>
                        </a>
                    </div>
                    <p class="text-slate-400 max-w-sm leading-relaxed text-sm">
                        Helping you clear the canvas with confidence. We connect patients with top-rated medical laser specialists across the US.
                    </p>
                </div>

                <!-- Directory Links -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-8">Directory</h3>
                    <ul class="space-y-5 text-sm">
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/states')); ?>">Search by State</a></li>
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/search')); ?>">Search by City</a></li>
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/states')); ?>">All State Pages</a></li>
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/for-business')); ?>">Business Solutions</a></li>
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/add-clinic')); ?>">Add Your Clinic</a></li>
                    </ul>
                </div>

                <!-- Get in Touch -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-8">Get in Touch</h3>
                    <ul class="space-y-5 text-sm">
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/contact')); ?>">Contact Page</a></li>
                        <li><a class="hover:text-brand transition-colors font-medium" href="<?php echo esc_url(home_url('/for-business')); ?>">Claim Your Listing</a></li>
                        <li><a class="hover:text-brand transition-colors font-medium" href="#">Help Center</a></li>
                        <li class="pt-6 flex space-x-5">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-brand cursor-pointer transition-all transform hover:-translate-y-1">
                                <span class="text-white font-bold">f</span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-brand cursor-pointer transition-all transform hover:-translate-y-1">
                                <span class="text-white font-bold">t</span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-brand cursor-pointer transition-all transform hover:-translate-y-1">
                                <span class="text-white font-bold">i</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Search By State Section -->
            <div class="pt-16 border-t border-slate-800">
                <h3 class="text-white font-black text-sm uppercase tracking-[0.2em] mb-10">Search By State</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-y-4 gap-x-6">
                    <?php
                    // Get all states (parent terms) from us_location taxonomy
                    $states = get_terms(array(
                        'taxonomy'   => 'us_location',
                        'hide_empty' => false,
                        'parent'     => 0,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                    ));

                    if (!empty($states) && !is_wp_error($states)) :
                        foreach ($states as $state) :
                            $state_url = add_query_arg(
                                array(
                                    's' => 'Laser Tattoo Removal',
                                    'us_location' => $state->slug
                                ),
                                home_url('/')
                            );
                            ?>
                            <a class="text-[11px] font-bold text-slate-400 hover:text-brand transition-colors uppercase tracking-wider whitespace-nowrap" 
                               href="<?php echo esc_url($state_url); ?>">
                                <?php echo esc_html($state->name); ?>
                            </a>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="max-w-7xl mx-auto px-4 mt-20 pt-10 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
            <p>&copy; <?php echo date('Y'); ?> Search Tattoo Removal. All rights reserved.</p>
            <div class="flex space-x-8 mt-6 md:mt-0 font-medium">
                <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="<?php echo esc_url(home_url('/terms-of-service')); ?>" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
