<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('TARIFA_BANCARIA',0.001);
define('TARIFA_MONTOS_ALTOS',531);
define('VALOR_MONTOS_ALTOS',10000);

define('ESTADO_TRANSACCION_PENDIENTE','Pendiente');
define('ESTADO_TRANSACCION_PROCESADO','Procesada');
define('ESTADO_TRANSACCION_VERIFICACION','Espera de Correcci??n');
define('ESTADO_TRANSACCION_CANCELADO','Cancelado');

define('COMISIONES_GIRADORES',0.02);

define('DB_PREFIX','gvzla_');

define('USUARIO_ADMINISTRADOR','Administrador');
define('USUARIO_SUPERVISOR','Supervisor');
define('USUARIO_GIRADOR','Girador');
define('USUARIO_TIENDA','Tienda');
define('USUARIO_DISTRIBUIDOR','Distribuidor');
define('USUARIO_SUBDISTRIBUIDOR','Sub-Distribuidor');

define('ESTADO_USUARIO_ENEABLE',1);
define('ESTADO_USUARIO_DISABLE',0);

define('ESTADO_CUENTA_ENEABLE',1);
define('ESTADO_CUENTA_DISABLE',0);

define('ESTADO_TRANFERENCIAS_EJECUTADA',1);
define('ESTADO_TRANFERENCIAS_DEVUELTA',2);

define('RECLAMO_ESTADO_INICIADO',1);
define('RECLAMO_ESTADO_RESUELTO',0);

define('HOY',date('Y-m-d'));
define('AYER',date('Y-m-d',strtotime('-1 day')));
define('ESTA_SEMANA',date('Y-m-d',strtotime('last Sunday')));
define('ESTE_MES',date('Y-m-01'));

define('GRUPO_TIENDAS_RECARGAS','Por Recarga');
define('GRUPO_TIENDAS_CALLVOIP','Giralo Express');
define('GRUPO_EMPRESA','Giralo Express');
define('GRUPO_CONSIGNACION','Giralo Consignacion'); 

define('ESTADO_MENSAJE_ACTIVO','Activo');
define('ESTADO_MENSAJE_INACTIVO','Inactivo');

define('PUBLICO_MENSAJES_CLIENTES','Clientes');
define('PUBLICO_MENSAJES_PERSONAL','Personal');
define('PUBLICO_MENSAJES_TODOS','Todos');

define('ESTADO_RECARGA_ESPERA','En Espera');
define('ESTADO_RECARGA_ACEPTADA','Aceptada');
define('ESTADO_RECARGA_RECHAZADA','Rechazada');