<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$id = $_REQUEST['id'];
$cpf = $_REQUEST['cpf'];
$produtoId = $_REQUEST['produtoId'];
$date = $_REQUEST['date'];
$quantidade = $_REQUEST['quantidade'];
$desconto = $_REQUEST['desconto'];
$status = $_REQUEST['status'];


if (userKeyValidation($key, $conn)) {
    setTablePedido();
}

function setTablePedido() {
    $id = $GLOBALS['id'];
    $cpf = $GLOBALS['cpf'];
    $produtoId = $GLOBALS['produtoId'];
    $date = $GLOBALS['date'];
    $quantidade = $GLOBALS['quantidade'];
    $status = $GLOBALS['status'];
    $desconto = $GLOBALS['desconto'];
    $conn = $GLOBALS['conn'];
    if ($id === "") {
        $sql = "insert into pedido (CPF, idProduto, DtPedido, quantidade, status, desconto) values ('$cpf','$produtoId','$date','$quantidade','$status','$desconto');";       
        $conn->query($sql);
    } else {
        $sql = "select * from pedido where NumeroPedido = '$id'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows === 0) {
            $sql = "insert into pedido (NumeroPedido, CPF, idProduto, DtPedido, quantidade, status, desconto) values ('$id','$cpf','$produtoId','$date','$quantidade','$status','$desconto');";
            $conn->query($sql);
        } else {
            $sql = "update pedido set CPF = '$cpf', idProduto = '$produtoId', DtPedido = '$date', quantidade = '$quantidade', status = '$status', desconto = '$desconto' where NumeroPedido = '$id'";
            $conn->query($sql);
        }
    }

    echo 'ok';
}
