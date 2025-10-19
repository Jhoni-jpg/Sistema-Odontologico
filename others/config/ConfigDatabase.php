<?php

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

        $this->host = getenv('DB_HOST');
        $this->dbname = getenv('DB_NAME');
        $this->password = getenv('DB_PASSWORD');
        $this->username = getenv('DB_USER');
    }

    function establecerConexion(): \PDO
    {
        $this->pdo = new \PDO(
            "pgsql:host={$this->host};dbname={$this->dbname}",
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
