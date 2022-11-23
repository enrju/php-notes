<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\StorageException;
use PDO;
use PDOException;

abstract class AbstractModel
{
    protected PDO $conn;

    public function __construct(array $config)
    {
        try {
            $this->createConnection($config);
        } catch (PDOException $e) {
            throw new StorageException('Błąd połaczenia z DB');
        }
    }

    private function createConnection(array $config): void
    {
        $dns = $dns = "mysql:host={$config['host']};dbname={$config['database']}";

        $this->conn = new PDO(
            $dns,
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
