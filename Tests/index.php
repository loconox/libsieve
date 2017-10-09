<?php

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Sieve\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/../lib/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

include_once '../contrib/managesieve.lib.php';

define('MANAGESIEVE_HOST', 'localhost');
define('MANAGESIEVE_USER', 'heiko');
define('MANAGESIEVE_PASS', 'heiko');

print '<table cellpadding="4" style="font-family:sans-serif; border-collapse: collapse">';
print '<tr align="center" style="font-weight:bold;background-color:lightgrey">';
print '  <td style="padding-left:10px">Test</td><td>Expected</td><td>Sieved</td><td style="padding-right:10px">Parser</td>';
print '</tr>';

foreach (array('good', 'bad') as $dir)
{
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false)
	{
		if (preg_match('/(.+)\.siv$/', $file, $match)) {
			$script = file_get_contents("$dir/$file");

			$sieved = new Sieve(MANAGESIEVE_HOST, 4190, MANAGESIEVE_USER, MANAGESIEVE_PASS);
			$sieved->sieve_login();
			if ($sieved->sieve_sendscript("test.siv", $script)) {
				$sieved_bgcolor = $dir == 'good' ? 'lightgreen' : 'tomato';
				$sieved_status = 'good';
				$sieved_error = '';
			}
			else {
				$sieved_bgcolor = $dir == 'bad' ? 'lightgreen' : 'tomato';
				$sieved_status = 'bad';
				$sieved_error = ' title="'. htmlentities($sieved->error_raw[0] .' '. $sieved->error_raw[1]) .'"';
			}

			try {
				$parser = new \Sieve\Parser();
				$parser->parse($script);

				$parser_bgcolor = $dir == 'good' ? 'lightgreen' : 'tomato';
				$parser_status = 'good';
				$parser_error = '';
			}
			catch (Exception $e) {
				$parser_bgcolor = $dir == 'bad' ? 'lightgreen' : 'tomato';
				$parser_status = 'bad';
				$parser_error = ' title="'. htmlentities($e->getMessage()) .'"';
			}

			print '<tr align="center" style="border-style:solid; border-left-style:none; border-right-style:none; border-width:1px">';
			print   '<td align="left" style="padding-left:10px; font-weight:bold">'. $match[1] .'</td><td>'. $dir .'</td>';
			print   '<td><span style="padding:2px; background-color:'. $sieved_bgcolor .'"'. $sieved_error .'>&nbsp;'. $sieved_status .'&nbsp;</span></td>';
			print   '<td style="padding-right:10px"><span style="padding:2px; background-color:'. $parser_bgcolor .'"'. $parser_error .'>&nbsp;'. $parser_status .'&nbsp;</span></td>';
			print '</tr>';
		}
	}
}

print '</table>';

closedir($dh);

?>