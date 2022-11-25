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

class NoteController
{
    private array $configuration = [];
    private NoteModel $noteModel;
    private Request $request;
    private View $view;

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
            $viewParams = [];

            switch ($httpMethod) {
                case 'GET':
                    switch ($action) {
                        case 'show':
                            $viewParams = $this->GETshowAction();
                            break;
                        case 'list':
                            $viewParams = $this->GETlistAction();
                            break;
                        case 'create':
                            $viewParams = $this->GETcreateAction();
                            break;
                        case 'edit':
                            $viewParams = $this->GETeditAction();
                            break;
                        case 'delete':
                            $viewParams = $this->GETdeleteAction();
                            break;
                        default:
                            $viewParams = $this->GETlistAction();
                            break;
                    }
                    break;
                case 'POST':
                    switch ($action) {
                        case 'create':
                            $this->POSTcreateAction();
                            break;
                        case 'edit':
                            $this->POSTeditAction();
                            break;
                        case 'delete':
                            $this->POSTdeleteAction();
                            break;
                        default:
                            $this->redirect('/', ['action' => 'list']);
                            break;
                    }
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

    private function getIdFromQueryString(): int
    {
        $id = (int) ($this->request->getQueryStringParam('id'));

        if (!$id) {
            header("Location: /?error=missingNoteId");
            exit();
        }

        return $id;
    }

    private function getIdFromPost(): int
    {
        $id = (int)($this->request->getPostBodyParam('id'));

        if (!$id) {
            header("Location: /?error=missingNoteId");
            exit();
        }

        return $id;
    }

    private function redirect(string $to, array $params): void
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

    private function GETshowAction(): array
    {
        $id = $this->getIdFromQueryString();

        $viewParams = [
            'note' => $this->noteModel->get($id)
        ];

        return $viewParams;
    }

    private function GETlistAction(): array
    {
        $viewParams = [
            'notes' => $this->noteModel->list(),
            'before' => $this->request->getQueryStringParam('before'),
            'id' => $this->request->getQueryStringParam('id'),
            'error' => $this->request->getQueryStringParam('error')
        ];

        return $viewParams;
    }

    private function GETcreateAction(): array
    {
        return [];
    }

    private function GETeditAction(): array
    {
        $id = $this->getIdFromQueryString();

        $viewParams = [
            'note' => $this->noteModel->get($id)
        ];

        return $viewParams;
    }

    private function GETdeleteAction(): array
    {
        $id = $this->getIdFromQueryString();

        $viewParams = [
            'note' => $this->noteModel->get($id)
        ];

        return $viewParams;
    }

    private function POSTcreateAction(): void
    {
        $insertedId = $this->noteModel->create([
            'title' => $this->request->getPostBodyParam('title'),
            'description' => $this->request->getPostBodyParam('description')
        ]);

        $this->redirect('/', [
            'before' => 'created',
            'id' => "$insertedId"
        ]);
    }

    private function POSTeditAction(): void
    {
        $editedId = $this->getIdFromPost();

        $this->noteModel->edit(
            $editedId,
            [
                'title' => $this->request->getPostBodyParam('title'),
                'description' => $this->request->getPostBodyParam('description')
            ]
        );

        $this->redirect('/', [
            'before' => 'edited',
            'id' => "$editedId"
        ]);
    }

    private function POSTdeleteAction(): void
    {
        $deletedId = $this->getIdFromPost();

        $this->noteModel->delete($deletedId);

        $this->redirect('/', [
            'before' => 'deleted',
            'id' => "$deletedId"
        ]);
    }
}
