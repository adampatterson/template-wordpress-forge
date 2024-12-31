<?php
if (file_exists(__DIR__.'/vendor/autoload.php')) :
    require_once __DIR__.'/vendor/autoload.php';
else:
    echo "Run composer install";
    die;
endif;

if (class_exists('Dotenv\Dotenv')) :
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
else:
    echo "Create a .env file in the WordPress root.";
    die;
endif;

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', env('DB_NAME', 'beaumont_wordpress'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', 'root'));
define('DB_HOST', env('DB_HOST', 'localhost'));


define('WP_HOME', env('WP_HOME', ''));
define('WP_SITEURL', env('WP_SITEURL', ''));

define('ACF_PRO_LICENSE', env('ACF_PRO_LICENSE', ''));
define('GF_LICENSE_KEY', env('GF_LICENSE_KEY', ''));
define('GPERKS_LICENSE_KEY', env('GPERKS_LICENSE_KEY', ''));

define('WP_SENTRY_PHP_DSN', env('WP_SENTRY_PHP_DSN', ''));
define('WP_SENTRY_ENV', env('WP_SENTRY_ENV', ''));


/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', env('AUTH_KEY', ''));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY', ''));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY', ''));
define('NONCE_KEY', env('NONCE_KEY', ''));
define('AUTH_SALT', env('AUTH_SALT', ''));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT', ''));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT', ''));
define('NONCE_SALT', env('NONCE_SALT', ''));
define('WP_CACHE_KEY_SALT', env('WP_CACHE_KEY_SALT', ''));


define('DB_CHARSET', 'utf8');
define('DB_COLLATE', env('DB_COLLATE', ''));


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env('DB_PREFIX', 'wp_');


define('DISABLE_WP_CRON', env('DISABLE_WP_CRON', false));

// Prevent File Modifications
define('DISALLOW_FILE_EDIT', env('DISALLOW_FILE_EDIT', true));

/* Add any custom values between this line and the "stop editing" line. */

define('WP_CACHE', env('WP_CACHE', false));

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', env('WP_DEBUG', false));
}

define('WP_DEBUG_LOG', env('WP_DEBUG_LOG', true));
define('WP_DEBUG_DISPLAY', env('WP_DEBUG_DISPLAY', false));

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__.'/');
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH.'wp-settings.php';
