<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 * * and other things.
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

/**
 |
 | Project configuration
 |
 | Pull the configuration file from the project root
 |
 */
if ( ! defined( '__ROOT__' ) )
	define( '__ROOT__', __DIR__ . '/..' );

require_once __ROOT__ . '/conf.php';


if ( HTTPS_SUPPORT )
	$httpProtocol = 'https';
else
	$httpProtocol = 'http';

$hostName = $_SERVER[ 'HTTP_HOST' ] ?: $_SERVER[ 'SERVER_NAME' ];


/**
 |
 | Routing
 |
 */
// Fetch media files from the WIP server
if ( CMS_FETCH_MEDIA_REMOTELY )
	if ( $hostName !== CMS_REMOTE_ADDRESS )
		if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/content/cms/' ) !== false )
			return header( 'Location: ' . $httpProtocol . '://' . CMS_REMOTE_ADDRESS . $_SERVER[ 'REQUEST_URI' ], true, 302 );

// If accessing the CMS backend, ensure that only the canonical version / instance is accessed
if ( RESTRICT_ACCESS_TO_CANONICAL_CMS_ONLY )
	if ( $hostName !== CMS_CANONICAL_ADDRESS )
		if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/cms' ) === 0 )
			return header( 'Location: ' . $httpProtocol . '://' . CMS_CANONICAL_ADDRESS . $_SERVER[ 'REQUEST_URI' ], true, 302 );



/**
 |
 | WordPress Website Roots
 |
 | Set it such that it is contextual to the domain that the site is hosted behind
 |
 */
define( 'WP_HOME', $httpProtocol . '://' . $hostName );
if ( ! defined( 'WP_SITEURL' ) )
	define( 'WP_SITEURL', $httpProtocol . '://' . $hostName . '/cms' );



/**
 |
 | Cron
 |
 */
// if ( BFS_ENV_PRODUCTION )
//      define( 'DISABLE_WP_CRON', true );



/**
 |
 | Database
 |
 */
// define( 'WP_ALLOW_REPAIR', true );
// MySQL
/** The name of the database for WordPress */
define( 'DB_NAME', CMS_DB_NAME );
/** MySQL database username */
define( 'DB_USER', CMS_DB_USER );
/** MySQL database password */
define( 'DB_PASSWORD', CMS_DB_PASSWORD );
/** MySQL hostname */
define( 'DB_HOST', CMS_DB_HOST );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/** Use an SSL connection when connecting to the database */
if ( CMS_DB_SSL )
	define( 'MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL );

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
define( 'AUTH_KEY', CMS_WP_AUTH_KEY );
define( 'SECURE_AUTH_KEY', CMS_WP_SECURE_AUTH_KEY );
define( 'LOGGED_IN_KEY', CMS_WP_LOGGED_IN_KEY );
define( 'NONCE_KEY', CMS_WP_NONCE_KEY );
define( 'AUTH_SALT', CMS_WP_AUTH_SALT );
define( 'SECURE_AUTH_SALT', CMS_WP_SECURE_AUTH_SALT );
define( 'LOGGED_IN_SALT', CMS_WP_LOGGED_IN_SALT );
define( 'NONCE_SALT', CMS_WP_NONCE_SALT );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = CMS_WP_DB_TABLE_PREFIX;

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
define( 'WP_DEBUG', CMS_DEBUG_MODE );
define( 'WP_DEBUG_LOG', CMS_DEBUG_LOG_TO_FILE );
define( 'WP_DEBUG_DISPLAY', CMS_DEBUG_LOG_TO_FRONTEND );
ini_set( 'display_errors', CMS_DEBUG_LOG_TO_FRONTEND ? '1' : '0' );

/* Add any custom values between this line and the "stop editing" line. */

/**
 |
 | WordPress Updates
 |
 | Using a plugin for this now.
 |
 */
// define( 'WP_AUTO_UPDATE_CORE', false );
// define( 'AUTOMATIC_UPDATER_DISABLED', !! CMS_AUTO_UPDATE );

/**
 |
 | Theme
 |
 */
define( 'WP_DEFAULT_THEME', CMS_DEFAULT_THEME );

/**
 |
 | Media and Uploads
 |
 */
if ( ! defined( 'UPLOADS' ) )
	define( 'UPLOADS', '../content/cms' );  # this one is relative to `ABSPATH`





/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', __DIR__ . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
