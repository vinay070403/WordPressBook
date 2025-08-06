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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'l.l.kI+`v?uT/|mNiSwSkUYtLB1(qalVGA$HN]})`7KQ/;:uMg%*q>5%2h9qTZ<h' );
define( 'SECURE_AUTH_KEY',   'LM&ek2X-|RfyeJzjk1cl3@DTnmov1gk_ Px;3o`$KPh*,s@yMsDJJ<v@Cn[|rW>~' );
define( 'LOGGED_IN_KEY',     'A]9?c4yksSnkEVK`q^ ztz-)I*&KvxoW|sN(R[p^xMlg.-fZV(?$,02jgY$D0_Mf' );
define( 'NONCE_KEY',         'B^-w_U)(3tKE/.-${C-CYo<v+12-3d;{p*Rr v^qvc/5=iC=*S@]ttqdzw8^xER}' );
define( 'AUTH_SALT',         '3vVAkJjY1<3V`bM%,Aq1qF6MaWm/`@:G!F)S3}=a4W#3 i7wg2T>t&]$wX zk%hF' );
define( 'SECURE_AUTH_SALT',  '2hkJ)%)5BtXm6QM(#(@.Lk:s-uu^OgG7M5`|SS6do1x?%hDof;eUO~nBHn3NPKJ7' );
define( 'LOGGED_IN_SALT',    '*ywv,ER@/^srk7lnCc@PbLL/I6G! r xHo:Yg)cD|?00|zlWZMe5[gq:NqPBSp7A' );
define( 'NONCE_SALT',        'h`e9?j*Y^>yVcDLS:nc!25K(dzyAbS4omeR#?X<LlBk~!F!O0I&<`!s5YF1YyBT~' );
define( 'WP_CACHE_KEY_SALT', '$?r#.yA7R%Wt%~zVnM2QS-~gL w|EYbAO>aw BToiUA4,p[T~pq/;@NDme#MgM$)' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
