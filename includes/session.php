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

// Same memcached node SimpleSAMLphp already uses. Override per environment.
$qwMemcached = getenv('QW_MEMCACHED') ?: '10.x.x.x:11211';

if (extension_loaded('memcached')) {
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
    // No shared store available: sessions stay node-local and the multi-pod
    // login/logout flapping this file exists to fix will come straight back.
    error_log('session.php: neither the memcached nor the memcache extension is '
        . 'loaded; falling back to node-local file sessions.');
}

// The four settings below lived in .htaccess and have never actually applied
// under FPM. The originals were also wildly mismatched: gc_maxlifetime 200000
// (~55h) against cookie_lifetime 2000000 (~23 days).
ini_set('session.gc_maxlifetime',  '28800');  // 8h server-side; align with SSP session.duration
ini_set('session.cookie_lifetime', '0');      // expire with the browser session

// Escape hatch for a plain-HTTP dev box only. Leave unset everywhere else — a
// non-secure session cookie is sent over cleartext.
ini_set('session.cookie_secure',   getenv('QW_SESSION_COOKIE_SECURE') === '0' ? '0' : '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');      // reject attacker-supplied session IDs

session_start();
