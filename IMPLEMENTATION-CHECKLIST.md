# WordPress Theme Implementation Checklist

Based on: **FINAL TECH BRIEF - US Tattoo Removal Clinics Directory**

---

## ✅ 1. Theme Installation & Activation

- [ ] Theme uploaded to server: `/wp-content/themes/tattoo-removal-theme/`
- [ ] Theme activated in WordPress Admin → Appearance → Themes
- [ ] Permalinks flushed: Settings → Permalinks → Save Changes
- [ ] No PHP errors on activation
- [ ] Theme appears in admin dashboard

---

## ✅ 2. Custom Post Types

### 2.1 Clinic CPT
- [. ] **Clinic** menu appears in WordPress admin sidebar
- [.] Slug format: `/clinic/{clinic-name}/`
- [ ] Can create new clinic via "Add New"
- [ ] REST API enabled: Check `/wp-json/wp/v2/clinics`
- [ ] Supports:
  - [ ] Title
  - [ ] Editor (content area)
  - [ ] Excerpt
  - [ ] Featured Image
  - [ ] Revisions (check post revision history)
  - [ ] Custom fields (meta boxes visible)

### 2.2 Laser Technology CPT
- [ ] **Laser Tech** menu appears in admin sidebar
- [ ] Slug format: `/laser-technology/{name}/`
- [ ] Can create new laser technology
- [ ] REST API enabled: Check `/wp-json/wp/v2/laser-technologies`
- [ ] Supports:
  - [ ] Title
  - [ ] Editor
  - [ ] Featured Image
  - [ ] Custom fields

---

## ✅ 3. Taxonomies

### 3.1 US Location Taxonomy (CRITICAL)
- [ ] **US Locations** taxonomy visible under Clinics menu
- [ ] Hierarchical structure enabled
- [ ] Can create State (parent term)
- [ ] Can create City (child term under State)
- [ ] Only attached to Clinic CPT (not posts/pages)
- [ ] Custom meta box displays on clinic edit page
- [ ] Radio buttons for city selection (one city only)
- [ ] Test URL structure:
  - [ ] State archive: `/us-location/california/`
  - [ ] City archive: `/us-location/california/los-angeles/`

**Test State Term Meta:**
- [ ] Create a State term
- [ ] Can add `state_code` meta (CA, TX, NY, etc.)
- [ ] Term meta saves correctly

### 3.2 Clinic Features Taxonomy
- [ ] **Features** taxonomy visible under Clinics menu
- [ ] Non-hierarchical (tag-like)
- [ ] Pre-populated with 12 features on theme activation:
  - [ ] Appointment Required
  - [ ] Online Scheduling
  - [ ] Offers Packages
  - [ ] Military Discount
  - [ ] Financing Available
  - [ ] Cash Only
  - [ ] Accepts Credit Cards
  - [ ] Accepts Debit Cards
  - [ ] Accepts Mobile Payments
  - [ ] Accepts Checks
  - [ ] Wheelchair Accessible
  - [ ] Medical Supervision
- [ ] Each feature has `feature_group` term meta set
- [ ] Can assign multiple features to a clinic
- [ ] Features appear on clinic edit page

### 3.3 Laser Technology Taxonomies
All attached to `laser_tech` CPT:
- [ ] **Laser Brands** taxonomy exists
- [ ] **Laser Wavelengths** taxonomy exists
- [ ] **Pulse Types** taxonomy exists
- [ ] **Target Ink Colors** taxonomy exists
- [ ] **Safe Skin Types** taxonomy exists
- [ ] All are non-hierarchical (tag-like)
- [ ] All appear on laser tech edit page

---

## ✅ 4. Clinic Custom Fields (Meta Boxes)

### 4.1 Basic Information Meta Box
- [ ] Meta box visible on clinic edit page
- [ ] Fields present:
  - [ ] Website URL (url input)
  - [ ] Phone Number (tel input)
  - [ ] Google Maps URL (url input)
- [ ] Data saves correctly on publish/update

### 4.2 Rating & Reviews Meta Box
- [ ] Meta box visible
- [ ] Fields present:
  - [ ] Rating (0-5, decimal allowed)
  - [ ] Reviews Count (integer)
- [ ] Data saves correctly

### 4.3 Address Details Meta Box
- [ ] Meta box visible
- [ ] Warning message displayed: "City & State MUST be set using US Locations taxonomy"
- [ ] Fields present:
  - [ ] Street Address (text)
  - [ ] ZIP Code (text)
  - [ ] Full Address (textarea)
- [ ] Data saves correctly

### 4.4 Operating Hours Meta Box
- [ ] Meta box visible
- [ ] Field: Operating Hours Raw (textarea)
- [ ] Placeholder/description text visible
- [ ] Data saves correctly

### 4.5 Pricing Meta Box
- [ ] Meta box visible
- [ ] Fields present:
  - [ ] Minimum Price (number, decimal)
  - [ ] Maximum Price (number, decimal)
  - [ ] Consultation Price (text - allows "Free", "$50", "Varies")
- [ ] Data saves correctly

### 4.6 Media & Branding Meta Box
- [ ] Meta box visible
- [ ] Fields present:
  - [ ] Logo URL (url input)
  - [ ] Before/After Gallery IDs (text)
- [ ] Data saves correctly
- [ ] Featured Image set via default WP featured image box

### 4.7 Business Information Meta Box (Sidebar)
- [ ] Meta box visible in sidebar
- [ ] Fields present:
  - [ ] Years in Business (number)
  - [ ] Featured Clinic (checkbox)
- [ ] Data saves correctly

### 4.8 Laser Technologies Meta Box (Sidebar)
- [ ] Meta box visible in sidebar
- [ ] Shows list of available laser tech posts
- [ ] Checkboxes for multiple selection
- [ ] Selected technologies save correctly
- [ ] Relationship stored in `_laser_technologies` meta

---

## ✅ 5. Laser Technology Custom Fields

### 5.1 Technology Information Meta Box
- [ ] Meta box visible on laser tech edit page
- [ ] Fields present:
  - [ ] Official Website (url)
  - [ ] Short Description (textarea)
  - [ ] Technical Notes (textarea)
- [ ] Data saves correctly

---

## ✅ 6. Data Architecture Validation

### 6.1 Location Architecture
- [ ] NO `city` or `state` text fields used for location
- [ ] Location ONLY via `us_location` taxonomy
- [ ] State is parent term, City is child term
- [ ] One clinic = one city assignment

### 6.2 Features Architecture
- [ ] NO boolean meta fields (e.g., `_has_parking`, `_wheelchair_accessible`)
- [ ] All options are `clinic_feature` taxonomy terms
- [ ] Multiple features can be assigned to one clinic

### 6.3 Laser Technology Architecture
- [ ] Laser Technology is a CPT (not taxonomy)
- [ ] Clinics link to Laser Tech via relationship meta field
- [ ] Multiple laser techs can be assigned to one clinic

### 6.4 No External IDs
- [ ] Confirm NO `place_id` meta field
- [ ] Confirm NO `data_id` meta field
- [ ] Confirm NO external ID fields anywhere

---

## ✅ 7. REST API Endpoints

- [ ] `/wp-json/wp/v2/clinics` - returns clinics
- [ ] `/wp-json/wp/v2/laser-technologies` - returns laser techs
- [ ] `/wp-json/wp/v2/us-locations` - returns location terms
- [ ] `/wp-json/wp/v2/clinic-features` - returns feature terms
- [ ] Custom endpoints (if implemented):
  - [ ] `/wp-json/str/v1/search` - search clinics
  - [ ] `/wp-json/str/v1/clinic/{id}` - get clinic details
  - [ ] `/wp-json/str/v1/states` - get states

---

## ✅ 8. Frontend Display

### 8.1 Clinic Archive Page
- [ ] `/clinic/` shows all clinics
- [ ] Displays clinic cards with:
  - [ ] Title
  - [ ] Featured image (if set)
  - [ ] Rating/reviews
  - [ ] Address/location
  - [ ] Link to single clinic page

### 8.2 Single Clinic Page
- [ ] `/clinic/{clinic-name}/` loads correctly
- [ ] Displays all clinic information:
  - [ ] Title
  - [ ] Content
  - [ ] Rating & reviews
  - [ ] Contact info (phone, website, maps)
  - [ ] Address
  - [ ] Hours
  - [ ] Pricing info
  - [ ] Features
  - [ ] Laser technologies used

### 8.3 Location Archive Pages
- [ ] State archive: `/us-location/{state-slug}/` works
- [ ] City archive: `/us-location/{state-slug}/{city-slug}/` works
- [ ] Shows clinics filtered by location
- [ ] Breadcrumbs show State → City hierarchy

### 8.4 Feature Archive Pages
- [ ] Feature archive: `/feature/{feature-slug}/` works
- [ ] Shows clinics with that feature

### 8.5 Laser Technology Archive
- [ ] Laser tech archive: `/laser-technology/` works
- [ ] Single laser tech page shows related clinics (if implemented)

---

## ✅ 9. Test Data Creation

### 9.1 Create Test State & Cities
- [ ] Create State: "California" (slug: `california`)
- [ ] Add state_code meta: `CA`
- [ ] Create City: "Los Angeles" (parent: California)
- [ ] Create City: "San Francisco" (parent: California)
- [ ] Verify hierarchy displays correctly

### 9.2 Create Test Laser Technologies
- [ ] Create laser tech: "PicoWay"
- [ ] Add taxonomies: brand, wavelength, etc.
- [ ] Add custom fields (website, description, notes)
- [ ] Save and verify

### 9.3 Create Test Clinic
- [ ] Create clinic: "Test Tattoo Removal Clinic"
- [ ] Assign US Location: California → Los Angeles
- [ ] Assign Features: Online Scheduling, Accepts Credit Cards, Wheelchair Accessible
- [ ] Add all custom fields:
  - [ ] Website, phone, Google Maps URL
  - [ ] Rating: 4.5, Reviews: 120
  - [ ] Street, ZIP, Full address
  - [ ] Operating hours
  - [ ] Pricing: min $100, max $500, consultation "Free"
  - [ ] Years in business: 10
  - [ ] Featured: Yes
  - [ ] Assign laser tech: PicoWay
- [ ] Publish and verify all data displays correctly

---

## ✅ 10. Admin Functionality

### 10.1 Clinic Admin List
- [ ] Custom columns show:
  - [ ] Rating
  - [ ] City
  - [ ] State
  - [ ] Phone (or other relevant fields)
- [ ] Columns are sortable
- [ ] Filter by US Location works
- [ ] Filter by Features works

### 10.2 Bulk Operations
- [ ] Can bulk edit clinics
- [ ] Can bulk delete clinics
- [ ] Taxonomies update correctly in bulk

---

## ✅ 11. CSV Import Preparation (Future)

Check that structure supports CSV import:
- [ ] State/City can be auto-created from CSV
- [ ] Features can be assigned by matching term names
- [ ] Laser techs can be matched by name or created
- [ ] No duplicate detection by external IDs (manual handling)
- [ ] All meta fields have consistent naming: `_field_name`

---

## ✅ 12. Performance & Security

- [ ] No PHP errors in error log
- [ ] No JavaScript console errors
- [ ] Meta fields properly sanitized on save
- [ ] Nonces verified on form submissions
- [ ] Direct file access prevented (ABSPATH check)
- [ ] Data escaped on output
- [ ] Queries use $wpdb->prepare when needed

---

## ✅ 13. SEO Readiness

- [ ] Custom post types have proper permalinks
- [ ] Taxonomies have proper permalinks
- [ ] Title tags work correctly
- [ ] Meta descriptions supported (if using SEO plugin)
- [ ] Breadcrumbs possible for State → City
- [ ] Schema markup possible for future implementation

---

## ✅ 14. Documentation

- [ ] README.md exists with:
  - [ ] Installation instructions
  - [ ] Feature list
  - [ ] Data architecture explanation
  - [ ] API endpoints documentation
  - [ ] Template hierarchy explanation
- [ ] Code comments present in all files
- [ ] Functions properly documented

---

## 🎯 Final Verification Tests

### Test Case 1: Complete Clinic Workflow
1. [ ] Create State "Texas" with state_code "TX"
2. [ ] Create City "Houston" under Texas
3. [ ] Create Laser Tech "Q-Switched Nd:YAG"
4. [ ] Create Clinic "Houston Laser Removal"
5. [ ] Assign Houston location
6. [ ] Assign 3+ features
7. [ ] Fill all meta fields
8. [ ] Assign laser technology
9. [ ] Publish and verify frontend display
10. [ ] Check archive pages work
11. [ ] Verify REST API returns correct data

### Test Case 2: Data Integrity
1. [ ] Edit clinic and change location
2. [ ] Add/remove features
3. [ ] Update rating and pricing
4. [ ] Verify all changes save
5. [ ] Check frontend reflects changes

### Test Case 3: Archive & Filtering
1. [ ] Visit state archive page
2. [ ] Visit city archive page
3. [ ] Visit feature archive page
4. [ ] Confirm correct clinics display
5. [ ] Test pagination if multiple clinics exist

---

## 📊 Success Criteria

**All items checked = Architecture fully implemented per FINAL TECH BRIEF**

- Custom Post Types: 2/2 (Clinic, Laser Tech)
- Taxonomies: 7/7 (US Location, Clinic Features, 5 Laser taxonomies)
- Clinic Meta Fields: 15+ fields organized in 8 meta boxes
- Laser Tech Meta Fields: 3 fields in 1 meta box
- NO location text fields ✓
- NO boolean option meta fields ✓
- NO external IDs ✓

---

## 🐛 Known Issues / Notes

_(Document any issues found during testing)_

- Issue 1:
- Issue 2:
- Issue 3:

---

## 📝 Next Steps After Checklist

1. [ ] CSV Import script development
2. [ ] Frontend filtering UI implementation
3. [ ] Advanced search functionality
4. [ ] Map integration for clinics
5. [ ] Schema markup for SEO
6. [ ] Performance optimization
7. [ ] Bulk import real clinic data

---

**Last Updated:** February 2, 2026  
**Theme Version:** 1.0.0  
**WordPress Version Required:** 6.0+  
**PHP Version Required:** 7.4+
