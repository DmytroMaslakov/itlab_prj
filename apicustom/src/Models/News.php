<?php

namespace App\Models;

use App\Core\Database\ActiveRecord;
/**
 * @property int $id id of news
 * @property string $title Title of news
 * @property string $text Text of news *
 * @property string $date Date of news
 * @property int $user_id User id of news */
class News extends ActiveRecord
{

    public function __construct()
    {
        $this->table = 'news';
        parent::__construct();
    }
}