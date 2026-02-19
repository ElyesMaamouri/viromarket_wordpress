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
define( 'DB_NAME', 'ecomshop' );

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
define( 'AUTH_KEY',         's  hK;LF|X8.HsNMTU^Y|PDbF?#:t#l/cl4nto1tJ  H.>>:ALxA|Hyl~g,qy>U6' );
define( 'SECURE_AUTH_KEY',  'h?|bbe[u/b2n:((~H0WW9X=ZByp)*J {{sg}%>5MK*cxgGXsiV=KEr4i~JJ[Ku:/' );
define( 'LOGGED_IN_KEY',    '+XAMYLy>C%_/F~3y1Y>c+R8sd5 }L*:tsambjcKNe8P5ab7EP;.TXJAJN%YTxU!(' );
define( 'NONCE_KEY',        '<7y4j6O])<^r_(uZA~ lggK@|UVhUdLS!mOn9nHA8+xihv}C>dL& -z;Jnqz_r!d' );
define( 'AUTH_SALT',        'aQ}d1(p+vxlk=U<G-S,`7oYgR{8.E9&?HHcT}[`_dX>NeK*#){B5L^j`a`/qW%Kt' );
define( 'SECURE_AUTH_SALT', '7l~[Bp&b1JKp&n~wQ(yy*Z1`a&DQVMU;h%z&M.xu}xMkvaq)R8FB[EIdd}.><Usu' );
define( 'LOGGED_IN_SALT',   'dw0P?n7a4Q(/oupPzg[F2q`?aX/AXt:/i]|D^<gu+F<u6>R`ruz=wi?p?`wU**<e' );
define( 'NONCE_SALT',       'Nw|Z8>84oJT29F[=.=[2c1T3)e }a7h+|;}kbg5lnt`xt:#0-M@]2gaahhyaW`BH' );

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
$table_prefix = 'wp_dgbi';

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
define( 'WP_HOME', 'http://localhost/ecomshop' );
define( 'WP_SITEURL', 'http://localhost/ecomshop' );



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
