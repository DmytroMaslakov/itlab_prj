<?php

namespace App\Core;

use App\Core\Database\Database;

class StaticCore
{
    private static Database $database;
    private static FrontController $frontController;

    private function __construct()
    {
    }

    public static function GetDatabase(): Database
    {
        return self::$database;
    }

    public static function Init(): void
    {
        global $CoreParams;
        self::$database = new Database(
            $CoreParams['Database']['Host'],
            $CoreParams['Database']['Username'],
            $CoreParams['Database']['Password'],
            $CoreParams['Database']['Database']);
        self::$frontController = new FrontController();
    }

    public static function Run(): void
    {
        self::$database->connect();
        self::$frontController->run();
    }

    public static function Done(): void
    {

    }
}