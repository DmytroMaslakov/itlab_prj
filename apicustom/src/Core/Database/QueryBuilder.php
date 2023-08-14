<?php

namespace App\Core\Database;
class QueryBuilder
{

    protected $type;
    protected $fields;

    protected $table;

    protected $where;

    protected $set;

    protected $params;
    protected $values;

    protected $joinTable;
    protected $joinType;
    protected $on;

    public function __construct()
    {
        $this->params = [];
    }

    public function select($fields = '*') : self
    {
        $this->type = "select";
        $fields_string = $fields;
        if (is_array($fields)) {
            $fields_string = implode(', ', $fields);
        }
        $this->fields = $fields_string;
        return $this;
    }

    public function update($table) : self
    {
        $this->type = "update";
        $this->table = $table;
        return $this;
    }

    public function insertInto(string $table) : self
    {
        $this->type = "insert";
        $this->table = $table;
        return $this;
    }

    public function values(array $values) : self
    {
        $value_parts = [];
        $cols = [];
        foreach ($values as $key => $value) {
            $value_parts [] = ":{$key}";
            $cols [] = $key;
            $this->params[$key] = $value;
        }
        $this->fields = implode(', ', $cols);
        $this->values = implode(', ', $value_parts);
        return $this;
    }

    public function set($set) : self
    {
        $set_parts = [];
        foreach ($set as $key => $value) {
            $set_parts [] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }
        $this->set = implode(', ', $set_parts);
        return $this;
    }

    public function from($table) : self
    {
        $this->table = $table;
        return $this;
    }

    public function where($where): self
    {
        /* if(is_a($where)){


         }*/
        $where_parts = [];
        foreach ($where as $key => $value) {
            $where_parts [] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }

        $this->where = implode(' AND ', $where_parts);
        return $this;
    }

    public function delete()
    {
        $this->type = "delete";
        return $this;
    }


    public function innerJoin($joinTable)
    {
        $this->joinType = 'inner';
        $this->joinTable = $joinTable;
        return $this;
    }

    public function rightJoin($joinTable)
    {
        $this->joinType = "right";
        $this->joinTable = $joinTable;
        return $this;
    }

    public function leftJoin($joinTable)
    {
        $this->joinType = 'left';
        $this->joinTable = $joinTable;
        return $this;
    }

    public function on($currentTableCol, $joinedTableCol)
    {
        $this->on = "{$this->table}.{$currentTableCol} = {$this->joinTable}.$joinedTableCol";
        return $this;
    }

    public function getSql()
    {
        switch ($this->type) {
            case 'select':
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                if (!empty($this->joinTable)) {
                    switch ($this->joinType) {
                        case 'inner':
                            $sql .= " INNER ";
                            break;
                        case 'right':
                            $sql .= " RIGHT ";
                            break;
                        case 'left':
                            $sql .= " LEFT ";
                            break;
                    }
                    $sql .= "JOIN {$this->joinTable} ON {$this->on}";
                }
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
            case 'update':
                $sql = "UPDATE {$this->table} SET {$this->set}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
            case 'insert':
                $cols = '(' . $this->fields . ')' ?? "";
                return "INSERT INTO {$this->table} {$cols} VALUES ({$this->values})";
            case 'delete':
                return "DELETE FROM {$this->table} WHERE {$this->where}";
        }

    }

    public function getParams(): array
    {
        return $this->params;
    }
}