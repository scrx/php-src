--TEST--
mysqli_debug() - enabling trace with ini setting
--SKIPIF--
<?php
require_once('skipif.inc');
require_once('skipifemb.inc');

if (!function_exists('mysqli_debug'))
 	die("skip mysqli_debug() not available");

if (!defined('MYSQLI_DEGBUG_TRACE_ENABLED'))
	die("skip: can't say for sure if mysqli_debug works");

if (defined('MYSQLI_DEBUG_TRACE_ENABLED') && !MYSQLI_DEBUG_TRACE_ENABLED)
	die("skip: debug functionality not enabled");

require_once('connect.inc');
if (!$IS_MYSQLND || ($MYSQLND_VERSION < 940))
	die("skip needs mysqlnd version/revision 940+");

if (!$fp = @fopen('/tmp/mysqli_debug_phpt.trace', 'w'))
	die("skip PHP cannot create a file in /tmp/mysqli_debug_phpt");
else
	fclose($fp);
?>
--INI--
mysqlnd.debug="t:O,/tmp/mysqli_debug_phpt.trace"
--FILE--
<?php
	require_once('connect.inc');
	require_once('table.inc');

	var_dump(ini_get('mysqlnd.debug'));

	$trace_file = '/tmp/mysqli_debug_phpt.trace';
	clearstatcache();
	if (!file_exists($trace_file))
		printf("[003] Trace file '%s' has not been created\n", $trace_file);
	if (filesize($trace_file) < 50)
		printf("[004] Trace file '%s' is very small. filesize() reports only %d bytes. Please check.\n",
			$trace_file,
			filesize($trace_file));

	mysqli_close($link);
	unlink($trace_file);

	print "done!";
?>
--EXPECTF--
string(32) "t:O,/tmp/mysqli_debug_phpt.trace"
done!
--UEXPECTF--
unicode(32) "t:O,/tmp/mysqli_debug_phpt.trace"
done!