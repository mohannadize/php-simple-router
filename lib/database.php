<?php

$db = new PDO("sqlite:sql.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

class Database
{
    static function query(string $sql)
    {
        global $db;
        $res = $db->query($sql);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        return $res;
    }

    static function prepare(string $sql)
    {
        global $db;
        return $db->prepare($sql);
    }

    static function begin_transaction()
    {
        global $db;
        return $db->beginTransaction();
    }

    static function commit()
    {
        global $db;
        return $db->commit();
    }
}
