<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$table = $_REQUEST['table'];
$id = $_REQUEST['id'];
$sql;
if (userKeyValidation($key, $conn)) {
    switch ($table) {
        case 'produto':
            $sql = "delete from $table where idProduto = '$id'";
            break;
        case 'cliente':
            $sql = "delete from $table where CPF = '$id'";
            break;
        case 'pedido':
            $sql = "delete from $table where NumeroPedido = '$id'";
            break;
    }
}


$result = $conn->query($sql) or die("É necessário deletar os pedidos vinculados à este item.");
