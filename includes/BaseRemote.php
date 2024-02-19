<?php

class BaseRemote
{
    private $host = 'seu_host';
    private $user = 'seu_usuario';
    private $password = 'sua_senha';
    private $dbname = 'seu_banco';
    private $conn;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->conn = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function insert($query, $params)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $this->conn->lastInsertId();
    }

    public function select($query, $params = array())
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($query, $params)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}
