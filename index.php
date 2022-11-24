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
    $viewParams = [];

    switch ($httpMethod) {
        case 'GET':
            switch ($action) {
                case 'show':
                    $id = (int) ($request->getQueryStringParam('id'));

                    if ($id) {
                        $viewParams = [
                            'note' => $noteModel->get($id)
                        ];
                    }
                    break;
                case 'list':
                    $viewParams = [
                        'notes' => $noteModel->list(),
                        'before' => $request->getQueryStringParam('before'),
                        'id' => $request->getQueryStringParam('id')
                    ];
                    break;
                case 'create':
                    $viewParams = [];
                    break;
                case 'edit':
                    $id = (int) ($request->getQueryStringParam('id'));

                    $viewParams = [
                        'note' => $noteModel->get($id)
                    ];
                    break;
                default:
                    break;
            }
            break;
        case 'POST':
            switch ($action) {
                case 'create':
                    $insertedId = $noteModel->create([
                        'title' => $request->getPostBodyParam('title'),
                        'description' => $request->getPostBodyParam('description')
                    ]);

                    header("Location: /?before=created&id=$insertedId");
                    exit();

                    break;
            }
            break;
        default:
            throw new AppException('Nieobsługiwana metoda HTTP');
    }

    $view->render($action, $viewParams);



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
