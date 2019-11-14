<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hanhan' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ee}!v_`!Cn>#,k{>^y;C*vkXUX*|]sQd3F8^?;;`Rlw,hQ){tQNU;rv0Zd F*Dl*' );
define( 'SECURE_AUTH_KEY',  '3:e;T<o{#tU9dXH$:BQ++X%?37H[2heqE>fQg0sCOmKz&dLb-eiUQ>GUxAQCoP4s' );
define( 'LOGGED_IN_KEY',    '-zU4dDG;i*=IJO!p7]E9 s3?w}iUdqZ]L&uKf[RDG5A&nnj8m`8e+>ndpETOQm7L' );
define( 'NONCE_KEY',        '0Gt7b+saJYvX|@q>dBsek~(^L64zuw(!X(#a<H&0 Af!Lwi0R)7M&ViTAnJ)HN)^' );
define( 'AUTH_SALT',        'VB^N~hW&Pn^xhMc9t-zK![PO<{ao5zG LyO/baStT69$Bt ua_MV2R]Dhk!]wBQi' );
define( 'SECURE_AUTH_SALT', 'Ei_^O9T<9oF~%P`Du>8wS[Jq[;aA0-7[#MaLhOa%d&<C7?xt%]FbN{Y=GI CXA6p' );
define( 'LOGGED_IN_SALT',   'W?}diO)2]%KSQQ|KdhnI.~:r_NVi,VIhIfcF*7d<X#]?]<H1$oF^Q$~tN0@oDFS,' );
define( 'NONCE_SALT',       'EeCpT1LBeL>$GtD_X{(xv*NjCy)1gdLt7#kits#)bCNSS*tGI+wb.ZHx}a`;Z6z1' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
