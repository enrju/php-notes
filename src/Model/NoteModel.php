<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Model\AbstractModel;
use App\Model\ModelInterface;

use PDO;
use Throwable;

class NoteModel extends AbstractModel implements ModelInterface
{
    public function get(int $id): array
    {
        try {
            $query = "SELECT * FROM notes WHERE id = $id";

            $result = $this->conn->query($query);

            $note = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }

        if (!$note) {
            throw new NotFoundException("Notatka o id = $id nie istanieje");
        }

        return $note;
    }

    public function list(
        string $sortBy,
        string $sortOrder,
        int $pageNumber,
        int $pageSize
    ): array {
        return $this->findBy(
            null,
            $sortBy,
            $sortOrder,
            $pageNumber,
            $pageSize
        );
    }

    public function search(
        string $phrase,
        string $sortBy,
        string $sortOrder,
        int $pageNumber,
        int $pageSize
    ): array {
        return $this->findBy(
            $phrase,
            $sortBy,
            $sortOrder,
            $pageNumber,
            $pageSize
        );
    }

    private function findBy(
        ?string $phrase,
        string $sortBy,
        string $sortOrder,
        int $pageNumber,
        int $pageSize
    ): array {
        try {
            if (!in_array($sortBy, ['created', 'title'])) {
                $sortBy = 'title';
            }

            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortBy = 'desc';
            }

            $offset = ($pageNumber - 1) * $pageSize;
            $limit = $pageSize;

            $sqlWherePart = '';
            if ($phrase) {
                $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
                $sqlWherePart = "WHERE title LIKE ($phrase)";
            }

            $query = "
            SELECT id, title, created
            FROM notes
            $sqlWherePart
            ORDER BY $sortBy $sortOrder
            LIMIT $offset, $limit
            ";

            $result = $this->conn->query($query);

            $notes = $result->fetchAll(PDO::FETCH_ASSOC);

            return $notes;
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać notatek', 400, $e);
        }
    }

    public function create(array $data): int
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "
            INSERT INTO notes(title, description, created)
            VALUES($title, $description, $created)
            ";

            $this->conn->exec($query);

            return (int) $this->conn->lastInsertId();
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowej notatki', 400, $e);
        }
    }

    public function edit(int $id, array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "
            UPDATE notes
            SET title = $title, description = $description
            WHERE id = $id
            ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się zmodyfikować notatki', 400, $e);
        }
    }

    public function delete(int $id): void
    {
        try {
            $query = "DELETE FROM notes WHERE id = $id LIMIT 1";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się skasować notatki', 400, $e);
        }
    }

    public function count(): int
    {
        try {
            $query = "SELECT count(*) AS count FROM notes";

            $result = $this->conn->query($query, PDO::FETCH_ASSOC);

            $result = $result->fetch();

            if ($result) {
                return (int) $result['count'];
            }

            return 0;
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać liczby wszystkich notatek', 400, $e);
            exit();
        }
    }

    public function searchCount(string $phrase): int
    {
        try {
            $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);

            $query = "
            SELECT count(*) AS count 
            FROM notes
            WHERE title LIKE ($phrase)
            ";

            $result = $this->conn->query($query, PDO::FETCH_ASSOC);

            $result = $result->fetch();

            if ($result) {
                return (int) $result['count'];
            }

            return 0;
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać liczby wszystkich notatek', 400, $e);
            exit();
        }
    }
}
