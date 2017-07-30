<?php

//ensure that the APP_PATH const is available in tests.
define('APP_PATH', realpath(dirname(__DIR__)));

require APP_PATH . '/vendor/autoload.php';