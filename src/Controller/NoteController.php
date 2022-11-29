<?php

declare(strict_types=1);

namespace App\Controller;

class NoteController extends AbstractController
{
    private const PAGE_NUMBER = 1;
    private const PAGE_SIZE = 10;

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
        $phrase = $this->request->getQueryStringParam('phrase');
        $sortBy = $this->request->getQueryStringParam('sortby', 'title');
        $sortOrder = $this->request->getQueryStringParam('sortorder', 'asc');

        $pageNumber = (int) $this->request->getQueryStringParam('pagenumber', self::PAGE_NUMBER);
        $pageSize = (int) $this->request->getQueryStringParam('pagesize', self::PAGE_SIZE);

        if (!in_array($pageSize, [1, 5, 10, 25])) {
            $pageSize = self::PAGE_SIZE;
        }

        if ($phrase) {
            $notes = $this->noteModel->search(
                $phrase,
                $sortBy,
                $sortOrder,
                $pageNumber,
                $pageSize
            );

            $notesCount = $this->noteModel->searchCount($phrase);
        } else {
            $notes = $this->noteModel->list(
                $sortBy,
                $sortOrder,
                $pageNumber,
                $pageSize
            );

            $notesCount = $this->noteModel->count();
        }

        $viewParams = [
            'notes' => $notes,
            'phrase' => $phrase,
            'sort' => [
                'by' => $sortBy,
                'order' => $sortOrder,
            ],
            'page' => [
                'number' => $pageNumber,
                'size' => $pageSize,
                'pages' => (int) ceil($notesCount / $pageSize)
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
