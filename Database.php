<?php

namespace App;

use \PDO;

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
        );
        try {
            $this->conn = new PDO(
                'mysql:host='.HOST.';dbname='.NAME.';charset=utf8', USER, PASSWORD, $options
            );
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}