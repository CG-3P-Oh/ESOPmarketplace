<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'esopmark_wp3p2025' );

/** Database username */
define( 'DB_USER', 'esopmark_4759fhw' );

/** Database password */
define( 'DB_PASSWORD', '{o?u{{NM*%~2' );

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
define('AUTH_KEY',         '*<g$d.MEY;igX}?FF(`g~o&VpFrBM8b1)-{$NB0$lhV)IU09h Ax2:~xf}60@oB>');
define('SECURE_AUTH_KEY',  'MlbY-Ne?2;)2@; SDwHb^qo|Ve7bU;C+Y11=[#He_o@Y1uH7-?>Vz[SB1tUfG{DM');
define('LOGGED_IN_KEY',    ',mcjmB,([WHTQoJpWpYq6|w@J*|$NPKF/Q|^q&|!m[4T}j`+TCq%iRp %u>O7Iwq');
define('NONCE_KEY',        '@caRNLB~|$|;nK^C%z1b~G=b12*H9fC2q 7vLB!e:=CpPP,q6h4#^~zQgJ6I0PR|');
define('AUTH_SALT',        '|f&m16<D|<+m/8_msxBqy~$-gAEM}>C6moQROBh5KZ^AWg$s,[vQYHz]8Zpftu(l');
define('SECURE_AUTH_SALT', 'C%))$a_s[1?sZ@gpXm-*Q8;h#n[e,+4IE{LYQHxBf8kwQG$q=s9p8(itv|{6B:j-');
define('LOGGED_IN_SALT',   '^P|:*T{q74M?SMB:{rVt)|H_-BFu-duBBcDxW1kA:+!r`&kR{fL}pk+-JSg<@+My');
define('NONCE_SALT',       'Vgc R| yZJS_NVApxR&%=--?lI-;<=|n<`q)$J.7#[rdAkGx6](_YSztv*I]5Y$R');

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
define( 'MAPBOX_ACCESS_TOKEN', 'pk.your_token_here' );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
