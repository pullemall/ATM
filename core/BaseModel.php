<?php
namespace App\Core;

class BaseModel
{
    public $fields;
    protected $schema;

    public function __construct()
    {
        $full_class_name = mb_strtolower(get_class($this));
        $this->schema = explode("\\", $full_class_name)[2];
        $this->fields = array();
    }

    public function __toString()
    {
        return $this->schema;
    }

    public function get_field($key)
    {
        return $this->fields[$key];
    }

    public function set_field($key, $value)
    {
        $this->fields[$key] = $value;
    }

    public function get($id)
    {
        $query = "SELECT * FROM " . $this->schema . " WHERE id = $id";
        $result = $this->query($query);

        $result = $result->fetchArray(SQLITE3_ASSOC);

        $class_name = get_called_class();
        $model = new $class_name();

        foreach($result as $key => $val) {
            $model->set_filed($key, $val);
        }

        return $model;
    }

    public function get_all()
    {
        $query = "SELECT * FROM " . $this->schema;
        $result = $this->query($query);

        $models = array();

        while($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $class_name = get_called_class();
            $model = new $class_name();
            
            foreach($res as $key => $val) {
                $model->set_field($key, $val);
            }

            $models[] = $model;
        }
    }

    public function field_exists($name)
    {
        return isset($this->fields[$name]);
    }

    public function save()
    {
        if($this->field_exists("id")) {
            $query = "UPDATE " . $this->schema . " SET ";
            $keys = array_keys($this->fields);
            $values = array_values($this->fields);
            $query_parts = array();

            for($i = 0; $i < count($values); $i++) {
                if($keys[$i] == "id")
                    continue;
                
                if(!is_numeric($values[$i]))
                    $query_parts[] = $keys[$i] . "=" . "'" . $values[$i] . "'";
                else
                    $query_parts[] = $keys[$i] . "=" . $values[$i];
            }

            $query .= implode(", ", $query_parts) . " WHERE id = " . $this->get_field("id");

            return $this->query($query);
        }

        $query = "INSERT INTO " . $this->schema;
        $keys = array_keys($this->fields);
        $values = array_values($this->fields);
        
        for($i = 0; $i < count($values); $i++) {
            if(!is_numeric($values[$i]))
                $values[$i] = "'" . $values[$i] . "'";
        }

        $query .= " (" . implode(", ", $keys) . ") VALUES (" . implode(",", $values) . ")";
        return $this->query($query);
    }

    public function query($query)
    {
        return \App\Core\Schema::query($query);
    }
}