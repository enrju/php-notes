<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Model\NoteModel;
use App\Request;
use App\View;

abstract class AbstractController
{
    private array $configuration = [];
    protected NoteModel $noteModel;
    protected Request $request;
    protected View $view;

    public function __construct(array $configuration, Request $request)
    {
        if (empty($configuration['db'])) {
            throw new ConfigurationException('Problem z konfiguracją');
        }

        $this->configuration = $configuration;
        $this->request = $request;
        $this->noteModel = new NoteModel($configuration['db']);
        $this->view = new View();
    }

    public function run(): void
    {
        try {
            $httpMethod = $this->request->getHTTPMethod();
            $action = $this->request->getQueryStringParam('action', 'list');

            $methodName = $httpMethod . $action . 'Action';

            if (!method_exists($this, $methodName)) {
                $httpMethod = 'GET';
                $methodName = 'GETlistAction';
            }

            $viewParams = [];

            switch ($httpMethod) {
                case 'GET':
                    $viewParams = $this->$methodName();
                    break;
                case 'POST':
                    $this->$methodName();
                    break;
                default:
                    throw new AppException('Nieobsługiwana metoda HTTP');
            }

            $this->view->render($action, $viewParams);
        } catch (StorageException $e) {
            $this->view->render(
                'error',
                ['message', $e->getMessage()]
            );
        } catch (NotFoundException $e) {
            $this->redirect('/', ['error' => 'noteNotFound']);
        }
    }

    protected function getIdFromQueryString(): int
    {
        $id = (int) ($this->request->getQueryStringParam('id'));

        if (!$id) {
            header("Location: /?error=missingNoteId");
            exit();
        }

        return $id;
    }

    protected function getIdFromPost(): int
    {
        $id = (int)($this->request->getPostBodyParam('id'));

        if (!$id) {
            header("Location: /?error=missingNoteId");
            exit();
        }

        return $id;
    }

    final protected function redirect(string $to, array $params): void
    {
        $location = $to;

        if (count($params)) {
            $queryParams = [];

            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urlencode($value);
            }

            $queryParams = implode('&', $queryParams);

            $location .= '?' . $queryParams;
        }

        header("Location: $location");
        exit;
    }
}
