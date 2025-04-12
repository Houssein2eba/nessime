<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\xampp\htdocs\wordpress\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'JbnX6oE<0&16&.((7&gTB#/jdqa*;A)Dt~tp8$yVN@dnJ!YOxVy99m*syI$x%x-2' );
define( 'SECURE_AUTH_KEY',  '>P]tkG&LSov6D.7REHQs0M^61n(UU}+Gk;e/XJw[UA[Xb0w<,r$2CG[[122&O1P0' );
define( 'LOGGED_IN_KEY',    '0aF(Y([R|AZ;:T;}4f$gG[b/STqwy^t[C@OV(UcVwzv`CHP|p;Y 0rL~11=wT`L5' );
define( 'NONCE_KEY',        'f;eAb&,CE<4y43;7X@2en,CCFr*:2|zw;jSmnz!.{PSQMYhwH-jGo l!2|?QW*!<' );
define( 'AUTH_SALT',        'N*s0MZef/ctgqKs(+`]A9yp5Q*TN7|VLxYo=?19T[w/`FRF#vgi7=wAFcUwQ!u/6' );
define( 'SECURE_AUTH_SALT', '=?T14Z&TY3YiHL|[LK2MFfN`R9~el#L(2_ _,Ywzs .xzX#%akpJ&zqjQ2B/%B]r' );
define( 'LOGGED_IN_SALT',   'LJ:{LeEO Q9{S)Us?*bRo^btzZVb?[H^gODMA]j=Z~j<37xZ</v#z|nC,m+e?P*>' );
define( 'NONCE_SALT',       'wiPy2ws#%!_wgYlBF@og.KkFZ#|8&mvmKEbM]0YE:bwKH,(>9D!-.+B5W;H#~1H[' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
