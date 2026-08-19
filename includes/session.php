<?php
/**
 * Session bootstrap. MUST be required before any output and before session_start().
 * Has no dependencies — safe as the first require in any entry point.
 *
 * Why this file exists: `php_value` directives in .htaccess are inert under the
 * PHP-FPM/FastCGI SAPI, so every session setting has to be applied from PHP, and
 * therefore before the session handler is chosen and the session is opened.
 * Putting them in config.php is too late — config.php is loaded after
 * session_start() in most of the old entry points.
 */

if (!function_exists('qw_session_destroy')) {
    /**
     * Tear a session down completely: server-side data *and* the browser cookie.
     * session_destroy() on its own leaves the cookie sitting in the browser.
     */
    function qw_session_destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        // Clear server side.
        $_SESSION = [];

        // Clear the cookie.
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => $p['path'],
                'domain'   => $p['domain'],
                'secure'   => $p['secure'],
                'httponly' => $p['httponly'],
                'samesite' => $p['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
    }
}

if (session_status() === PHP_SESSION_ACTIVE) {
    return;
}

// Deploy-specific settings live in session-config.php, which is gitignored —
// copy includes/session-config.php.default and fill it in. They are kept out of
// config.php on purpose: config.php is required *after* the session is already
// open, it connects to MySQL at include time, and this file has to stay free of
// dependencies to be safe as the first require in an entry point.
if (is_file(__DIR__ . '/session-config.php')) {
    require_once __DIR__ . '/session-config.php';
}

// Either source works: the environment wins if set, otherwise the constant from
// session-config.php. $_SERVER is checked as well because values injected as
// FastCGI params land there and getenv() does not always see them.
//
// There is deliberately no built-in default. A plausible-looking wrong address
// switches the save handler to memcached and then silently loses every session
// on every request — a worse failure than not using memcached at all.
$qwMemcached = getenv('QW_MEMCACHED')
    ?: (string)($_SERVER['QW_MEMCACHED'] ?? '')
    ?: (defined('QW_MEMCACHED_HOST') ? QW_MEMCACHED_HOST : '');

if ($qwMemcached === '') {
    // Defining QW_MEMCACHED_HOST as '' is how a single-node environment says
    // "no shared store needed" — node-local sessions are correct there, so stay
    // quiet. Not configuring it at all is a broken multi-pod deploy: say so, or
    // the login flapping this file exists to fix comes back with no explanation.
    if (!defined('QW_MEMCACHED_HOST')) {
        error_log('session.php: no memcached host configured. Set QW_MEMCACHED in the '
            . 'environment, or QW_MEMCACHED_HOST in includes/session-config.php. '
            . 'Sessions will be node-local files and will NOT be shared across pods.');
    }
} elseif (extension_loaded('memcached')) {
    ini_set('session.save_handler', 'memcached');
    ini_set('session.save_path',    $qwMemcached);

    // Keep app sessions in their own keyspace, clear of SSP's keys on the same node.
    ini_set('memcached.sess_prefix', 'qw.sess.');

    // Serialize concurrent requests on the same session. Leave ON — this is the
    // per-session locking that the `files` handler gave us for free and that a
    // naive memcached setup does not.
    ini_set('memcached.sess_locking',       '1');
    ini_set('memcached.sess_lock_expire',   '30');
    ini_set('memcached.sess_lock_wait_min', '1000');
} elseif (extension_loaded('memcache')) {
    // Older extension. Weaker locking (memcache.lock_timeout); prefer `memcached`.
    ini_set('session.save_handler', 'memcache');
    ini_set('session.save_path',
        'tcp://' . $qwMemcached . '?persistent=1&weight=1&timeout=1&retry_interval=15');
} else {
    // A host is configured but there is no way to reach it, so sessions stay
    // node-local and the multi-pod login/logout flapping comes straight back.
    error_log('session.php: a memcached host is configured but neither the memcached '
        . 'nor the memcache extension is loaded; falling back to node-local file sessions.');
}

// The four settings below lived in .htaccess and have never actually applied
// under FPM. The originals were also wildly mismatched: gc_maxlifetime 200000
// (~55h) against cookie_lifetime 2000000 (~23 days).
ini_set('session.gc_maxlifetime',  '28800');  // 8h server-side; align with SSP session.duration
ini_set('session.cookie_lifetime', '0');      // expire with the browser session

// Escape hatch for a plain-HTTP dev box only. Leave unset everywhere else — a
// non-secure session cookie is sent over cleartext.
$qwCookieSecure = '1';
if (getenv('QW_SESSION_COOKIE_SECURE') === '0'
    || ($_SERVER['QW_SESSION_COOKIE_SECURE'] ?? null) === '0'
    || (defined('QW_SESSION_COOKIE_SECURE') && !QW_SESSION_COOKIE_SECURE)) {
    $qwCookieSecure = '0';
}
ini_set('session.cookie_secure',   $qwCookieSecure);
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');      // reject attacker-supplied session IDs

session_start();
