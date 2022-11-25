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

use App\Exception\ConfigurationException;
use App\Controller\NoteController;
use App\Exception\AppException;
use App\Request;

$configuration = require_once("config/config.php");
$request = new Request($_GET, $_POST, $_SERVER);

try {
    $noteController = new NoteController($configuration, $request);
    $noteController->run();
} catch (ConfigurationException $e) {
    echo "<h1>wystapił błąd w aplikacji</h1>";
    echo 'Problem z konfiguracją - proszę skontaktować się z administratorem xxx@xxx.com';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
}
