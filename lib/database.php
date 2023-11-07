<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $dbase;
    private $connection;

    function __construct($host, $username, $password, $dbase)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbase = $dbase;
        $this->open_connection();
    }

    public function open_connection()
    {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->dbase);
        if (mysqli_connect_errno()) {
            die(
                "Database connection failed: " .
                mysqli_connect_error() .
                " (" . mysqli_connect_errno() . ")"
            );
        }
        mysqli_set_charset($this->connection, "latin1");
    }

    public function start_transaction()
    {
        return mysqli_begin_transaction($this->connection);
    }

    public function commit()
    {
        return mysqli_commit($this->connection);
    }

    public function close_connection()
    {
        if (isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->connection, $sql);
        $this->confirm_query($result);
        return $result;
    }

    public function migrate_query($sql)
    {
        var_dump($sql);
        return $this->query($sql);
    }

    private function confirm_query($result)
    {
        if (!$result) {
            $error = mysqli_error($this->connection);
            error_log($error);
            throw new Exception($error);
        }
    }

    public function escape_value($par)
    {
        if (!is_array($par)) {
            return mysqli_real_escape_string($this->connection, $par);
        } else {
            return array_map(
                function ($r) {
                    return $this->escape_value($r);
                }, $par
            );
        }
    }

    public function unescape_value($par)
    {
        if (!is_array($par)) {
            return strtr(
                $par, [
                '\0'   => "\x00",
                '\n'   => "\n",
                '\r'   => "\r",
                '\\\\' => "\\",
                "\'"   => "'",
                '\"'   => '"',
                '\Z' => "\x1a"
                ]
            );
        } else {
            return array_map(
                function ($r) {
                    return $this->unescape_value($r);
                }, $par
            );
        }
    }

    // "database neutral" functions

    public function fetch_array($result_set)
    {
        return mysqli_fetch_assoc($result_set);
    }

    public function num_rows($result_set)
    {
        return mysqli_num_rows($result_set);
    }

    public function insert_id()
    {
        // get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }

    public function affected_rows()
    {
        return mysqli_affected_rows($this->connection);
    }
}
