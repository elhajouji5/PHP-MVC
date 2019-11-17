<?php

class Model
{

    public function __construct()
    {
        //
    }

    public static function get()
    {
        $table = strtolower(get_called_class()) . 's';
        $model = new static;
        $connection = Connection::get_instance()->connect_to_db();;
        if(!$connection) return;
        $statement = $connection->prepare("SELECT * from products");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $data = $statement->fetchAll();
        return $data;
    }    

    /**
     * Set the related models
     */

}
