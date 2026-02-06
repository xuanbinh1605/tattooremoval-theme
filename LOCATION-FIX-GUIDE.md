# Location Clinic Listing - Fixes & Instructions

## Issues Fixed

### 1. **Meta Field Keys Corrected**
   - Changed from `rating` to `_rating`
   - Changed from `review_count` to `_reviews_count`
   - Changed from `city` to `_city`
   - Changed from `price_range` to `_price_range_display`

### 2. **Added URL Parameter Support**
   - The taxonomy template now supports URL parameters: `?location_state=StateName&location_city=CityName`
   - Works with both WordPress taxonomy routing AND manual URL parameters

### 3. **Sample Data Script Updated**
   - Now saves `_city` and `_state` as meta fields in addition to taxonomy assignment
   - This ensures data is available both ways

### 4. **Created Standalone Location Search Template**
   - New file: `page-location-search.php`
   - Template Name: "Location Search"
   - Can be used for a dedicated location search page

## How to Test & Use

### Step 1: Re-run the Sample Data Script (if needed)
If you haven't created sample clinics yet, or want to update existing ones:
1. Navigate to: `yoursite.com/wp-content/themes/search-tattoo-removal/sample-clinic-data.php`
2. Log in as admin
3. The script will create/update 5 sample clinics with proper meta fields and taxonomy assignments

### Step 2: Flush Permalinks
1. Go to WordPress Admin → Settings → Permalinks
2. Just click "Save Changes" (don't change anything)
3. This refreshes WordPress's URL routing

### Step 3: Test Using Taxonomy URLs
Try accessing these URLs (replace with your actual state/city names):
```
yoursite.com/us-location/florida/
yoursite.com/us-location/california/
yoursite.com/us-location/florida/miami/
```

### Step 4: Test Using URL Parameters
The taxonomy template (`taxonomy-us_location.php`) also supports URL parameters:
```
yoursite.com/us-location/florida/?location_state=Florida
yoursite.com/us-location/florida/?location_state=Florida&location_city=Miami
```

### Step 5: Use the Standalone Location Search Page (Optional)
For a dedicated location search page:
1. Go to WordPress Admin → Pages → Add New
2. Create a new page (e.g., "Find Clinics")
3. In Page Attributes → Template, select "Location Search"
4. Publish the page
5. Access it with parameters:
   ```
   yoursite.com/find-clinics/?location_state=Florida
   yoursite.com/find-clinics/?location_state=Florida&location_city=Miami
   ```

## URL Parameter Format

### Search by State (all clinics in state)
```
?location_state=Florida
?location_state=California
?location_state=New%20York  (use %20 for spaces)
```

### Search by City in State
```
?location_state=Florida&location_city=Miami
?location_state=California&location_city=Los%20Angeles
?location_state=New%20York&location_city=New%20York
```

**Important:** State and city names are case-sensitive and must match exactly how they are stored in the taxonomy.

## Sample Data Locations
The sample data includes these locations:
- Florida → Miami
- California → Los Angeles  
- New York → New York
- Texas → Houston
- Arizona → Phoenix

## Troubleshooting

### Clinics Still Not Showing?
1. **Check if clinics exist:**
   - Go to Admin → Clinics
   - Verify you have published clinics

2. **Check taxonomy assignment:**
   - Edit a clinic
   - Look for "US Locations" section
   - Ensure a city is selected

3. **Check meta fields:**
   - Edit a clinic
   - Look for Custom Fields (may need to enable via Screen Options)
   - Verify `_rating`, `_city`, `_state` fields exist

4. **Re-run sample data script:**
   - This will ensure all meta fields are properly saved

5. **Check for PHP errors:**
   - Enable WP_DEBUG in wp-config.php
   - Check debug.log file

### URL Parameters Not Working?
- Make sure you're using the exact state/city names from the taxonomy
- Check that spaces are encoded as %20 or +
- Verify permalinks are flushed

## Files Modified
1. `taxonomy-us_location.php` - Added URL parameter support, fixed meta field keys
2. `sample-clinic-data.php` - Added _city and _state meta field saving
3. `page-location-search.php` - NEW standalone template for location searches

## Next Steps
After fixing these issues:
1. The taxonomy routing should work automatically
2. URL parameters provide a reliable backup method
3. You can use either approach based on your needs
4. Update any links to use the correct URL format
