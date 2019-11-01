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

######################APP CONST###############################
defined('APPNAME') OR define('APPNAME', 'pc');

defined('STATIC_URL') OR define('STATIC_URL', 'http://static.ipensoft.com/default/');
defined('STATIC_VER') OR define('STATIC_VER', '0.01');


defined('SITE_BASIC') OR define('SITE_BASIC', 'http://www.ipensoft.com');
defined('SITE_NOVEL') OR define('SITE_NOVEL', 'http://novel.ipensoft.com');
defined('SITE_MUSIC') OR define('SITE_MUSIC', 'http://music.ipensoft.com');
defined('SITE_COMIC') OR define('SITE_COMIC', 'http://comic.ipensoft.com');
defined('SITE_USERS') OR define('SITE_USERS', 'http://users.ipensoft.com');
defined('SITE_ANIME') OR define('SITE_ANIME', 'http://anime.ipensoft.com');



##################################CACHE CONST########################################
define('CACHE_SWITCH', TRUE);
define('REMOVE_CACHE_DATA', TRUE); # remove cache data
define('CACHE_OUT_TIME_ONE_WEEKS', 3600 * 24 * 7); # 1 周
define('CACHE_OUT_TIME_ONE_DAY', 3600 * 24); # 24 小时
define('CACHE_OUT_TIME_ONE_HOUR', 3600); # 一小时
define('CACHE_OUT_TIME_THIRTY_MINU', 1800); # 30 分钟
define('CACHE_OUT_TIME_ONE_MINU', 60); # 60 秒
define('CACHE_OUT_TIME_THIRTY_SECO', 30); # 30 秒

###################################app error code message ##########################################
defined('NO_LOGIN_MSG') OR define('NO_LOGIN_MSG', '账号未登录');
defined('NO_LOGIN_CODE') OR define('NO_LOGIN_CODE', 1001);
defined('PLAIN_ERROR_CODE') OR define('PLAIN_ERROR_CODE', 0);


####################################Users Login const ##############################################
defined('LOGIN_OUT_TIME') OR define('LOGIN_OUT_TIME', 7);
defined('USERS_SIGNAL_USERS_ID_KEY') OR define('USERS_SIGNAL_USERS_ID_KEY', 'users:signal:%s');
defined('USERS_USERS_ID_SIGNAL_KEY') OR define('USERS_USERS_ID_SIGNAL_KEY', 'users:users_id:%s:signal');

####################################Comic Const#####################################################
defined('DELAY_NUM') OR define('DELAY_NUM', 666); // 更新入库延迟次数


######################################Public const##################################################
defined('NORMAL') OR define('NORMAL', 1);
defined('DELETED') OR define('DELETED', 2);
defined('NOT_AUDIT') OR define('NOT_AUDIT', 3);
