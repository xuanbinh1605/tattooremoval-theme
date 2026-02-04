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
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'search-tattoo-removal'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) :
                    the_custom_logo();
                else :
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) :
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <?php esc_html_e('Menu', 'search-tattoo-removal'); ?>
                </button>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => 'div',
                        'container_class' => 'primary-menu-container',
                    )
                );
                ?>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">
