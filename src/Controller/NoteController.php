<?php

declare(strict_types=1);

namespace App\Controller;

class NoteController extends AbstractController
{
    protected function GETshowAction(): array
    {
        $id = $this->getIdFromQueryString();

        $viewParams = [
            'note' => $this->noteModel->get($id)
        ];

        return $viewParams;
    }

    protected function GETlistAction(): array
    {
        $sortBy = $this->request->getQueryStringParam('sortby', 'title');
        $sortOrder = $this->request->getQueryStringParam('sortorder', 'asc');

        $notes = $this->noteModel->list($sortBy, $sortOrder);

        $viewParams = [
            'notes' => $notes,
            'sort' => [
                'by' => $sortBy,
                'order' => $sortOrder,
            ],
            'before' => $this->request->getQueryStringParam('before'),
            'id' => $this->request->getQueryStringParam('id'),
            'error' => $this->request->getQueryStringParam('error')
        ];

        return $viewParams;
    }

    protected function GETcreateAction(): array
    {
        return [];
    }

    protected function GETeditAction(): array
    {
        $id = $this->getIdFromQueryString();

        $viewParams = [
            'note' => $this->noteModel->get($id)
        ];

        return $viewParams;
    }

    protected function GETdeleteAction(): array
    {
        $id = $this->getIdFromQueryString();

        $viewParams = [
            'note' => $this->noteModel->get($id)
        ];

        return $viewParams;
    }

    protected function POSTcreateAction(): void
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

    protected function POSTeditAction(): void
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

    protected function POSTdeleteAction(): void
    {
        $deletedId = $this->getIdFromPost();

        $this->noteModel->delete($deletedId);

        $this->redirect('/', [
            'before' => 'deleted',
            'id' => "$deletedId"
        ]);
    }
}
