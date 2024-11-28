<?php

namespace src\core;

use Exception;
use mysqli;

class DBConnection
{
    private static $dbConnection = [];
    private $host = null;

    private function __construct() {
        $this->host = include CONFIG_PATH . '/database.php';
    }

    public static function getConnection($dbType) {
        if(empty(self::$dbConnection[$dbType])) {
            $instance = new self();
            self::$dbConnection[$dbType] = $instance->connect($dbType);
        }

        return self::$dbConnection[$dbType];
    }

    private function connect($dbType) {
        try {
            $host = $this->host[$dbType]['host'];
            $user = $this->host[$dbType]['user'];
            $password = $this->host[$dbType]['password'];
            $database = $this->host[$dbType]['database'];
            $port = $this->host[$dbType]['port'];

            return new mysqli($host, $user, $password, $database, $port);
        } catch (Exception $e) {
            throw new Exception('DB connection failed');
        }
    }
}