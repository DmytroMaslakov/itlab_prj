<?php
global $CoreParams;
require_once("../config/config.php");
spl_autoload_register(function ($className) {
    $path = "../src/${className}.php";
    if (file_exists($path))
        require_once($path);
});
$database = new Database($CoreParams['Database']['Host'],
    $CoreParams['Database']['Username'],
    $CoreParams['Database']['Password'],
    $CoreParams['Database']['Database']);
echo "<pre>";

$database->connect();
$query = new QueryBuilder();
$query->delete()
    ->from('news')
    ->where(['id' => 30]);
$rows = $database->execute($query);
var_dump($rows);



/*$front_controller = new FrontController();
$front_controller->run();*/