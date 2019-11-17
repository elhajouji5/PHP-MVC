<?php

class DB
{

    ## Interacts with database

    /**
     * @return mixed
     */
    public static function select(string $query)
    {
        $con = Connection::get_instance()->connect_to_db();
        $statement = $con->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }

}
