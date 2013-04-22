<?php

define('ROOT_PATH', dirname(__DIR__));
define('PAGES_PATH', ROOT_PATH . '/pages');
define('CACHE_PATH', ROOT_PATH . '/cache');

include ROOT_PATH . '/libs/UserSwifki.php';

$swifki = new UserSwifki();
$swifki->render();