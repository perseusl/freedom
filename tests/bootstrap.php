<?php


$autoloadPath = __DIR__;
var_dump($autoloadPath);
$autoloadPath = explode('\\', $autoloadPath);
array_pop($autoloadPath);
$autoloadPath = join('\\', $autoloadPath);

require_once $autoloadPath . '/vendor/autoload.php';
require_once __DIR__ . '/BaseTest.php';
//set a valid access token here . . .
//$valid
/*global $myToken;
echo var_dump($_GET['accessToken']);
$_GET['accessToken'] = $myToken;*/
