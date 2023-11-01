<?php

namespace App\Helpers;

use Conf\DB;

class SQL
{
    private static $conn;

    public static function findById($tableName, $id)
    {
        $query = 'SELECT * FROM ' . $tableName . ' where id = :id';
        $sth = self::prepareStmt($query);
        $sth->execute([':id' => $id]);
        return $sth->fetch();
    }

    public static function getAll($tableName)
    {
        $query = 'SELECT * FROM ' . $tableName;
        $sth = self::prepareStmt($query);
        $sth->execute();
        return $sth->fetchAll();
    }

    public static function queryWithParams($query, $params)
    {
        $sth = self::prepareStmt($query);
        $sth->execute($params);
        return $sth->fetchAll();
    }

    public static function findWithParams($query, $params)
    {
        $sth = self::prepareStmt($query);
        $sth->execute($params);
        return $sth->fetch();
    }

    public static function save($query, $params)
    {
        $sth = self::prepareStmt($query);
        if(! $sth->execute($params)){
            return false;
        }else{
            return self::$conn->lastInsertId();
        }
    }

    private static function prepareStmt($query)
    {
        self::$conn = DB::connect();
        $sth = self::$conn->prepare($query);
        return $sth;
    }



}


?>