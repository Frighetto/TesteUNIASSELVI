<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$table = $_REQUEST['table'];
$filtro = $_REQUEST['filtro'];


if (userKeyValidation($key, $conn)) {
   
    if (sizeof($filtro)==0) {
        $sql = "select * from $table";
        echo $conn->query($sql)->num_rows;
    } else {
        switch (table) {
            case 'cliente':
                $sql = "select * from $table where CPF != '#'";
                foreach ($filtro as $key => $value) {
                    $sql .= " and  $key = '$value'";
                }
                echo $conn->query($sql)->num_rows;
                break;
            case 'produto':
                $sql = "select idProduto, CodBarras, nomeProduto, valorUnitario from produto where idProduto != -1 ";
                foreach ($filtro as $key => $value) {
                    $sql .= " and  $key = '$value'";
                }
                echo $conn->query($sql)->num_rows;
                break;
            case 'pedido':
                $sql = "select p.NumeroPedido as NumeroPedido, p.CPF as CPF, p.DtPedido as DtPedido, pt.NomeProduto as NomeProduto, p.quantidade as quantidade, p.quantidade * pt.valorUnitario - p.desconto as valorTotal, p.status as status  from pedido p join produto pt where p.idProduto = pt.idProduto";
                foreach ($filtro as $key => $value) {
                    $sql .= " and " . str_replace("valorTotal", "(p.quantidade * pt.valorUnitario - p.desconto)", $key) . " = '$value'";
                }
                
                echo $conn->query($sql)->num_rows;
                break;
        }
    }
}
