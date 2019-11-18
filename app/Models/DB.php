<?php

class DB
{

    ## Interacts with database

    /**
     * @return mixed
     * @param string $selectQuery
     * @param array|null $param filter result by
     */
    public static function select(string $selectQuery, $param = null)
    {
        $con = Connection::get_instance()->connect_to_db();
        $statement = $con->prepare($selectQuery);
        // Add where clause to the selectQuery
        if($param)
        {
            $statement->bindParam(":search", $param[1]);
        }
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }

    /**
     * @return mixed
     * @param string $selectQuery
     * @param array|null $filter
     */
    public static function statement(string $selectQuery, $filter = null)
    {
        $con = Connection::get_instance()->connect_to_db();
        $statement = $con->prepare($selectQuery);
        if($filter)
        {
            $statement->bindParam(":search", $filter[1]);
        }
        $statement->execute();
    }

}
