<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
define( 'DB_NAME', 'tecnotr1_wp1' );

/** MySQL database username */
define( 'DB_USER', 'tecnotr1_wp1' );

/** MySQL database password */
define( 'DB_PASSWORD', 'C.EnFuKRBp877yOBpwE86' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'CfgKUWu6RNAA9Zg0eeP0I8OWYBW3XogLps6vfN7i384wHfewVEMSQc7Fw0NySAyp');
define('SECURE_AUTH_KEY',  'I04uMApFkL8o6xifybXwp9NGJJBAjt7JOr2vTRbn4xs4e6EWdicw8m7XMgbY9y9M');
define('LOGGED_IN_KEY',    'rlHHhg1QBdHpeDUmtY7aTi4spnjBIGGdvHEB4v5Q29EVWqMMnNlbXaaR1jv0s4rb');
define('NONCE_KEY',        'VbmMyjsLo6F31ZF8gWS8nEY2ztWlcorfZ49JDKI5PyxrYILtL77nzLEgglhFHyDU');
define('AUTH_SALT',        'bjUNWBBzg7KPBDFgzRSi1ykEAQ0TTwH7NZO5JYnSSRf0KJRWAnI7nhAkECqdFf17');
define('SECURE_AUTH_SALT', 'xUVIsMFMH2SowFlvRbfkRdstGDWYVDHqYWRMK2Fqa7GXUJreG4qJ8AQ7fg33icta');
define('LOGGED_IN_SALT',   '5bmC0hIdv1f7v3CEyFowClb2wFoXM0kpi5iIwLMCpSCqScjtxdbzo2MGMW9p5Wbe');
define('NONCE_SALT',       '6yMVoP0xt4iqtLeVYE62GWEUaMoZXsaWNxOLLI5c0fpE9Lrf8YP76hIegkHBYciU');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
