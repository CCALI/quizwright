<?php
/**
 * SAML Login Handler
 */

// Load Composer's autoloader from parent project
use SimpleSAML\Auth\Simple;
use SimpleSAML\Configuration;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/includes/config.php');

// SimpleSAMLphp config is set via SIMPLESAMLPHP_CONFIG_DIR environment variable
// No need to call Configuration::setConfigDir()

// Authenticate via SAML using the quizwright-sp entity
$as = new Simple('quizwright-sp');
$as->requireAuth();

// Use the same session as SimpleSAMLphp
// SimpleSAMLphp has already started its session, but we need to use it for our app
session_name('SimpleSAML');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require USER_MGMNT; // loads saml_user.php

$error = userLoginViaSAML();

if ($error != '') {
    die("Authentication error: " . htmlspecialchars($error));
}

// Debug: Check if session variables are set
error_log("login.php - After userLoginViaSAML - Session ID: " . session_id() . ", uid: " . ($_SESSION['uid'] ?? 'not set') . ", username: " . ($_SESSION['username'] ?? 'not set') . ", SESSION data: " . json_encode($_SESSION));

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 0) {
    die("Session not set after SAML login. uid=" . ($_SESSION['uid'] ?? 'not set') . ", username=" . ($_SESSION['username'] ?? 'not set'));
}

// Ensure session is written before redirect
session_write_close();

$returnUrl = $_GET['return'] ?? SITE_URL;

// Fix for :80 port appearing in HTTPS URLs when behind proxy
// Remove :80 from the URL if it exists
$returnUrl = preg_replace('/^(https?:\/\/[^:\/]+):80(\/|$)/', '$1$2', $returnUrl);

header("Location: " . $returnUrl);
exit();
