<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$table = $_REQUEST['table'];

if (userKeyValidation($key, $conn)) {
    $sql = "select * from $table";    
    echo $conn->query($sql)->num_rows;
}
