<?php


ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/dev.php';
require_once __DIR__.'/../src/ItemNode.php';
require_once __DIR__.'/../src/HackerNewsClient.php';
require_once __DIR__.'/../src/ItemMapper.php';

require __DIR__.'/../src/controllers.php';

// To make the buil-in PHP server serve static files
$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}


$app->run();
