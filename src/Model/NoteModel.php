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

    public function list(): array
    {
        try {
            $query = "
            SELECT id, title, created
            FROM notes
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
            $title = $data['title'];
            $description = $data['description'];
            $created = date('Y-m-d H:i:s');

            $query = "
            INSERT INTO notes(title, description, created)
            VALUES('$title', '$description', '$created')
            ";

            $this->conn->exec($query);

            return (int) $this->conn->lastInsertId();
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowej notatki', 400, $e);
        }
    }

    public function edit(int $id, array $data): void
    {
        $title = $data['title'];
        $description = $data['description'];

        $query = "
        UPDATE notes
        SET title = '$title', description = '$description'
        WHERE id = $id
        ";

        $this->conn->exec($query);
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM notes WHERE id = $id LIMIT 1";

        $this->conn->exec($query);
    }
}
