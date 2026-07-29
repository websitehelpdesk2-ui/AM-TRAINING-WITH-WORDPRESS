<?php
define( 'DB_NAME', 'wordpress' );
define( 'DB_USER', 'wp_user' );
define( 'DB_PASSWORD', 'wp_password' );
define( 'DB_HOST', 'db:3306' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    $forwarded_host_parts = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
    $_SERVER['HTTP_HOST'] = trim($forwarded_host_parts[0]);
}

if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && stripos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';
    $_SERVER['REQUEST_SCHEME'] = 'https';
}

if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    $forwarded_host_parts = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
    $forwarded_host = trim($forwarded_host_parts[0]);
    $forwarded_proto = !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) ? trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]) : 'https';
    $runtime_url = $forwarded_proto . '://' . $forwarded_host;
    define('WP_HOME', $runtime_url);
    define('WP_SITEURL', $runtime_url);
} elseif (!empty($_SERVER['CODESPACE_NAME']) && !empty($_SERVER['GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN'])) {
    $codespace_url = 'https://' . $_SERVER['CODESPACE_NAME'] . '-8080.' . $_SERVER['GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN'];
    define('WP_HOME', $codespace_url);
    define('WP_SITEURL', $codespace_url);
} else {
    define('WP_HOME', 'https://musical-space-spoon-q7w9jgq4v97q3xrpg-8080.app.github.dev');
    define('WP_SITEURL', 'https://musical-space-spoon-q7w9jgq4v97q3xrpg-8080.app.github.dev');
}

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
