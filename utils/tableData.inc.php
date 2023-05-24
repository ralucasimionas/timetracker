<?php

require_once "db.inc.php";
require_once "inputValidation.inc.php";
require_once "validators.inc.php";
function getOne($field, $id, $table, $conn)
{
  $query = "SELECT * FROM `$table` WHERE $field ='$id'";
  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);
  return $user;
}

function update($field, $id, $table, $conn, array $updatedInfo)
{
  foreach ($updatedInfo as $key => $value) {
    $updatedPairs[] = "$key='$value'";
    echo "Updated Pairs";
    var_dump($updatedPairs);
  }

  $query = "UPDATE $table SET ";
  $query .= implode(",", $updatedPairs);
  $query .= " WHERE $field='$id'";
  var_dump($query);

  $result = mysqli_query($conn, $query);
  // $user = mysqli_fetch_assoc($result);
  echo "result din update";
  var_dump($result);
  return $result;
}

function create($table, $conn, $columns, $data)
{
  $query =
    "INSERT INTO $table (" .
    implode(",", $columns) .
    ") VALUES (" .
    implode(",", $data) .
    ")";

  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);
  return $user;
}
