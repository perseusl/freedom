<?php


$autoloadPath = __DIR__;
if(strrpos($autoloadPath, '/')) {
	$autoloadPath = explode('/', $autoloadPath);
	array_pop($autoloadPath);
	$autoloadPath = implode('/', $autoloadPath);
} else {
	$autoloadPath = explode('\\', $autoloadPath);
	array_pop($autoloadPath);
	$autoloadPath = implode('\\', $autoloadPath);
}

echo "============================";
var_dump($autoloadPath);

require_once __DIR__ . '/BaseTest.php';
require_once $autoloadPath . '/vendor/autoload.php';
//set a valid access token here . . .
//$valid
/*global $myToken;
echo var_dump($_GET['accessToken']);
$_GET['accessToken'] = $myToken;*/
