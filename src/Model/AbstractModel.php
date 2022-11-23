<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

abstract class AbstractModel
{
    protected PDO $conn;

    public function __construct(array $config)
    {
        $this->createConnection($config);
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

        dump($this->conn);
    }
}
