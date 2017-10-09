<?php

error_reporting (E_ALL | E_STRICT);

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'LibSieve\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/../src/';

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

$filename = 'script.siv';
$fd = fopen($filename, 'r');
$script = fread($fd, filesize($filename));
fclose($fd);

$text_color = 'green';
$text = 'success';

try
{
	$parser = new \LibSieve\Parser();
	$parser->parse($script);
}
catch (Exception $e)
{
	$text_color = 'tomato';
	$text = $e->getMessage();
	//print "<pre>". $e->getTraceAsString() ."</pre>";
}

print "<small><pre>$script</pre><hr>";
print '<pre style="color:'. $text_color .';font-weight:bold">' . $text .'</pre><hr><pre>';
print htmlentities($parser->dumpParseTree());
print '</pre><hr><pre>';
print $parser->getScriptText();
print '</pre></small>';

?>
