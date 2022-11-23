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
}
