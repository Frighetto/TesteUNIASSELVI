<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$id = $_REQUEST['id'];
$codBarras = $_REQUEST['codBarras'];
$nomeProduto = $_REQUEST['nomeProduto'];
$valor = $_REQUEST['valor'];



if (userKeyValidation($key, $conn)) {
    setTableProduto();
}

function setTableProduto() {
    $id = $GLOBALS['id'];
    $codBarras = $GLOBALS['codBarras'];
    $nomeProduto = $GLOBALS['nomeProduto'];
    $valor = $GLOBALS['valor'];
   
    $conn = $GLOBALS['conn'];
    if ($id === "") {
        $sql = "insert into produto (CodBarras, nomeProduto, valorUnitario) values ('$codBarras','$nomeProduto','$valor');";       
        $conn->query($sql);
    } else {
        $sql = "select * from prduto where id = " . $id;
        $result = $conn->query($sql);

        if ($result->num_rows === 0) {
            $sql = "insert into produto (idProduto, CodBarras, nomeProduto, valorUnitario) values ('$id',$codBarras','$nomeProduto','$valor');";  
            $conn->query($sql);
        } else {
            $sql = "update produto set CodBarras = '$codBarras', nomeProduto = '$nomeProduto', valorUnitario = '$valor' where idProduto = '$id'";
            $conn->query($sql);
        }
    }

    echo 'ok';
}
