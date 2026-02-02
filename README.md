# Search Tattoo Removal WordPress Theme

A modern, scalable WordPress theme designed for tattoo removal clinic directory and search functionality.

## Features

- **Custom Post Type**: Clinics with extensive metadata support
- **Custom Taxonomies**: States, Cities, and Treatment Types
- **REST API**: Full API support for headless WordPress functionality
- **Responsive Design**: Mobile-first, fully responsive layout
- **SEO Optimized**: Clean markup and semantic HTML5
- **Custom Fields**: Comprehensive clinic information management
- **Rating System**: Star rating display for clinics
- **Scalable Architecture**: Modular code structure for easy maintenance

## Installation

1. Upload the theme folder to `/wp-content/themes/`
2. Activate the theme through the WordPress admin panel
3. Go to Settings > Permalinks and click "Save Changes" to flush rewrite rules

## Theme Structure

```
wp-theme/
├── assets/
│   ├── css/
│   │   └── main.css          # Main stylesheet
│   ├── js/
│   │   └── main.js           # Main JavaScript
│   └── images/               # Theme images
├── inc/
│   ├── custom-post-types.php # Clinic post type
│   ├── custom-taxonomies.php # State, City, Treatment taxonomies
│   ├── custom-fields.php     # Meta boxes and custom fields
│   ├── template-functions.php # Helper functions
│   ├── rest-api.php          # REST API endpoints
│   ├── admin-functions.php   # Admin customizations
│   └── jetpack.php           # Jetpack compatibility
├── template-parts/
│   ├── content.php           # Default post template
│   ├── content-clinic.php    # Clinic card template
│   └── content-none.php      # No results template
├── templates/                # Page templates
├── functions.php             # Main functions file
├── style.css                 # Theme info (required by WordPress)
├── header.php                # Site header
├── footer.php                # Site footer
├── sidebar.php               # Sidebar
├── index.php                 # Default template
├── single-clinic.php         # Single clinic template
├── archive-clinic.php        # Clinic archive template
└── search.php                # Search results template
```

## Custom Post Type: Clinic

The theme includes a custom post type "Clinic" with the following meta fields:

### Basic Information
- Rating (0-5 stars)
- Number of Reviews
- Price Range ($, $$, $$$, $$$$)
- Established Year

### Contact Information
- Phone Number
- Email Address
- Website URL
- Business Hours

### Location
- Street Address
- City
- State
- ZIP Code
- Latitude
- Longitude (for mapping)

## Custom Taxonomies

1. **States**: Hierarchical taxonomy for organizing clinics by state
2. **Cities**: Hierarchical taxonomy for organizing clinics by city
3. **Treatment Types**: Non-hierarchical taxonomy for treatment methods

## REST API Endpoints

### Search Clinics
```
GET /wp-json/str/v1/search
Parameters:
  - query: Search term
  - state: Filter by state slug
  - city: Filter by city slug
  - per_page: Results per page (default: 10)
  - page: Page number (default: 1)
```

### Get Clinic Details
```
GET /wp-json/str/v1/clinic/{id}
```

### Get States
```
GET /wp-json/str/v1/states
```

## Template Functions

### Display Clinic Rating
```php
<?php str_clinic_rating($clinic_id); ?>
```

### Display Clinic Address
```php
<?php str_clinic_address($clinic_id); ?>
```

### Get Clinic Contact Info
```php
<?php
$contact = str_get_clinic_contact($clinic_id);
echo $contact['phone'];
echo $contact['email'];
echo $contact['website'];
?>
```

### Custom Pagination
```php
<?php str_pagination(); ?>
```

## Customization

### Adding New Meta Fields

Edit `inc/custom-fields.php` to add new meta boxes and fields for clinics.

### Modifying Templates

Template files are located in:
- Root directory for main templates (single, archive, etc.)
- `template-parts/` for reusable content components

### Styling

Main CSS is in `assets/css/main.css`. The theme uses a mobile-first approach with responsive breakpoints at 768px.

### JavaScript

Main JavaScript is in `assets/js/main.js`. jQuery is used for compatibility and ease of use.

## Widget Areas

The theme includes 4 widget areas:
1. Sidebar (appears on blog posts and pages)
2. Footer Widget Area 1
3. Footer Widget Area 2
4. Footer Widget Area 3

## Navigation Menus

Two navigation menu locations:
1. Primary Menu (header)
2. Footer Menu (footer)

## Theme Support

- Custom Logo
- Post Thumbnails (with custom image sizes)
- HTML5 markup
- Title Tag
- Automatic Feed Links
- Selective Refresh for Widgets
- Block Editor Styles
- Wide Alignment
- Responsive Embeds

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- IE11+ (with potential graceful degradation)

## Requirements

- WordPress 6.0+
- PHP 7.4+
- MySQL 5.6+

## Development

This theme follows WordPress coding standards and best practices:
- Escaping output for security
- Sanitizing input data
- Using nonces for form submissions
- Internationalization ready (text domain: 'search-tattoo-removal')

## Performance

- Minimal external dependencies
- Optimized database queries
- Lazy loading support
- Clean, semantic HTML

## Security

- All user input is sanitized
- All output is escaped
- Nonce verification for form submissions
- Direct file access prevention

## Support

For theme customization or issues, contact: Robert Phillip

## License

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

## Changelog

### Version 1.0.0
- Initial release
- Custom clinic post type
- Custom taxonomies
- REST API integration
- Responsive design
- Admin customizations
