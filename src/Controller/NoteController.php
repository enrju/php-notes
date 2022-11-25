<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AppException;
use App\Exception\ConfigurationException;
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
        $httpMethod = $this->request->getHTTPMethod();
        $action = $this->request->getQueryStringParam('action', 'list');
        $viewParams = [];

        switch ($httpMethod) {
            case 'GET':
                switch ($action) {
                    case 'show':
                        $id = $this->getIdFromQueryString();

                        $viewParams = [
                            'note' => $this->noteModel->get($id)
                        ];

                        break;
                    case 'list':
                        $viewParams = [
                            'notes' => $this->noteModel->list(),
                            'before' => $this->request->getQueryStringParam('before'),
                            'id' => $this->request->getQueryStringParam('id'),
                            'error' => $this->request->getQueryStringParam('error')
                        ];
                        break;
                    case 'create':
                        $viewParams = [];
                        break;
                    case 'edit':
                        $id = $this->getIdFromQueryString();

                        $viewParams = [
                            'note' => $this->noteModel->get($id)
                        ];
                        break;
                    case 'delete':
                        $id = $this->getIdFromQueryString();

                        $viewParams = [
                            'note' => $this->noteModel->get($id)
                        ];
                        break;
                    default:
                        break;
                }
                break;
            case 'POST':
                switch ($action) {
                    case 'create':
                        $insertedId = $this->noteModel->create([
                            'title' => $this->request->getPostBodyParam('title'),
                            'description' => $this->request->getPostBodyParam('description')
                        ]);

                        header("Location: /?before=created&id=$insertedId");
                        exit();

                        break;
                    case 'edit':
                        $editedId = $this->getIdFromPost();

                        $this->noteModel->edit(
                            $editedId,
                            [
                                'title' => $this->request->getPostBodyParam('title'),
                                'description' => $this->request->getPostBodyParam('description')
                            ]
                        );

                        header("Location: /?before=edited&id=$editedId");
                        exit();

                        break;
                    case 'delete':
                        $deletedId = $this->getIdFromPost();

                        $this->noteModel->delete($deletedId);

                        header("Location: /?before=deleted&id=$deletedId");
                        exit();

                        break;
                }
                break;
            default:
                throw new AppException('Nieobsługiwana metoda HTTP');
        }

        $this->view->render($action, $viewParams);
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
}
