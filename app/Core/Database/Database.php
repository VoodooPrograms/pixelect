<?php

namespace Quetzal\Core\Database;

use Quetzal\Core\AppException;
use Quetzal\Core\Register;

class Database
{
    private $reg;

    private array $database_config;

    public function __construct() {
        $this->reg = Register::instance();
        $this->database_config = $this->reg->getSettingsManager()['database']['database'];
    }

    public function connect(?string $username = null, ?string $password = null, ?string $host = null, ?string $database = null) {
        $username = $username ?? $this->database_config['username'];
        $password = $password ?? $this->database_config['password'];
        $host = $host ?? $this->database_config['host'];
        $database = $database ?? $this->database_config['database'];
        $dsn = "pgsql:host=$host;port=5432;dbname=$database";
        try {
            $connection = new \PDO(
                $dsn,
                $username,
                $password,
                ['sslmode' => 'prefer']
            );

            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $connection;

        } catch (\PDOException $e) {
            throw new AppException("Connection failed: ".$e->getMessage());
        }
    }
}