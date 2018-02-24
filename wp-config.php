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
define('DB_NAME', 'dolly_com');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'akhay7285');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'wGiV[DK;c<w;0oC]4,jG^7MsP7GpSWBp]8UPXfBA4A}?26yc>&df&>>3)?^L>{ND');
define('SECURE_AUTH_KEY',  'wM2%X}&Tem],_1{c:ODU~}S*rwe<qI|)ii(*/PeIL0bDr0_oK~qm9&iv%fEuMT$*');
define('LOGGED_IN_KEY',    'Qi&le<hvZ,gwL8g#2jyw;,X9S+ta*BFsvU$MH9[3pep2#OoEy<hm@HpnY%_+zTY7');
define('NONCE_KEY',        '`UUid(rZ|l~u0`uzM|{=ORH(zn{:SEt^^SpaIPt0^33,/g*A:=Soo</]|eh:dVXM');
define('AUTH_SALT',        ',dE=O%wqc.1ynmepr1xuN/L7;o(j<jvhcV#$S&R{w}=FO}d/;p&L}HMA!((W=2U!');
define('SECURE_AUTH_SALT', ']k%X.,WQI228uC?],2b+,`vu`&+FxZbIMWCtF]%x9RxkA?;~pGt.)m:Twyb=9`3D');
define('LOGGED_IN_SALT',   ']BPuMtu/+N&DfF2n&obiQsw@b.RU(: 2mg8{;7ND)KV8^F4<5j9s06g8,8 ?)?p9');
define('NONCE_SALT',       'esId0%G)X6[{:iA-EUBVdZ+4Fqh`MJ:-%l(hw$2G=?Zvwa77zNc--uag@%I=l],k');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
