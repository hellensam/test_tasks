<?php
namespace Api\Models;

use PDO;
use PDOException;

class Database
{

    /**
     * @var string
     */
    private string $host = 'localhost';

    /**
     * @var string
     */
    private string $db_name = 'test_db';

    /**
     * @var string
     */
    private string $username = 'root';

    /**
     * @var string
     */
    private string $password = '';

    /**
     * @var PDO|null
     */
    public ?PDO $connection = null;

    /**
     * @return PDO
     * @throws PDOException
     */
    public function connect(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username,
                    $this->password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Database Connection Error: ' . $e->getMessage());
            }
        }

        return $this->connection;
    }
}
