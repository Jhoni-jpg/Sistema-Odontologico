<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class BDConnect
{

    public $pdo;
    private string $host;
    private string $port;
    private string $dbname;
    private string $password;
    private string $username;
    function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->username = $_ENV['DB_USERNAME'];
    }

    function establecerConexion(): \PDO
    {
        $this->pdo = new \PDO(
            "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}",
            $this->username,
            $this->password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
        $this->pdo->exec(statement: "SET client_encoding TO 'UTF8'");

        return $this->pdo;
    }

    function cerrarConexion()
    {
        return $this->pdo = null;
    }
}
