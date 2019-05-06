<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$cpf = $_REQUEST['cpf'];
$nomeCliente = $_REQUEST['nomeCliente'];
$email = $_REQUEST['email'];



if (userKeyValidation($key, $conn)) {
    setTableCliente();
}

function setTableCliente() {
    $cpf = $GLOBALS['cpf'];
    $nomeCliente = $GLOBALS['nomeCliente'];
    $email = $GLOBALS['email'];

    $conn = $GLOBALS['conn'];

    $sql = "select * from cliente where cpf = '$cpf'";
    $result = $conn->query($sql);
   
    
    if ($result->num_rows === 0) {
        $sql = "insert into cliente (cpf, nomeCliente, email) values ('$cpf','$nomeCliente','$email');";
       $conn->query($sql);
    } else {
        $sql = "update cliente set nomeCliente = '$nomeCliente', email = '$email' where cpf = '$cpf'";
        $conn->query($sql);
    }


    echo 'ok';
}
