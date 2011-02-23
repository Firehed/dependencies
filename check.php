#!/usr/bin/php
<?php
$errors = 0;
function show($ext, $pass = true) {
	global $errors;
	if ($pass !== true) $errors++;
	$fail = "[\033[0;31mFAILED\033[0m]";
	$skip = "[\033[0;33m SKIP \033[0m]";
	$ok   = "[\033[0;32m  OK  \033[0m]";
	if ($pass === true) $msg = $ok;
	elseif ($pass === false) $msg = $fail;
	else $msg = $skip;
	printf("%-30s%s\n", $ext, $msg);
}
function label($text) {
	echo "\n-=[ $text ]=-\n";
}

echo "Testing extension reqirements...\n";

label('GD');
	show('ImageCreateFromGif', function_exists('imagecreatefromgif'));
	show('ImageCreateFromJpeg',function_exists('imagecreatefromjpeg'));
	show('ImageCreateFromPng', function_exists('imagecreatefrompng'));
	show('ImageJpeg',          function_exists('imagejpeg'));

label('OAuth');
	show('OAuth',         class_exists('OAuth'));
	show('OAuthProvider', class_exists('oauthprovider'));

label('Date/Time');
	show('DateTime',      class_exists('DateTime'));
	show('DateTimeZone',  class_exists('DateTimeZone'));
	show('DatePeriod',    class_exists('DatePeriod'));
	show('DateInterval',  class_exists('DateInterval'));

label('Security');
	show('MCrypt',        defined('MCRYPT_ENCRYPT'));

label('Storage');
	show('MySQLi',        class_exists('mysqli'));
	show('Memcache',      class_exists('Memcache'));

label('XML/DOM');
	show('SimpleXML',     class_exists('SimpleXMLElement'));
	show('DOMDocument',   class_exists('domdocument'));

label('PHP Core');
	show('Reflection',    class_exists('reflectionclass'));
	show('Curl',          function_exists('curl_init'));
	show('APC',           function_exists('apc_cache_info'));
	show('POSIX',         function_exists('posix_kill'));
	show('PCNTL',         function_exists('pcntl_fork'));

label('Text/Unicode');
	show('PCRE UTF8 Support',  !!@preg_match('/^\pL$/u', 'ñ'));
	show('iconv extension',    extension_loaded('iconv'));
	show('mbstring extension', extension_loaded('mbstring'));
	 if (extension_loaded('mbstring'))
	 	show('mbstring overload is disabled', !(ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING));
	 else
		show('mbstring overload is disabled', 'skip');
	
label('Output');
	show('GZCompress', function_exists('gzcompress'));


echo str_repeat('=', 38), "\n";
show('Overall', !$errors);
exit($errors ? 1 : 0);

