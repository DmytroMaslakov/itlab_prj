<?php

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

    public function select($fields = '*')
    {
        $this->type = "select";
        $fields_string = $fields;
        if (is_array($fields)) {
            $fields_string = implode(', ', $fields);
        }
        $this->fields = $fields_string;
        return $this;
    }

    public function update($table)
    {
        $this->type = "update";
        $this->table = $table;
        return $this;
    }

    public function insertInto(string $table)
    {
        $this->type = "insert";
        $this->table = $table;
        return $this;
    }

    public function values(array $values)
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

    public function set($set)
    {
        $set_parts = [];
        foreach ($set as $key => $value) {
            $set_parts [] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }
        $this->set = implode(', ', $set_parts);
        return $this;
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function where($where): QueryBuilder
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


    public function innerJoin($joinTable){
        $this->joinType = 'inner';
        $this->joinTable = $joinTable;
        return $this;
    }

    public function on($currentTableCol, $joinedTableCol){
        $this->on = "{$this->table}.{$currentTableCol} = {$this->joinTable}.$joinedTableCol";
        return $this;
    }

    public function getSql()
    {
        switch ($this->type) {
            case 'select':
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                if(!empty($this->joinTable)){
                    switch ($this->joinType){
                        case 'inner':
                            $sql .= " INNER JOIN {$this->joinTable} ON {$this->on}";
                            break;
                    }
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