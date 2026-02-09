# Clinic Importer Guide

## Overview
The Clinic Importer allows you to bulk import clinic data from CSV (or Excel converted to CSV) files into your WordPress site. All data is automatically mapped to the correct custom fields and taxonomies.

## Accessing the Importer

1. Log in to WordPress Admin
2. Navigate to **Clinics → Import from Excel**
3. You'll see the import page with upload form and template download

## Step-by-Step Import Process

### Step 1: Download the Template
1. Click the **"Download CSV Template"** button
2. This downloads a file called `clinic-import-template.csv`
3. The template includes:
   - All required column headers
   - One sample row showing the correct format

### Step 2: Prepare Your Data

#### Option A: Using the CSV Template Directly
1. Open the downloaded template in Excel, Google Sheets, or any spreadsheet software
2. Keep the header row exactly as is (row 1)
3. Delete the sample data row (row 2)
4. Add your clinic data starting from row 2

#### Option B: Converting Excel to CSV
1. If you have data in Excel already:
   - Make sure your columns match the template headers
   - File → Save As → Choose "CSV (Comma delimited) (*.csv)"
   - Save the file

### Step 3: Fill In Your Data

#### Required Fields
- **title**: The clinic name (e.g., "Tattoo Gone LA")
- **state**: US State name (e.g., "California")
- **city**: City name (e.g., "Los Angeles")

> **Important**: States and cities will be automatically created in the US Locations taxonomy if they don't exist. Make sure spelling is consistent!

#### Optional But Recommended Fields
- **content**: Full description of the clinic
- **phone**: Phone number in any format
- **website**: Full URL including https://
- **street**: Street address
- **zip_code**: ZIP code
- **rating**: Number from 0 to 5 (e.g., 4.5)
- **reviews_count**: Number of reviews (e.g., 152)
- **price_range_display**: What shows on cards (e.g., "$150 range")

#### All Available Fields

| Field Name | Type | Example | Notes |
|------------|------|---------|-------|
| title | Text | Tattoo Gone LA | **Required** - Clinic name |
| content | Long Text | Professional tattoo removal... | Full description |
| state | Text | California | **Required for location** |
| city | Text | Los Angeles | **Required for location** |
| street | Text | 123 Main St, Suite 100 | Street address |
| zip_code | Text | 90001 | ZIP code |
| phone | Text | (555) 123-4567 | Phone number |
| website | URL | https://example.com | Website URL |
| google_maps_url | URL | https://maps.google.com/... | Google Maps link |
| rating | Number | 4.5 | 0-5 rating |
| reviews_count | Number | 152 | Number of reviews |
| reviews_summary | Long Text | Patients love... | What people say |
| min_price | Number | 100 | Minimum price in $ |
| max_price | Number | 500 | Maximum price in $ |
| consultation_price | Text | Free | Consultation price |
| price_range_display | Text | $150 range | Display text for cards |
| operating_hours_raw | Text | Mon-Fri: 9AM-5PM | Operating hours |
| open_status | Text | Open Now | Current status |
| years_in_business | Number | 15 | Years operating |
| is_verified | 1 or 0 | 1 | 1 = verified, 0 = not |
| is_featured | 1 or 0 | 0 | 1 = featured, 0 = not |
| logo | URL | https://example.com/logo.png | Logo image URL |

### Step 4: Import the File

1. Go to **Clinics → Import from Excel**
2. Choose your CSV file using the file picker
3. Select the import mode:
   - **Create new clinics only**: Skip if clinic with same title exists
   - **Update existing**: Update clinics with matching titles
   - **Create or Update**: Overwrite existing or create new
4. Click **"Import Clinics"**
5. Wait for the success message showing how many clinics were imported

## Import Modes Explained

### Create New Clinics Only
- Safest option for first-time imports
- If a clinic with the same title exists, it will be skipped
- Existing data is never touched

### Update Existing (Match by Title)
- Updates clinics that have matching titles
- Leaves existing clinics alone if not in CSV
- Good for updating specific clinics

### Create or Update (Overwrite)
- Most powerful option
- Creates new clinics or updates existing ones
- Overwrites all fields with CSV data
- Use when you want to refresh all data

## Tips for Success

### Multi-line Text Fields
For fields like `operating_hours_raw` or `reviews_summary`, you can include line breaks:
- In Excel: Press Alt+Enter for a new line within a cell
- In Google Sheets: Press Ctrl+Enter (Cmd+Enter on Mac)

### Location Taxonomy
The importer automatically:
- Creates states if they don't exist (as parent terms)
- Creates cities under the correct state (as child terms)
- Assigns the city to the clinic

Make sure state and city names are spelled consistently across all rows!

### Boolean Fields (Yes/No)
For `is_verified` and `is_featured`:
- Use `1` for Yes/True
- Use `0` for No/False
- You can also use `yes` or `no` (case-insensitive)

### Empty Fields
- Leave cells empty if you don't have data
- Empty fields won't overwrite existing data in "Update" mode
- In "Overwrite" mode, empty fields will clear existing data

### Large Imports
- The importer can handle hundreds of rows
- If importing thousands of clinics, consider splitting into multiple files
- Each row is processed individually, so one bad row won't stop the entire import

## Troubleshooting

### "File upload failed"
- Make sure you selected a file
- Check file size (most servers allow up to 2MB)
- Verify the file extension is .csv

### "Empty file or invalid format"
- Make sure the first row contains column headers
- Ensure the file is saved as CSV (not .xlsx or .xls)
- Try opening in a text editor to verify it's comma-separated

### Clinics Not Appearing
- Check that the `title` field is not empty
- Verify the import success message shows the correct count
- Go to Clinics → All Clinics to see imported posts

### Location Not Showing
- Both `state` and `city` must be filled
- Check spelling matches exactly between rows
- Go to Clinics → US Locations to verify taxonomy was created

### Missing Data in Imported Clinics
- Column headers must match exactly (case-insensitive)
- Check for extra spaces in header names
- Verify data is in the correct columns

## Example CSV Structure

```csv
title,content,state,city,street,zip_code,phone,website,rating,reviews_count,is_verified,price_range_display
Tattoo Gone LA,Professional removal services,California,Los Angeles,123 Main St,90001,(555) 123-4567,https://example.com,4.5,152,1,$150 range
Ink Away Denver,Expert laser removal,Colorado,Denver,456 Oak Ave,80201,(555) 987-6543,https://inkaway.com,4.8,203,1,$200 range
```

## After Import

After successfully importing clinics:

1. **Review Imported Clinics**
   - Go to Clinics → All Clinics
   - Verify data looks correct
   - Check a few clinic pages on the frontend

2. **Verify Locations**
   - Go to Clinics → US Locations
   - Make sure state→city hierarchy is correct
   - Edit any duplicate locations if needed

3. **Add Additional Data**
   - Manually edit clinics to add:
     - Featured images
     - Before/after galleries
     - Laser technology assignments
     - Structured operating hours

4. **Test Search Functionality**
   - Visit your location search page
   - Test filtering by state and city
   - Verify clinic cards display correctly

## Need Help?

If you encounter issues:
1. Check the error message shown after import
2. Review this guide's troubleshooting section
3. Check WordPress error logs for detailed error messages
4. Verify your CSV format matches the template exactly

## Advanced: For Developers

The importer code is located in:
- `inc/clinic-importer.php`

Key functions:
- `str_import_csv()`: Processes CSV files
- `str_import_single_clinic()`: Imports one clinic row
- `str_set_clinic_location()`: Handles taxonomy assignment

To modify behavior, edit these functions in the theme files.
