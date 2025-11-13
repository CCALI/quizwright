# SAML Implementation Plan for QuizWright

## Current Authentication Architecture

The application currently uses:
- **Pluggable user management** system (configured via `USER_MGMNT` constant in config.php)
- **Two existing implementations**:
  1. `local_user.php` - Local database authentication (plaintext passwords)
  2. `drupal_user.php` - Drupal-based authentication with token support
- **Session-based** state management using `$_SESSION['uid']` and `$_SESSION['username']`
- **Dual database** support (application DB + optional user DB)
- **Simple user schema** in `people` table (uid, username, email, password, data, profile)

### Key Files in Current Architecture

- **User Management Files**:
  - `local_user.php:14-28` - Login flow with plaintext password comparison
  - `drupal_user.php:25-95` - Token-based and form-based Drupal authentication
  - `index.php:29` - Loads user management via `include(USER_MGMNT)`

- **Session Management**:
  - `includes/user-session.php:2-4` - Starts session and sets `$uid` from `$_SESSION['uid']`
  - `index.php:2` - Primary session initialization
  - `.htaccess:6-10` - Session configuration (200,000 sec maxlifetime)

- **Database Schema**:
  - `people` table: uid, username, email, password, data, profile
  - No password hashing in local mode (security issue)
  - Drupal mode uses SHA-512 hashing via `password.inc`

---

## Changes Required for SAML Implementation

### 1. Create New SAML User Management File

**File to create**: `saml_user.php` (root directory, alongside `local_user.php` and `drupal_user.php`)

**Requirements**:
- Follow the same structure pattern as existing user management files
- Handle these routes/cases:
  - `?u=login` - Initiate SAML authentication flow
  - `?u=logout` - SAML single logout (SLO)
  - `?u=register` - Redirect to IdP registration or disable
  - Default case - Redirect to login
- Integrate simpleSAMLphp library:
  - Initialize `SimpleSAML\Auth\Simple` instance
  - Use `requireAuth()` to force SAML authentication
  - Extract SAML attributes from `getAttributes()`
- Map SAML attributes to local user attributes:
  - SAML attribute → `username` (e.g., `eduPersonPrincipalName` or `uid`)
  - SAML attribute → `email` (e.g., `mail` or `email`)
  - Optional: Additional attributes to store in `data` JSON field
- Implement user provisioning logic:
  - Check if user exists in `people` table by username
  - If exists: Update session with existing user's `uid`
  - If not exists: Insert new user record, store SAML attributes in `data` field
- Set session variables:
  - `$_SESSION['username']` - from SAML attribute
  - `$_SESSION['uid']` - from `people` table
  - Optional: `$_SESSION['saml_attributes']` - store all SAML attributes
- Include appropriate view:
  - If authenticated: `include('./includes/home.php')`
  - If not authenticated: `include('./includes/login.php')`

**Example Implementation Structure**:
```php
<?php
/**
 * SAML-based user management using simpleSAMLphp
 */

$user = htmlspecialchars($_GET['u']);

switch($user) {
    case "login":
        require_once(SIMPLESAMLPHP_PATH . '/lib/_autoload.php');
        $saml = new \SimpleSAML\Auth\Simple(SAML_AUTH_SOURCE);

        // Force SAML authentication
        $saml->requireAuth();

        // Get SAML attributes
        $attributes = $saml->getAttributes();
        $saml_nameid = $saml->getNameId()['Value'];

        // Extract user info from SAML attributes
        $username = $attributes[SAML_USERNAME_ATTR][0];
        $email = $attributes[SAML_EMAIL_ATTR][0];

        // Check if user exists by SAML NameID
        $stmt = $mysqli->prepare("SELECT * FROM people WHERE saml_nameid = ?");
        $stmt->bind_param('s', $saml_nameid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Existing user - update attributes
            $row = $result->fetch_assoc();
            $uid = $row['uid'];

            $stmt = $mysqli->prepare("UPDATE people SET username=?, email=?, data=? WHERE uid=?");
            $data = json_encode($attributes);
            $stmt->bind_param('sssi', $username, $email, $data, $uid);
            $stmt->execute();
        } else {
            // New user - create account
            $stmt = $mysqli->prepare("INSERT INTO people (username, email, password, auth_source, saml_nameid, data) VALUES (?, ?, NULL, 'saml', ?, ?)");
            $data = json_encode($attributes);
            $stmt->bind_param('ssss', $username, $email, $saml_nameid, $data);
            $stmt->execute();
            $uid = $mysqli->insert_id;
        }

        // Set session variables
        $_SESSION['uid'] = $uid;
        $_SESSION['username'] = $username;

        if (isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            include('./includes/home.php');
        } else {
            include('./includes/login.php');
        }
        break;

    case "logout":
        require_once(SIMPLESAMLPHP_PATH . '/lib/_autoload.php');
        $saml = new \SimpleSAML\Auth\Simple(SAML_AUTH_SOURCE);

        session_destroy();

        // Perform SAML Single Logout
        $saml->logout(SITE_URL . '?u=login');
        break;

    case "register":
        // Redirect to IdP registration or show message
        header('Location:' . SAML_IDP_REGISTER_URL);
        break;

    default:
        header('Location:' . SITE_URL . '?u=login');
}
?>
```

---

### 2. Modify Configuration File

**File to modify**: `includes/config.php` (create from `config.php.default`)

**Changes needed**:
```php
// Change USER_MGMNT to point to SAML handler
define('USER_MGMNT', '/path/to/quizwright/saml_user.php');

// Add simpleSAMLphp configuration
define('SIMPLESAMLPHP_PATH', '/path/to/simplesamlphp');
define('SAML_AUTH_SOURCE', 'default-sp'); // Your SP auth source name

// SAML attribute mapping configuration
define('SAML_USERNAME_ATTR', 'eduPersonPrincipalName');
define('SAML_EMAIL_ATTR', 'mail');
define('SAML_FIRSTNAME_ATTR', 'givenName');
define('SAML_LASTNAME_ATTR', 'sn');

// Optional: IdP registration URL
define('SAML_IDP_REGISTER_URL', 'https://idp.example.edu/register');
```

**Notes**:
- `UDB_*` database constants may become unnecessary (unless keeping dual-mode support)
- Keep `DB_*` constants for application database (still needed for `people`, `info`, `page` tables)

---

### 3. Modify Login View

**File to modify**: `includes/login.php`

**Changes needed**:
- Replace username/password form with SAML login button/link
- Change form action from POST to direct link: `<a href="?u=login">Login with SAML</a>`
- Or use automatic redirect: `header('Location: ?u=login')`
- Remove password input field (no longer needed)
- Remove registration link or point to IdP registration
- Consider keeping the form structure but make it informational only

**Alternative approach**: Create `includes/saml_login.php` view with SAML-specific UI, and include it from `saml_user.php` instead of `login.php`

---

### 4. Modify Database Schema (OPTIONAL but RECOMMENDED)

**File to modify**: `QW_db_structure_20230802.sql` (for documentation) + run ALTER TABLE on production

**Changes needed**:

```sql
-- Option A: Minimal changes (keep backward compatibility)
ALTER TABLE `people`
  MODIFY `password` varchar(255) NULL DEFAULT NULL;
  -- Password no longer required for SAML users

-- Option B: Add SAML-specific fields (recommended)
ALTER TABLE `people`
  ADD COLUMN `auth_source` varchar(50) DEFAULT 'local' AFTER `password`,
  ADD COLUMN `saml_nameid` varchar(255) NULL AFTER `auth_source`,
  ADD INDEX `idx_saml_nameid` (`saml_nameid`);

-- auth_source: 'local', 'drupal', 'saml'
-- saml_nameid: Persistent SAML NameID for unique user identification
```

**Why these changes**:
- `password` becomes optional for SAML users (authentication happens at IdP)
- `auth_source` tracks which authentication method the user uses
- `saml_nameid` stores SAML persistent identifier (more reliable than username for matching)

---

### 5. Update User Provisioning Logic

**Considerations**:
- **Username conflicts**: SAML username might differ from existing local username
  - Solution: Use `saml_nameid` as unique identifier, or prefix SAML usernames
- **Email updates**: Should SAML attributes update existing user records?
  - Recommended: Yes, trust IdP as authoritative source
  - Implementation: Add UPDATE query after successful login
- **Profile data**: Store full SAML attribute set in `data` JSON field for debugging
- **Password field**: Leave NULL or store placeholder for SAML users

**Example user sync logic**:
```php
// After SAML authentication succeeds:
$saml_nameid = $saml->getNameId()['Value']; // Persistent identifier
$attributes = $saml->getAttributes();
$username = $attributes['eduPersonPrincipalName'][0];
$email = $attributes['mail'][0];

// Check by SAML NameID first (most reliable)
$stmt = $mysqli->prepare("SELECT * FROM people WHERE saml_nameid = ?");
$stmt->bind_param('s', $saml_nameid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Existing SAML user - update attributes
    $row = $result->fetch_assoc();
    $uid = $row['uid'];

    $stmt = $mysqli->prepare("UPDATE people SET username=?, email=?, data=? WHERE uid=?");
    $data = json_encode($attributes);
    $stmt->bind_param('sssi', $username, $email, $data, $uid);
    $stmt->execute();
} else {
    // New SAML user - create account
    $stmt = $mysqli->prepare("INSERT INTO people (username, email, password, auth_source, saml_nameid, data) VALUES (?, ?, NULL, 'saml', ?, ?)");
    $data = json_encode($attributes);
    $stmt->bind_param('ssss', $username, $email, $saml_nameid, $data);
    $stmt->execute();
    $uid = $mysqli->insert_id;
}

$_SESSION['uid'] = $uid;
$_SESSION['username'] = $username;
```

---

### 6. Handle Session Management Differences

**Files to review**: All files using `user-session.php` (30+ files)

**Current behavior**:
- `user-session.php:2-4` starts session and sets `$uid` from `$_SESSION['uid']`
- No explicit authentication check - assumes if session exists, user is authenticated

**Required changes**:

**Option A**: Modify `includes/user-session.php`:
```php
<?php
require ("./config.php");
session_start();

// Add SAML session validation
if (defined('SIMPLESAMLPHP_PATH')) {
    require_once(SIMPLESAMLPHP_PATH . '/lib/_autoload.php');
    $saml = new \SimpleSAML\Auth\Simple(SAML_AUTH_SOURCE);

    // If SAML session expired but PHP session still active, force re-auth
    if (!$saml->isAuthenticated() && isset($_SESSION['uid'])) {
        session_destroy();
        session_start();
    }
}

$uid = $_SESSION['uid'] ?? null;

// Add authentication guard (RECOMMENDED)
if (empty($uid)) {
    header('Location: ' . SITE_URL . '?u=login');
    exit;
}
?>
```

**Option B**: Create new `saml_session.php` and update all protected endpoints to use it

**Impact**: No changes needed to individual endpoint files if you modify `user-session.php` centrally

---

### 7. Implement Logout Changes

**File to modify**: `saml_user.php` (case "logout")

**Requirements**:
- Call simpleSAMLphp logout: `$saml->logout(SITE_URL)`
- This triggers SAML Single Logout (SLO) at IdP
- Destroy local session: `session_destroy()`
- IdP redirects back to specified return URL after SLO completes

**Implementation**:
```php
case "logout":
    require_once(SIMPLESAMLPHP_PATH . '/lib/_autoload.php');
    $saml = new \SimpleSAML\Auth\Simple(SAML_AUTH_SOURCE);

    session_destroy();

    // Perform SAML SLO
    $saml->logout(SITE_URL . '?u=login');
    break;
```

**Note**: Unlike local/Drupal logout, SAML logout involves IdP redirect - user will briefly leave your site

---

### 8. Remove or Deprecate Password-Related Code

**Files affected**:
- `includes/password.inc` - No longer needed for SAML users (keep if supporting hybrid auth)
- User profile/settings pages - Remove "change password" functionality for SAML users

**Recommended approach** (if going SAML-only):
- Keep files but don't load them in `saml_user.php`
- Add authentication source check before showing password fields:
```php
// In profile page:
$row = $mysqli->query("SELECT auth_source FROM people WHERE uid=$uid")->fetch_assoc();
if ($row['auth_source'] !== 'saml') {
    // Show password change form
}
```

---

### 9. Update Session Timeout Configuration (OPTIONAL)

**File to modify**: `.htaccess`

**Current settings** (lines 6-10):
```apache
php_value session.gc_maxlifetime 200000  # ~2.3 days
php_value session.cookie_lifetime 2000000 # ~23 days
```

**Considerations for SAML**:
- SAML session lifetime controlled by IdP
- PHP session should align with SAML assertion lifetime
- Recommended: Reduce PHP session lifetime to match typical SAML session (1-8 hours)

**Suggested changes**:
```apache
php_value session.gc_maxlifetime 28800    # 8 hours
php_value session.cookie_lifetime 0       # Browser session only
```

---

### 10. Add simpleSAMLphp Library Integration

**Installation steps** (not code changes, but required):

1. **Install simpleSAMLphp**:
   ```bash
   # Outside QuizWright directory
   composer create-project simplesamlphp/simplesamlphp /path/to/simplesamlphp
   ```

2. **Configure simpleSAMLphp**:
   - `config/config.php` - Set base path, auth admin password, session handler
   - `config/authsources.php` - Configure SP metadata and IdP connection
   - `metadata/saml20-idp-remote.php` - Add IdP metadata

3. **Generate SP metadata**:
   - Access: `https://yoursite.com/simplesamlphp/module.php/saml/sp/metadata.php/default-sp`
   - Provide to IdP administrator

4. **Web server configuration**:
   - Create alias in Apache/Nginx: `/simplesaml` → `/path/to/simplesamlphp/public`
   - Or use separate subdomain: `sso.yoursite.com`

---

### 11. Error Handling and User Feedback

**Files to create/modify**:

**Create**: `includes/saml_error.php` - SAML-specific error page
**Modify**: `saml_user.php` - Add try-catch blocks around SAML operations

**Error scenarios to handle**:
- IdP unreachable
- SAML assertion validation fails
- Required attributes missing from assertion
- User not authorized (e.g., missing required role)
- Database insert/update fails during provisioning

**Example error handling**:
```php
try {
    require_once(SIMPLESAMLPHP_PATH . '/lib/_autoload.php');
    $saml = new \SimpleSAML\Auth\Simple(SAML_AUTH_SOURCE);
    $saml->requireAuth();

    $attributes = $saml->getAttributes();

    // Validate required attributes
    if (empty($attributes['eduPersonPrincipalName'])) {
        throw new Exception('Required SAML attribute missing: eduPersonPrincipalName');
    }

    // Provision user...

} catch (Exception $e) {
    error_log("SAML authentication error: " . $e->getMessage());
    $_SESSION['saml_error'] = $e->getMessage();
    include('./includes/saml_error.php');
    exit;
}
```

---

### 12. Security Enhancements (HIGHLY RECOMMENDED)

**File**: `saml_user.php`

**Changes needed**:
1. **Use prepared statements** for all database queries (current code has SQL injection vulnerabilities)
2. **Validate SAML assertions** (handled by simpleSAMLphp, but verify configuration)
3. **Implement CSRF protection** for state-changing operations (future enhancement)
4. **Add authentication guard** to `user-session.php` (see #6 above)
5. **Secure session cookies**:

**In `saml_user.php` before `session_start()`**:
```php
// Secure session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);  // If using HTTPS
ini_set('session.cookie_samesite', 'Lax');
```

---

### 13. Testing and Validation Requirements

**Test cases to implement** (manual testing checklist):

1. **New user login**:
   - SAML user logs in for first time
   - Verify account created in `people` table
   - Verify session variables set correctly
   - Verify user can access protected pages

2. **Returning user login**:
   - Existing SAML user logs in again
   - Verify attributes updated in database
   - Verify no duplicate accounts created

3. **Logout**:
   - User clicks logout
   - Verify redirected to IdP SLO
   - Verify session destroyed
   - Verify cannot access protected pages after logout

4. **Session expiration**:
   - SAML assertion expires
   - Verify forced re-authentication
   - Verify no stale sessions persist

5. **Attribute mapping**:
   - Verify all expected SAML attributes stored correctly
   - Verify profile data accessible in application

6. **Error conditions**:
   - IdP returns error
   - Missing required attributes
   - Database connection fails

---

### 14. Documentation Updates

**Files to create/update**:

1. **README or installation docs**:
   - simpleSAMLphp installation instructions
   - SAML configuration steps
   - IdP metadata exchange process
   - Attribute mapping documentation

2. **Database migration script**:
   - Document/create SQL script for schema changes
   - Data migration for existing users (if needed)

3. **Configuration template**:
   - Update `config.php.default` with SAML constants
   - Add comments explaining each SAML setting

---

## Summary of Files to Create/Modify

### Files to CREATE:
1. `saml_user.php` - Main SAML authentication handler
2. `includes/saml_login.php` (optional) - SAML-specific login view
3. `includes/saml_error.php` - Error handling page
4. `docs/SAML_SETUP.md` (optional) - Installation/configuration guide

### Files to MODIFY:
1. `includes/config.php` - Add SAML configuration constants
2. `includes/user-session.php` - Add SAML session validation and auth guard
3. `includes/login.php` - Update UI for SAML login flow
4. `.htaccess` (optional) - Update session timeout settings
5. `QW_db_structure_20230802.sql` - Document schema changes
6. Database (via ALTER TABLE) - Add `auth_source` and `saml_nameid` columns

### Files to REVIEW (potential changes based on requirements):
1. `includes/profile.php` - Remove password change for SAML users
2. `includes/home.php` - Display user info from SAML attributes
3. All files using `user-session.php` - May need updates if changing session handling
4. `index.php` - Verify session initialization compatible with SAML

### Files to KEEP (for backward compatibility or hybrid mode):
1. `local_user.php` - Keep for fallback/admin access
2. `drupal_user.php` - Keep if supporting multiple auth methods
3. `includes/password.inc` - Keep if supporting non-SAML users

---

## Migration Strategy Recommendations

### Option A: SAML-Only (Clean Cut)
- Replace all authentication with SAML
- Set `USER_MGMNT` to `saml_user.php`
- Migrate existing users or require re-registration via IdP
- Simplest implementation, most secure

### Option B: Hybrid Authentication (Gradual Migration)
- Create router in `index.php` to choose auth method by user
- Store `auth_source` in `people` table
- Allow existing local users to continue using passwords
- New users automatically use SAML
- More complex but smoother transition

### Option C: Configurable (Most Flexible)
- Add `AUTH_MODE` constant to config
- Conditional loading in `index.php`: `include(USER_MGMNT)`
- Keep all three user management files
- Switch modes via config change
- Best for development/testing

---

## Critical Decision Points

Before implementing, you'll need to decide:

1. **Attribute mapping**: Which SAML attributes map to username/email?
2. **User matching**: Match by username, email, or SAML NameID?
3. **Profile updates**: Should SAML attributes overwrite local profile data?
4. **Registration**: Allow self-service via IdP or require admin provisioning?
5. **Backward compatibility**: Support existing local users or migrate all to SAML?
6. **Session lifetime**: What session timeout is appropriate?
7. **Required attributes**: What SAML attributes are mandatory for login?
8. **Authorization**: Are all authenticated users authorized, or check for specific attributes/roles?

---

## Current Architecture Notes

### Authentication Flow (Current)
1. User visits `index.php`
2. Session started via `session_start()` (line 2)
3. User management file loaded via `include(USER_MGMNT)` (line 29)
4. User manager checks for `?u=` parameter (login, logout, register)
5. If authenticated, sets `$_SESSION['uid']` and `$_SESSION['username']`
6. Includes `home.php` or `login.php` based on session state

### Protected Endpoints (Current)
- All include `user-session.php` which expects `$_SESSION['uid']` to exist
- No explicit null checks - relies on implicit PHP behavior
- **Security vulnerability**: Unauthenticated users may access protected pages if session variables are somehow set

### Database Relationships
- `people.uid` is foreign key in:
  - `info` table (quizzes)
  - `page` table (questions)
- Deleting users would require cascade delete or orphan handling

---

## Implementation Priority

**High Priority** (Required for basic SAML functionality):
1. Create `saml_user.php`
2. Configure `includes/config.php` with SAML settings
3. Modify database schema (add `saml_nameid` and `auth_source`)
4. Update `includes/user-session.php` with authentication guard
5. Install and configure simpleSAMLphp

**Medium Priority** (Important for security and UX):
6. Update `includes/login.php` for SAML UI
7. Implement error handling (`saml_error.php`)
8. Add secure session configuration
9. Test all authentication flows

**Low Priority** (Nice to have):
10. Update `.htaccess` session settings
11. Remove password fields from profile
12. Create comprehensive documentation
13. Implement hybrid authentication support

---

This implementation plan leverages the existing pluggable architecture and requires minimal changes to the core application logic. The key is creating a robust `saml_user.php` file that follows the established patterns in `local_user.php` and `drupal_user.php`.
