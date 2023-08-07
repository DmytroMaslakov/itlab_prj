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

    public function getSql()
    {
        switch ($this->type) {
            case 'select':
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
            case 'update':
                $sql = "UPDATE {$this->table} SET {$this->set}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
            case 'insert':
                $cols = $this->fields??"";
                return "INSERT INTO {$this->table} ({$cols}) VALUES ({$this->values})";
        }

    }

    public function getParams(): array
    {
        return $this->params;
    }
}