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
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fresh' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'zeaP2~:V,Jy&XL-UpYQ1lrJrwGK){|j7g!2UP<K#7ARwM@=:j{e(WmNd)kQpF8sU' );
define( 'SECURE_AUTH_KEY',  'W,?pc0|$.ujwcH4Oz@mS,FYmj)0:,t|%tZQ;v{arq_./Q%rr3/RYK*t6:[:$tscs' );
define( 'LOGGED_IN_KEY',    '!2!p ~:~,:EZC-;|uw][ IEcH<dF(bi]9L8~9E>|;1{T%jZ37E-a;3?gM+/ Q+ s' );
define( 'NONCE_KEY',        'hes4L!{CjGgd[mst,N/eg2a|>>BE0Y$<=4itcIavDr;^vHTY?*<4tHowK[ZAxg-#' );
define( 'AUTH_SALT',        '`}ao_62K;VFWd<l/M7wsK(*lUn+wb%}PCzy1W+rsxK@M0WAQrV`T@xTY bD9.k(M' );
define( 'SECURE_AUTH_SALT', '5#B3VJOG}r,ApDz| Sr7W44B>r)sSe}d|gG+(D]ZXuL38LX^vV5f?uYQDOp3qlVw' );
define( 'LOGGED_IN_SALT',   '^V%w!Txl3.$B%nI&%0Qc@ygY;uK<1tI2H#<}tUm;A@Rq^?6<?a2Wx,rd!,er^4m&' );
define( 'NONCE_SALT',       '%}pW.vL~-u1f8>cB$mUk-u_!L-e`;HDjyDNm4l~hAlTjviCTqf(CPsmDq,=NtZ)D' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
