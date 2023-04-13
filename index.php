<?php

use src\App;
use src\Router;

require __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/src/config.php';

require __DIR__ . '/src/App.php';
require __DIR__ . '/src/Router.php';
$app = new App();
$app->setConfig($config);

echo $app->run();
