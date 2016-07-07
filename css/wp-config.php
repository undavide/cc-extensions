<?php

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'c3c63ab2a_34b2f9');

/** MySQL database username */
define('DB_USER', 'c3c63ab2a_6e0000');

/** MySQL database password */
define('DB_PASSWORD', 'ium54P66Sx');

/** MySQL hostname */
define('DB_HOST', 'hostingmysql216.register.it');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Theoretically it should empty spam each day */
define( 'EMPTY_TRASH_DAYS', 0 );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'y3pcbyqi5sr5scib8nypnpmeepmv8ymxoyvrmrifgorjbd5vhdzzzvlmojvy4ppz');
define('SECURE_AUTH_KEY',  '6ahh1r4fnsgsqtc6v6oxaxiuz3qg1aaetuayfkqcrcpwkvaa3qavuiphn00mv1rq');
define('LOGGED_IN_KEY',    'yrdvzq6yzvfafticfjshfcvsgfondph2l8p4n1jblypruxtfvbuwqob381wz4upm');
define('NONCE_KEY',        'v48y3qxs7x9wwwbmnew7nq6rykokfkz0uhseu3qc2z7gbfnfmuyf5qpmrs9zvbzd');
define('AUTH_SALT',        'tpmnfzqcsa6oth6ikykhtbh5ro99dg0qxn5wngratjupc4i4pelbpdolivxtqvyz');
define('SECURE_AUTH_SALT', '4gvtq1o4f59ckcqmk9ttomavttgvfvefsst2117mphyiezbspcoqrtbvrr439qht');
define('LOGGED_IN_SALT',   'pzs7cjcuni4g7naamllhlvypc9lkv43rdiczqkvemx9hdyrw6jp6ggwsd0szybvh');
define('NONCE_SALT',       'pf2bg49ami9yolegsr7uwls4q3cvj1vqqflruyycb0ikfalois9dqx88wwrpk1co');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'en');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/* Might fix the Add Media button not working issue?! */
define('CONCATENATE_SCRIPTS', false );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/** Defines 5 as maximum revision versions kept */
define('WP_POST_REVISIONS',5);