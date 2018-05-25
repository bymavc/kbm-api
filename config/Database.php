<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/14/2018
 * Time: 10:34 PM
 */

class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'kbmdb';
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try
        {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->user, $this->pass);
            $this->conn->exec('set names utf8');
            $this->conn->lastInsertId();
        }
        catch (PDOException $e)
        {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}