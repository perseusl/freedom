<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../autoload.php';

//echo Freedom_Hello::world();
//this line of codes were used for sample testing
$client = new Freedom_Client;
$client->setAccessToken('375ec2d0104e4adc010e9a6419676736de1a6a7836f6adeefb54fbf01e790988');
$userService = new Freedom_Service_User($client);
echo var_dump($userService->getUser());
//======remove testing lines when done======//