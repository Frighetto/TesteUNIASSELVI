<?php
include 'dbconnect.php';

$sql = "drop table pedido";
$conn->query($sql);
$sql = "drop table produto";
$conn->query($sql);
$sql = "drop table cliente";
$conn->query($sql);
$sql = "create table usuario (id varchar(20) primary key, senha varchar(20), k varchar(50));";
$conn->query($sql);
$sql = "insert into usuario (id, senha) values ('adm','123');";
$conn->query($sql);

$sql = "create table cliente (CPF char(11) primary key, nomeCliente varchar(100) not null, email varchar(30));";
$conn->query($sql);
for($i = 0; $i < 100 ; $i++){
    $sql = "insert into cliente (CPF, nomeCliente, email) values ($i,'joão$i','jo$i@email.com')";
    $conn->query($sql);
}

$sql = "create table produto (idProduto serial primary key, CodBarras varchar(20) not null, nomeProduto varchar(255), valorUnitario decimal(15,5) not null);";
$conn->query($sql);
for($i = 0; $i < 100 ; $i++){
    $sql = "insert into produto (idProduto, CodBarras, nomeProduto, valorUnitario) values ($i,'111111111$i','produto$i',1.$i)";
    $conn->query($sql);
}

$sql = "create table pedido (NumeroPedido serial primary key, CPF char(11) not null, foreign key(CPF) references cliente(CPF), idProduto BIGINT unsigned , foreign key(idProduto) references produto(idProduto), DtPedido date, quantidade int not null, status varchar (10) not null, desconto decimal(15,5));";
$conn->query($sql);
 
for($i = 0; $i < 100 ; $i++){
    $sql = "insert into pedido (NumeroPedido, CPF, idProduto, quantidade, DtPedido, status, desconto) values ($i,'".rand(1,99)."',".rand(1,99).",".rand(1,99).",now(),'Pago',1.$i)";   
    $conn->query($sql);
}

echo 'Dados gerados, por favor volte a página.'
?>
