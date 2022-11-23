<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\AbstractModel;
use App\Model\ModelInterface;

use PDO;

class NoteModel extends AbstractModel implements ModelInterface
{
    public function get(int $id): array
    {
        $query = "SELECT * FROM notes WHERE id = $id";

        $result = $this->conn->query($query);

        $note = $result->fetch(PDO::FETCH_ASSOC);

        return $note;
    }

    public function list(): array
    {
        $query = "
        SELECT id, title, created
        FROM notes
        ";

        $result = $this->conn->query($query);

        $notes = $result->fetchAll(PDO::FETCH_ASSOC);

        return $notes;
    }

    public function create(array $data): int
    {
        $title = $data['title'];
        $description = $data['description'];
        $created = date('Y-m-d H:i:s');

        $query = "
        INSERT INTO notes(title, description, created)
        VALUES('$title', '$description', '$created')
        ";

        $this->conn->exec($query);

        return (int) $this->conn->lastInsertId();
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
}
