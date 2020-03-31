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
define( 'DB_NAME', 'c1e2w' );

/** MySQL database username */
define( 'DB_USER', 'c1e2w' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Kestufe12' );

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
define( 'AUTH_KEY',         '+^`kMd?H~]oUn{_AeJ!+Gqf9r%]xT]gy~k<W8UA!0SF[|l = 7X QvLvc`8o1Jk8' );
define( 'SECURE_AUTH_KEY',  'QS3MxP]H4i,X?i@D$k 2BLJD@>i)_A2im>Rz:6tp&F&zguNPQotnrI0__IoDfzl:' );
define( 'LOGGED_IN_KEY',    'rod;^5O-vA8F_}i*/B7(uj^oEX&]@i~W&8vT/EJ87GwukA&s<x{885>OAf8r>`xh' );
define( 'NONCE_KEY',        'uf8>ukt2i*bBLDL3}_N&dHMSqD$x)JouqDIy&BSp8-`>SO!S2`DF;1@.VVBfy5Kw' );
define( 'AUTH_SALT',        './#x(7$NaTSpS@55~Gf{TB/Dd&Q_YB*?CMsMUzP|ySs&JD627j:XZuo3LLQNHZq3' );
define( 'SECURE_AUTH_SALT', ',IyvD_m)a4C9fiZB(KK>yq7>|[fC*_0(}@JUnOJ9z&,3AE5X;`PYVqzAKGpRa44~' );
define( 'LOGGED_IN_SALT',   'XJ~#&T+IlmW=jls{t.6u!Aa;mV:3g{nwvGK0rCEZ_C+esGK,Q6NjOE7}1Ij9JD7e' );
define( 'NONCE_SALT',       '6A]pO0s79Gz2*QCO;q6v#du4s}w8J9VG(=J1CdLTn+`#&SPg ]BP#fiV/DArt.b3' );

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
