<?php
/**
 * SAML Logout Handler
 */

// Load Composer's autoloader from parent project
use SimpleSAML\Auth\Simple;
use SimpleSAML\Configuration;

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/includes/config.php');

// Use the same session as SimpleSAMLphp
session_name('SimpleSAML');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require USER_MGMNT; // loads saml_user.php

userLogoutViaSAML();