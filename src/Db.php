<?php

namespace App;

/**
 * Creates new DB connection
 * 
 * @since 1.0.0
 * @package Api
 */
class Db {

    /**
     * Holds Doctrine DB Object
     *
     * @var object
     * @since 1.0.0
     */
    public $conn;

    public function __construct() {
        $this->connect();
    }

    /**
     * Gets and sets new Doctrine Connection
     *
     * @return void
     * @since 1.0.0
     */
    public function connect() {
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'url' => $_ENV['DATABASE_URL'],
        );

        $this->conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }
}