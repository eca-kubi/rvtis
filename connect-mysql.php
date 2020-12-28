<?php
function getPdo() : PDO
{
    $pdo = null;
    $dsn = "mysql:dbname=timsdb;host=127.0.0.1";
    $user = "tims_root";
    $password ="1122";
    try {
        if ($pdo == null) {
            $pdo = new PDO($dsn, $user, $password);
        }
    } catch (PDOException $e) {
        $e->getMessage();
    }
    finally {
        return $pdo;
    }
}

?>