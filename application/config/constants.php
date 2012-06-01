<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


if(strpos($_SERVER['SERVER_NAME'], 'local.') !== FALSE || strpos($_SERVER['SERVER_NAME'], '.local') !== FALSE || $_SERVER['SERVER_NAME'] == 'localhost')
{
	define('ENV', 'local');
}
else
{
  	define('ENV', 'server');
}

if(isset($_SERVER['HTTP_HOST']))
{
	$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
	$protocol = $base_url;
	$base_url .= '://'. $_SERVER['HTTP_HOST'];
	$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	
	$base_url_https = 'https://'. $_SERVER['HTTP_HOST'];
	$base_url_https .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	
	$base_uri = parse_url($base_url, PHP_URL_PATH);
	if(substr($base_uri, 0, 1) != '/') $base_uri = '/'.$base_uri;
	if(substr($base_uri, -1, 1) != '/') $base_uri .= '/';
}
else
{
	$base_url = 'http://localhost/';
	$base_uri = '/';
}

define('BASE_URL', $base_url);
define('BASE_URI', $base_uri);
define('SALT', 'hskkj109AKa91cOpq5821casAAdf1d9');
define("EXAMS_FOLDER","files/exams/");
unset($base_uri, $base_url);

define("MAX_RADIUS", 750);
define("START_RADIUS", 10);
define("RADIUS_STEP", 50);
define("RELEASE_KEY","65611159891");
/* End of file constants.php */
/* Location: ./application/config/constants.php */