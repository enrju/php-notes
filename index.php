<?php

declare(strict_types=1);

// //switch off error raporting in production version
// error_reporting(0);
// ini_set('display_errors', '0');

spl_autoload_register(function (string $classNamespace) {
    $path = str_replace(['\\', 'App/'], ['/'], $classNamespace);
    $path = 'src/' . $path . '.php';

    require_once($path);
});

require_once("src/Utils/debug.php");

dump("Hello PHP");
dump([1, 2, 3]);
