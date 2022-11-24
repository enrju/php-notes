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
$configuration = require_once("config/config.php");

use App\Exception\AppException;
use App\Model\NoteModel;
use App\Request;
use App\View;

try {
    $noteModel = new NoteModel($configuration['db']);
    $request = new Request($_GET, $_POST, $_SERVER);
    $view = new View();

    $httpMethod = $request->getHTTPMethod();
    $action = $request->getQueryStringParam('action', 'list');
    $id = (int) ($request->getQueryStringParam('id'));

    switch ($httpMethod) {
        case 'GET':
            switch ($action) {
                case 'show':
                    if ($id) {
                        $viewParams = [
                            'note' => $noteModel->get($id)
                        ];
                    }
                    break;
                case 'list':
                    $viewParams = [
                        'notes' => $noteModel->list()
                    ];
                    break;
                case 'create':
                    $viewParams = [];
                    break;
                default:
                    break;
            }
            break;
        case 'POST':
            break;
        default:
            throw new AppException('Nieobsługiwana metoda HTTP');
    }

    $view->render($action, $viewParams);

    // $insertedId = $noteModel->create([
    //     'title' => '--- testowy ---',
    //     'description' => '--- tester ---'
    // ]);
    // dump($insertedId);

    // dump($noteModel->get($insertedId));
    // $noteModel->edit(
    //     $insertedId,
    //     [
    //         'title' => '--- testowy --- edited',
    //         'description' => '--- tester --- edited'
    //     ]
    // );
    // dump($noteModel->get($insertedId));

    // $noteModel->delete($insertedId);

    // throw new Throwable('test wyjątku Throwable');
    // throw new AppException('test wyjątku AppException');
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
}
