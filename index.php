<?php

if (version_compare(phpversion(), '5.2.0', '<')===true) {
    echo  '<div style="font:12px/1.35em arial, helvetica, sans-serif;"><div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;"><h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">Whoops, it looks like you have an invalid PHP version.</h3></div><p>Zend Framework supports PHP 5.2.0 or newer. Zend Framework using PHP-CGI as a work-around.</p></div>';
    exit;
}

$filename = 'public/index.php';
$url = 'http://localhost/pwd/public';
   header('Location: '.$url);

if (file_exists($filename)) {
    require_once $filename;
} else {
	include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}
