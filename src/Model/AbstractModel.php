<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;

abstract class AbstractModel
{
    protected PDO $conn;

    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch (PDOException $e) {
            throw new StorageException('Błąd połączenia z DB');
        }
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['host'])
            || empty($config['database'])
            || empty($config['user'])
            // || empty($config['password'])   //empty string for localhost
        ) {
            throw new ConfigurationException('Błąd konfiguracja DB');
        }
    }

    private function createConnection(array $config): void
    {
        $dns = "mysql:host={$config['host']};dbname={$config['database']}";

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
