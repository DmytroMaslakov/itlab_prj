<?php

namespace App\Core;

use App\Core\Database\Database;

class Core
{
    private Database $database;
    private FrontController $frontController;

    private static self $instance;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function GetDatabase(): Database
    {
        return $this->database;
    }

    public function init(): void
    {
        global $CoreParams;
        $this->database = new Database(
            $CoreParams['Database']['Host'],
            $CoreParams['Database']['Username'],
            $CoreParams['Database']['Password'],
            $CoreParams['Database']['Database']);
        $this->frontController = new FrontController();
    }

    public function run(): void
    {
        $this->database->connect();
        $this->frontController->run();
    }

    public function done(): void
    {

    }
}