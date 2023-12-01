<?php

class Database
{
    private static $db = "sqlite:sql.db";
    private static $connection;

    static function init()
    {
        self::$connection = new PDO(self::$db);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::set_timezone("Africa/Abidjan");
    }

    static function query(string $sql)
    {
        $res = self::$connection->query($sql);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        return $res;
    }

    static function auto_commit(bool $status)
    {
        return self::$connection->setAttribute(PDO::ATTR_AUTOCOMMIT, $status);
    }

    static function prepare(string $sql)
    {
        return self::$connection->prepare($sql);
    }

    static function begin_transaction()
    {
        self::auto_commit(false);
        return self::$connection->beginTransaction();
    }

    static function commit()
    {
        try {
            $res = self::$connection->commit();
            self::auto_commit(true);
            return $res;
        } catch (\Throwable $th) {
            self::$connection->rollBack();
            self::auto_commit(true);
            throw $th;
        }
    }

    static function set_timezone($timezone)
    {
        define('TIMEZONE', $timezone);
        date_default_timezone_set($timezone);
        $now = new DateTime();
        $mins = $now->getOffset() / 60;
        $sgn = ($mins < 0 ? -1 : 1);
        $mins = abs($mins);
        $hrs = floor($mins / 60);
        $mins -= $hrs * 60;
        $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
        self::query("SET time_zone='$offset'");
        return true;
    }
}
