<?php
function getPdo()
{
    $db = null;
    try {
        if ($db == null) {
            $db = new PDO("sqlite:tims.sqlite", "", "", array(
                PDO::ATTR_PERSISTENT => true
            ));
        }
    } catch (PDOException $e) {
        $e->getMessage();
    }
    finally {
        return $db;
    }
}

function exists($table, $column, $where=""){
  global $db;
  $ret= $db->query("SELECT $column from $table"+$where);
 if($ret){
return true;
 }
 return false;
}
//$db = getPdo();
$db = getPdo();
?>