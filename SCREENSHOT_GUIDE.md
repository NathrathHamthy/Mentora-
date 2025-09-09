
# MENTORA - Screenshot Capture Guide

## 📸 Required Screenshots for Documentation

This guide provides step-by-step instructions for capturing all required screenshots for MENTORA's documentation and test cases.

## 🎯 Screenshot Standards

### Technical Requirements
- **Resolution**: 1920x1080 minimum
- **Format**: PNG for UI screenshots
- **Browser**: Use Chrome with clean browser (no bookmarks bar)
- **Window Size**: Full screen or 1200x800 minimum
- **Quality**: High quality, no compression artifacts

### Capture Guidelines
- Clean browser window (close unnecessary tabs)
- Use consistent user data (admin/admin123, student accounts)
- Highlight important UI elements when needed
- Include browser address bar when showing URLs
- Use consistent lighting and display settings

## 📁 Directory Structure

```
docs/screenshots/
├── test-cases/          # Test case specific screenshots
├── features/            # Feature demonstration screenshots
├── ui-components/       # UI component screenshots
├── admin/              # Admin panel screenshots
├── installation/       # Installation process screenshots
├── mobile/             # Mobile responsive screenshots
└── workflow/           # User workflow screenshots
```

## 🧪 Test Case Screenshots Required

### Authentication Tests (TC-001 to TC-005)

#### TC-001: Admin Login Success
**File**: `test-cases/tc001-admin-login-success.png`
**Steps to capture**:
1. Navigate to login page: `http://localhost:5000/login.php`
2. Enter username: `admin`, password: `admin123`
3. Click "Sign In" button
4. Capture the admin dashboard after successful login

#### TC-002: Student Login Success
**File**: `test-cases/tc002-student-login-success.png`
**Steps to capture**:
1. Navigate to login page
2. Enter username: `john_doe`, password: `password123`
3. Click "Sign In" button
4. Capture the student dashboard after login

#### TC-003: Invalid Login Error
**File**: `test-cases/tc003-invalid-login-error.png`
**Steps to capture**:
1. Navigate to login page
2. Enter username: `invalid_user`, password: `wrong_password`
3. Click "Sign In" button
4. Capture the error message displayed

#### TC-004: Registration Success
**File**: `test-cases/tc004-registration-success.png`
**Steps to capture**:
1. Navigate to register page: `http://localhost:5000/register.php`
2. Fill out registration form with new user data
3. Click "Create Account" button
4. Capture success message or redirect page

#### TC-005: Duplicate Username Error
**File**: `test-cases/tc005-duplicate-username-error.png`
**Steps to capture**:
1. Navigate to register page
2. Enter existing username (e.g., `admin`)
3. Fill other fields and submit
4. Capture the duplicate username error message

### File Upload Tests (TC-006 to TC-008)

#### TC-006: File Upload Success
**File**: `test-cases/tc006-file-upload-success.png`
**Steps to capture**:
1. Login as student
2. Navigate to upload page: `http://localhost:5000/upload.php`
3. Fill form and select a valid PDF file
4. Click "Upload Material"
5. Capture success confirmation message

#### TC-007: Invalid File Type Error
**File**: `test-cases/tc007-invalid-file-type-error.png`
**Steps to capture**:
1. Navigate to upload page
2. Try to select an invalid file type (e.g., .exe)
3. Capture the file type validation error

#### TC-008: File Size Limit Error
**File**: `test-cases/tc008-file-size-limit-error.png`
**Steps to capture**:
1. Navigate to upload page
2. Try to upload a file larger than 50MB
3. Capture the file size limit error message

### Search & Filter Tests (TC-009 to TC-010)

#### TC-009: Search Success
**File**: `test-cases/tc009-search-success.png`
**Steps to capture**:
1. Navigate to departments page: `http://localhost:5000/departments.php`
2. Enter "JavaScript" in search box
3. Click search button
4. Capture the search results showing JavaScript materials

#### TC-010: Department Filter Success
**File**: `test-cases/tc010-department-filter-success.png`
**Steps to capture**:
1. Navigate to departments page
2. Select "IT" from department dropdown
3. Click filter button
4. Capture filtered results showing only IT materials

### Admin Panel Tests (TC-011 to TC-013)

#### TC-011: Admin Dashboard Access
**File**: `test-cases/tc011-admin-dashboard-access.png`
**Steps to capture**:
1. Login as admin
2. Navigate to admin dashboard: `http://localhost:5000/admin/index.php`
3. Capture the full dashboard with statistics and navigation

#### TC-012: Upload Approval Success
**File**: `test-cases/tc012-upload-approval-success.png`
**Steps to capture**:
1. Login as admin
2. Navigate to manage uploads: `http://localhost:5000/admin/manage.php`
3. Click "Approve" on a pending upload
4. Capture the approval confirmation

#### TC-013: Upload Rejection Success
**File**: `test-cases/tc013-upload-rejection-success.png`
**Steps to capture**:
1. Navigate to admin manage page
2. Click "Reject" on a pending upload
3. Enter rejection reason
4. Capture the rejection confirmation

### Security Tests (TC-014 to TC-015)

#### TC-014: SQL Injection Prevention
**File**: `test-cases/tc014-sql-injection-prevention.png`
**Steps to capture**:
1. Navigate to login page
2. Enter SQL injection attempt in username: `admin'; DROP TABLE users; --`
3. Submit form
4. Capture that login fails safely without database damage

#### TC-015: XSS Prevention
**File**: `test-cases/tc015-xss-prevention.png`
**Steps to capture**:
1. Navigate to upload form
2. Enter XSS script in title: `<script>alert('XSS')</script>`
3. Submit form
4. Capture that script is escaped/sanitized in output

### Installation Tests (TC-016 to TC-017)

#### TC-016: Installation Success
**File**: `test-cases/tc016-installation-success.png`
**Steps to capture**:
1. Navigate to installation: `http://localhost:5000/install/index.php`
2. Fill database configuration form
3. Click "Install MENTORA"
4. Capture installation success page

#### TC-017: Invalid Database Config Error
**File**: `test-cases/tc017-invalid-db-config-error.png`
**Steps to capture**:
1. Navigate to installation page
2. Enter invalid database credentials
3. Submit form
4. Capture database connection error message

## 🎨 Feature Screenshots Required

### Homepage & Navigation
**Files needed**:
- `features/homepage-overview.png` - Complete homepage
- `features/navigation-desktop.png` - Desktop navigation
- `features/navigation-mobile.png` - Mobile navigation
- `features/homepage-statistics.png` - Statistics section

**Capture steps**:
1. Navigate to homepage: `http://localhost:5000/`
2. Take full page screenshot
3. Resize browser to mobile width (375px) for mobile shot

### Authentication System
**Files needed**:
- `features/login-interface.png` - Login form
- `features/registration-form.png` - Registration form
- `features/user-dashboard.png` - Student dashboard

### Educational Materials
**Files needed**:
- `features/upload-form.png` - Upload interface
- `features/department-browse.png` - Department browsing
- `features/search-interface.png` - Search form
- `features/material-preview.png` - Material preview modal

### Admin Panel
**Files needed**:
- `admin/admin-dashboard.png` - Admin dashboard overview
- `admin/user-management.png` - User management interface
- `admin/content-management.png` - Content approval interface
- `admin/statistics-panel.png` - Admin statistics view

### UI Components
**Files needed**:
- `ui-components/glass-morphism-cards.png` - Glass effect cards
- `ui-components/buttons-interactive.png` - Button states and hover effects
- `ui-components/forms-styling.png` - Form styling examples
- `ui-components/notifications.png` - Notification examples

### Mobile Interface
**Files needed**:
- `mobile/mobile-homepage.png` - Mobile homepage
- `mobile/mobile-upload.png` - Mobile upload interface
- `mobile/mobile-admin.png` - Mobile admin interface
- `mobile/mobile-navigation.png` - Mobile menu

## 📱 Mobile Screenshot Instructions

### Browser Setup for Mobile
1. Open Chrome Developer Tools (F12)
2. Click device toggle icon (phone/tablet icon)
3. Select device: iPhone 12 Pro (390x844)
4. Take screenshots in this mode

### Mobile Breakpoints to Test
- **Mobile**: 375px width
- **Tablet**: 768px width
- **Desktop**: 1200px+ width

## 🔧 Screenshot Capture Tools

### Recommended Tools
1. **Built-in Browser**: Right-click → "Save as..." or Ctrl+S
2. **Browser Extensions**: Full Page Screen Capture
3. **OS Tools**: 
   - Windows: Snipping Tool or Win+Shift+S
   - Mac: Cmd+Shift+4
   - Linux: gnome-screenshot or scrot

### Browser Extension Setup
1. Install "Full Page Screen Capture" extension
2. Use for capturing long pages that scroll
3. Ensure high quality settings

## 📝 Screenshot Naming Convention

### Format: `category-description-state.png`

**Examples**:
- `tc001-admin-login-success.png`
- `features-upload-form-filled.png`
- `admin-dashboard-overview.png`
- `mobile-homepage-responsive.png`
- `ui-error-message-display.png`

## ✅ Screenshot Checklist

Before taking each screenshot:
- [ ] Clean browser window (no extra tabs/bookmarks visible)
- [ ] Correct URL in address bar
- [ ] Proper user logged in (if required)
- [ ] UI in correct state (forms filled, buttons highlighted, etc.)
- [ ] High resolution (1920x1080 or higher)
- [ ] Proper file naming convention used
- [ ] Saved in correct directory

## 🔄 Workflow for Capturing All Screenshots

### Daily Capture Schedule
**Day 1**: Authentication & Registration screenshots (TC-001 to TC-005)
**Day 2**: Upload & File Management screenshots (TC-006 to TC-008)
**Day 3**: Search & Admin Panel screenshots (TC-009 to TC-013)
**Day 4**: Security & Installation screenshots (TC-014 to TC-017)
**Day 5**: Feature & UI Component screenshots
**Day 6**: Mobile responsive screenshots
**Day 7**: Review and retake any missing/poor quality screenshots

### Quality Check Process
1. Review each screenshot for clarity
2. Verify proper naming convention
3. Check file sizes (should be 200KB-2MB)
4. Ensure all required screenshots are captured
5. Update documentation links

This systematic approach ensures comprehensive visual documentation of MENTORA's functionality and test coverage.
