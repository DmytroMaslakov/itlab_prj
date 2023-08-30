<?php

namespace App\Core\Database;

use App\Core\Core;
use App\Core\StaticCore;
use Symfony\Component\VarDumper\Cloner\Data;

class ActiveRecord
{
    protected array $fields = [];
    protected string $table;

    public function __construct()
    {
    }

    public function __set(string $name, $value): void
    {
        $this->fields[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->fields[$name];
    }

    public function __call(string $name, array $arguments)
    {
        switch ($name) {
            case 'save' :
                $builder = new QueryBuilder();
                if (!empty($arguments[0]))
                    $this->table = $arguments[0];
                if (!empty($this->table)) {
                    $builder->insertInto($this->table)->values($this->fields);
                    Core::getInstance()->GetDatabase()->execute($builder);
                } else {
                    //Error
                }
                break;
        }
    }
}