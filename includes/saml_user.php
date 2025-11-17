<?php
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlResolve */
/** @noinspection PhpConditionAlreadyCheckedInspection */
/**
 * SAML-based user management
 * Uses SimpleSAMLphp installed via Composer
 */

// Load config (for SITE_URL and other constants)
require_once(__DIR__ . '/config.php');

// Load Composer's autoloader from parent project (loads SimpleSAMLphp and all dependencies)
use SimpleSAML\Auth\Simple;
use SimpleSAML\Configuration;

require_once(__DIR__ . '/../../../vendor/autoload.php');

// SimpleSAMLphp config is set via SIMPLESAMLPHP_CONFIG_DIR environment variable
// No need to call Configuration::setConfigDir()

function userLoginViaSAML(): string
{
    global $mysqli;

    $as = new Simple('quizwright-sp');

    if (!$as->isAuthenticated()) {
        return "User not authenticated via SAML.";
    }

    $attributes = $as->getAttributes();

    // Use 'cn' (common name) as the username, fallback to 'uid' if not available
    $name = $attributes['cn'][0] ?? $attributes['uid'][0] ?? $attributes['username'][0] ?? null;
    $email = $attributes['mail'][0] ?? $attributes['email'][0] ?? null;
    $roles = $attributes['roles'] ?? [];

    // Get the Drupal UID for reference
    $drupal_uid = $attributes['uid'][0] ?? null;

    if (!$name) {
        return "Missing username from SAML assertion.";
    }

    // Check for required role - QuizWright requires CALI Staff or facstaff role
    $hasRequiredRole = false;
    $CALIStaff = false;
    $requiredRoles = ['CALI Staff', 'facstaff'];

    foreach ($roles as $role) {
        if (in_array($role, $requiredRoles)) {
            $hasRequiredRole = true;
            // Check if user is CALI Staff
            if ($role === 'CALI Staff') {
                $CALIStaff = true;
            }
            break;
        }
    }

    if (!$hasRequiredRole) {
        return "User does not have required role. QuizWright requires a CALI member faculty/staff account.";
    }

    $stmt = $mysqli->prepare("SELECT * FROM `people` WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count == 1) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $_SESSION['username'] = $row['username'];
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['CALIStaff'] = $CALIStaff;
        $err = "";
    } else {
        $data = json_encode($attributes);
        // SAML users don't have passwords - use empty string
        $password = '';
        $stmt = $mysqli->prepare("INSERT INTO `people` (username, email, password, data) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $data);

        if ($stmt->execute()) {
            $uid = $mysqli->insert_id;
            $_SESSION['username'] = $name;
            $_SESSION['uid'] = $uid;
            $_SESSION['CALIStaff'] = $CALIStaff;
            $err = "";
        } else {
            $err = sprintf("Error: %s\n", $mysqli->error);
        }
    }

    return $err;
}

function userLogoutViaSAML(): void
{
    $as = new Simple('quizwright-sp');

    // Destroy local session
    session_unset();
    session_destroy();

    // Full SAML logout - logs out of both SP and IdP (Drupal)
    // The return URL will be used after logout completes
    $returnUrl = SITE_URL ?? '/quizwright/';
    $as->logout($returnUrl);
}
