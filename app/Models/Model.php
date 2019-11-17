<?php

class Model
{

    protected $table;
    
    public function __construct()
    {
        //
    }

    /**
     * @return string
     * Get the table name
     */
    public function get_schema_name()
    {
        return $this->table ?? strtolower(get_called_class()) . 's';
    }

}
