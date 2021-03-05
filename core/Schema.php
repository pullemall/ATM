<?php
namespace App\Core;

class Schema
{
    /** @var string Table name */
    protected $table_name;

    /** @var array An array of fields for SQL query */
    protected $fields;

    /**
     * @param string $table_name A table name
     */
    public function __construct($table_name)
    {
        $this->table_name = $table_name;
        $this->fields = array("id" => "INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL");
    }

    /**
     * Adds a new field of the model.
     * 
     * @param string $name Name of the new field
     * @param string|int $type A type of the field
     */
    public function add_field($name, $type)
    {
        $this->fields[$name] = $type;
    }

    /**
     * Creates an instance of SQLite3 and executes a query.
     * 
     * @param string $query A query that needs to be executed. 
     */
    public static function query($query)
    {
        $db = new \SQLite3("ATM.db");
        return $db->query($query);
    }

    /**
     * Creates a database if it doesn't exists.
     */
    public function migrate()
    {
        $db = new \SQLite3("ATM.db");

        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " ";
        $fields = array();

        foreach($this->fields as $name=>$type) {
            $fields[] = "$name $type";
        }

        $query .= "(" . implode(", ", $fields) . ")";

        $db->exec($query);
    }
}