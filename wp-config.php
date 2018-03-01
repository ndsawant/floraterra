<?php
define('WP_AUTO_UPDATE_CORE', false);// This setting was defined by WordPress Toolkit to prevent WordPress auto-updates. Do not change it to avoid conflicts with the WordPress Toolkit auto-updates feature.
/**
 * The base configurations of the WordPress.
 *
 * You can find more information by visiting {@link http://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

define('DB_NAME', 'floraterra_com');
define('DB_USER', 'floraterra_com');
define('DB_PASSWORD', '65C7zf2Akv');
define('DB_HOST', 'localhost:3306');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

 

define('AUTH_KEY',         'nB1oi+5BVAsE+y#nA0sC6-{)v}3%|ANK~]$9Llg~X32J/?p&E0llA@2iF3)w_izD');
define('SECURE_AUTH_KEY',  'b3p.y++3OF|Rw}KX z+v6/0d5_<GV{(GIT>a1-@dt<le9>3R**?q}x<Sp|HleK?;');
define('LOGGED_IN_KEY',    ',YLQ9-yzWhqr_kR5$bVxW9]qglU;*Pf>fkP]K[CKrVR o#+%D>i|WA3asZh/0D@+');
define('NONCE_KEY',        'gMPye-jIL$h|qm}0G-_^bU/M{;ot0rwSEDV?d|}E<_Q8g`qq<kQD}M|<}_J&P7ME');
define('AUTH_SALT',        '4sQ7)U;fjI{PD-i1-ekj5<8Fo6;d*n~1(^n*pQw+bFLb5arzPRZP7;V`O^Yc)>?B');
define('SECURE_AUTH_SALT', 'R%<RU{?f@:+;~-7 U;`[+CYM)}$)zYsZK$+6d,jsnpd^%;--JX)v-|XAn@&9vfJA');
define('LOGGED_IN_SALT',   '4MJ9P{1 !.[ %R?+MDisN/mNxYj+g%[K_KR*g6XPM|hb|=-rUZIWZ_|_uhLM (eo');
define('NONCE_SALT',       'Mul*+hOh4{Ub-<o=%d7A)q;Jb-&^f>>kCx^dR,BmZI:##x(DQq1!ckLsp)g1^*!}');

$table_prefix  = 'pac_';

define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
add_filter('xmlrpc_enabled', '__return_false');