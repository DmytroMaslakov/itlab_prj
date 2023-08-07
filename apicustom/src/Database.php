<?php

class Database
{
    protected $host;
    protected $password;
    protected $username;
    protected $dbname;

    protected PDO $pdo;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function getConnectionString()
    {
        return "mysql:host={$this->host}; dbname={$this->dbname}";
    }

    public function connect()
    {
        $this->pdo = new PDO($this->getConnectionString(), $this->username, $this->password);
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function execute(QueryBuilder $builder)
    {
        $sth = $this->pdo->prepare($builder->getSql());
        $params = $builder->getParams();
        foreach ($params as $key => $value) {
            $sth->bindValue($key, $value);
        }
        $sth->execute();
        return $sth->fetchAll();
    }

}