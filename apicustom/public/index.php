<?php
global $CoreParams;

use App\Core\Core;
use App\Core\Database\ActiveRecord;
use App\Core\Database\Database;
use App\Core\FrontController;
use App\Core\StaticCore;
use App\Models\News;

require_once("../config/config.php");
spl_autoload_register(function ($className) {
    $newClassName = str_replace('\\', '/', $className);
    if(stripos($newClassName, 'App/') === 0)
        $newClassName = substr($newClassName, 4);
    $path = "../src/{$newClassName}.php";
    if (file_exists($path))
        require_once($path);
});


/*StaticCore::Init();
StaticCore::Run();
StaticCore::Done();*/

$core = Core::getInstance();
$core->init();
$core->run();
$core->done();


/*
$query = new QueryBuilder();
$query->select('name')
    ->from('news')
    ->leftJoin('user')
    ->on('user_id', 'id')
    ->where(['user_id' => 1]);
/*$query->insertInto('news')
    ->values(['title'=>'new', 'text'=>'newnewnew', 'date' => date('Y-m-d H:i:s'), 'user_id'=> 1]);*/


/*$rows = $database->execute($query);
var_dump($rows);*/


/*
$front_controller = new FrontController();
$front_controller->run();*/

$activeRecord = new News();
$activeRecord->title = "title";
$activeRecord->text = "text";
$activeRecord->date = "2023-08-11 19:39:00";
$activeRecord->user_id = 1;
$activeRecord->save();