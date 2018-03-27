<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'itailove');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'M5NU541dhqMqxrRb&.hO}fB~@dW5(lIjTZTKQi7wMV7#,==@H_WmavM0J[_+lTqX');
define('SECURE_AUTH_KEY', 'VA+w1(qGmLZ?YS?W]Yi~%mywrC=~TJ:sT,e96-#OJ>w|4TF4?m@Fvg4pAj;c_heI');
define('LOGGED_IN_KEY', '-!NwZ o:^*$rjGC;,r,MvSbcIW)y`/62Bx(4/]bG~H,IVXyy.ej)dqekXMu,@-3m');
define('NONCE_KEY', 'z2O,@(MMUwTd.JSBbxKh(|uA<]]YoOmGMPZONL4-}^&E?#*#PzRceuX9b_Ajrr9C');
define('AUTH_SALT', '}?aa2UH*h71:<.s1oidi(wc~=@*wAq+8eT%.OK8ztsb#j@+S;v#8=vv@*Cd)Dmp.');
define('SECURE_AUTH_SALT', '06.BsO-bwnsOMf0+E$Jgr_3m)nA<^MFUd:=a^M[Z;-x2uRmB5/d,5uqgh*a.Xs!)');
define('LOGGED_IN_SALT', 'RAL38Xg^z{lu,lVeT[,9<FfqMN$ENDX2B6H|iyZvV?sXY@~[#`yUjGzX[nL.1J[d');
define('NONCE_SALT', 'I!5um)??^f.dYLOlC.|B^g|0AVgr|Qu^S*O`5fMNT?5Ca$E2Dcn3X$Vo12Aw~,A ');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

