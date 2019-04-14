<?php

class Database 
{
    protected $name;
    protected $user;
    protected $host;
    protected $pass;

    public function __construct()
    {
        $this->host = '127.0.0.1';
        $this->name = 'developers';
        $this->user = 'root';
        $this->pass = '';
    }

    public function connect()
    {
        try {
            $dbh = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pass);
            return $dbh;

        } catch(PDOException $e) {
            return [
                'error' => true,
                'data' => $e->getMessage()
            ];
        }
    }

}