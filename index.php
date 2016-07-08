<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

require 'system/autoload.php';

$uri_a = explode('?', $_SERVER["REQUEST_URI"]);
$uri = trim($uri_a[0], '/');

require 'app/bootstrap.php';

Router::$prefix = '';
Router::load('app/routes.json');
Router::dispatch($uri);
