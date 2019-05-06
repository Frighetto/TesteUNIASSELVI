<?php

include 'dbconnect.php';
include 'user.php';

$key = $_REQUEST['key'];
$table = $_REQUEST['table'];
$order = $_REQUEST['order'];
$page = $_REQUEST['page'];
$filtro = $_REQUEST['filtro'];



if (userKeyValidation($key, $conn)) {
    switch ($table) {
        case "pedido" : getTablePedido();
            break;
        case "cliente" : getTableCliente();
            break;
        case "produto" : getTableProduto();
            break;
    }
}

function getTableProduto() {
    echo "<thead>";
    echo "<tr>";
    echo "<td>#</td><td>Código de Barras</td><td>Nome do Produto</td><td>Valor</td><td>Deletar</td>";
    echo "</tr>";
    echo "</thread>";
    echo "<tbody>";
    echo "<tr>";
    echo '<td><input id="idProduto" rtype="number" placeholder="Id"></td>'
    . '<td><input id="produtoCodBarras" type="text" placeholder="Código de Barras"></td>'
    . '<td><input id="produtoNomeProduto" type="text" placeholder="Nome do Produto"></td>'
    . '<td><input id="produtoValor" type="number" placeholder="Valor"></td>'
    . "<td><button onclick=\"registrarProduto()\">Registrar</buton></td>";
    echo "</tr>";
    $page = $GLOBALS['page'];
    $order = $GLOBALS['order'];
    $sql = "select idProduto, CodBarras, nomeProduto, valorUnitario from produto where idProduto != -1 ";
    $filtro = $GLOBALS['filtro'];
    
    if (sizeof($filtro) > 0) {
        foreach ($filtro as $key => $value) {
            $sql .= " and  $key = '$value'";
        }
    }
    $sql .= " order by $order";
    $conn = $GLOBALS['conn'];
    $result = $conn->query($sql);
    $count = 0;
    
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            if ($count >= ($page - 1) * 20 && $count <= (($page - 1) * 20) + 20) {
                echo "<tr>";
                echo "<td>" . $row['idProduto'] . "<button  onclick=\"filtrar('produto','idProduto','" . $row['idProduto'] . "')\" >/*</button></td>"
                . "<td >" . $row['CodBarras'] . "<button onclick=\"filtrar('produto','codBarras','" . $row['CodBarras'] . "')\" >/*</button></td>"
                . "<td ><a onclick=\"showPedido('NomeProduto', '" . $row['nomeProduto'] . "')\" class=\"nav-link\" id=\"pedidos-tab\" data-toggle=\"tab\" href=\"#pedidos\" role=\"tab\" aria-controls=\"pedidos\">" . $row['nomeProduto'] . "</a><button onclick=\"filtrar('produto','nomeProduto','" . $row['nomeProduto'] . "')\" >/*</button></td>"
                . "<td >" . $row['valorUnitario'] . "<button onclick=\"filtrar('produto','valorUnitario','" . $row['valorUnitario'] . "')\" >/*</button></td>"
                . "<td><button onclick=\"deletar('produto','" . $row['idProduto'] . "')\">Deletar</button></td>";
                echo "</tr>";
            }
            $count++;
        }
    } else {
        
    }
    echo "</tbody>";
}

function getTablePedido() {
    $conn = $GLOBALS['conn'];
    $sql = "select CPF from cliente";
    $cpfs = $conn->query($sql);
    $sql = "select idProduto from produto";
    $produtos = $conn->query($sql);

    echo "<thead>";
    echo "<tr>";
    echo "<td>#</td><td>CPF</td><td>Data</td><td>Produto</td><td>Quantidade</td><td>Total</td><td>Status</td><td>Deletar</td>";
    echo "</tr>";
    echo "</thread>";
    echo "<tbody>";
    echo "<tr>";
    echo '<td><input id="pedidoId" type="number" placeholder="Id"></td>';


    echo '<td><select id="pedidoCPF">';
    while ($row = $cpfs->fetch_assoc()) {
        echo '<option>' . $row['CPF'] . '</option>';
    }
    echo '</select></td>';

    echo '<td><input id="pedidoData" type="date" placeholder="Data"></td>';
    echo '<td><select id="pedidoProdutoId">';
    while ($row = $produtos->fetch_assoc()) {
        echo '<option>' . $row['idProduto'] . '</option>';
    }
    echo '</select></td>';

    echo '<td><input id="pedidoQuantidade" type="number" pattern="[1-9][0-9]*" placeholder="Quantidade"></td>'
    . '<td><input id="pedidoDesconto" type="number" placeholder="Desconto"></td>'
    . '<td><select id="pedidoStatus"><option>Pago</option><option>Em Aberto</option><option>Cancelado</option></select></td>'
    . "<td><button onclick=\"registrarPedido()\">Registrar</buton></td>";
    echo "</tr>";
    $page = $GLOBALS['page'];
    $order = $GLOBALS['order'];
    $filtro = $GLOBALS['filtro'];
    $sql = "select p.NumeroPedido as NumeroPedido, p.CPF as CPF, p.DtPedido as DtPedido, pt.NomeProduto as NomeProduto, p.quantidade as quantidade, p.quantidade * pt.valorUnitario - p.desconto as valorTotal, p.status as status  from pedido p join produto pt where p.idProduto = pt.idProduto";
    if (sizeof($filtro) > 0) {
        foreach ($filtro as $key => $value) {
            $sql .= " and " . str_replace("valorTotal", "(p.quantidade * pt.valorUnitario - p.desconto)", $key) . " = '$value'";
        }
    }
    $sql .= " order by $order";
    $result = $conn->query($sql);
    $count = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            if ($count >= ($page - 1) * 20 && $count <= (($page - 1) * 20) + 20) {
                echo "<tr>";
                echo "<td onclick=\"filtrar('pedido','NumeroPedido','" . $row['NumeroPedido'] . "')\">" . $row['NumeroPedido'] . "<button  >/*</button></td>"
                . "<td ><a onclick=\"showCliente('" . $row['CPF'] . "')\" class=\"nav-link\" id=\"clientes-tab\" data-toggle=\"tab\" href=\"#clientes\" role=\"tab\" aria-controls=\"clientes\">" . $row['CPF'] . "</a><button onclick=\"filtrar('pedido','CPF','" . $row['CPF'] . "')\" >/*</button></td>"
                . "<td >" . $row['DtPedido'] . "<button onclick=\"filtrar('pedido','DtPedido','" . $row['DtPedido'] . "')\" >/*</button></td>"
                . "<td ><a onclick=\"showProduto('" . $row['NomeProduto'] . "')\" class=\"nav-link\" id=\"produtos-tab\" data-toggle=\"tab\" href=\"#produtos\" role=\"tab\" aria-controls=\"produtos\">" . $row['NomeProduto'] . "</a><button onclick=\"filtrar('pedido','NomeProduto','" . $row['NomeProduto'] . "')\" >/*</button></td>"
                . "<td >" . $row['quantidade'] . "<button onclick=\"filtrar('pedido','quantidade','" . $row['quantidade'] . "')\" >/*</button></td>"
                . "<td >" . $row['valorTotal'] . "<button onclick=\"filtrar('pedido','valorTotal','" . $row['valorTotal'] . "')\" >/*</button></td>"
                . "<td >" . $row['status'] . "<button onclick=\"filtrar('pedido','status','" . $row['status'] . "')\" >/*</button></td>"
                . "<td><button onclick=\"deletar('pedido','" . $row['NumeroPedido'] . "')\">Deletar</button></td>";
                echo "</tr>";
            }
            $count++;
        }
    } else {
        
    }

    echo "</tbody>";
}

function getTableCliente() {
    echo "<thead>";
    echo "<tr>";
    echo "<td>CPF</td><td>Nome</td><td>Email</td><td>Deletar</td>";
    echo "</tr>";
    echo "</thread>";
    echo "<tbody>";
    echo "<tr>";
    echo '<td><input id="clienteCPF" type="number" placeholder="CPF"></td>'
    . '<td><input id="clienteNomeCliente" type="text" placeholder="Nome"></td>'
    . '<td><input id="clienteEmail" type="text" placeholder="Email"></td>'
    . "<td><button onclick=\"registrarCliente()\">Registrar</buton></td>";
    echo "</tr>";
    $page = $GLOBALS['page'];
    $order = $GLOBALS['order'];
    $sql = "select CPF, nomeCliente, email from cliente where CPF != '#' ";
    $filtro = $GLOBALS['filtro'];
    
    if (sizeof($filtro) > 0) {
        foreach ($filtro as $key => $value) {
            $sql .= " and  $key = '$value'";
        }
    }
    $sql .= " order by $order";
    $conn = $GLOBALS['conn'];
    $result = $conn->query($sql);
    $count = 0;
    echo $sql;

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            if ($count >= ($page - 1) * 20 && $count <= (($page - 1) * 20) + 20) {
                echo "<tr>";
                echo "<td ><a onclick=\"showPedido('CPF', '" . $row['CPF'] . "')\" class=\"nav-link\" id=\"pedidos-tab\" data-toggle=\"tab\" href=\"#pedidos\" role=\"tab\" aria-controls=\"pedidos\">" . $row['CPF'] . "</a><button onclick=\"filtrar('cliente','cpf','" . $row['CPF'] . "')\" >/*</button></td>"
                . "<td >" . $row['nomeCliente'] . "<button onclick=\"filtrar('cliente','nomeCliente','" . $row['nomeCliente'] . "')\" >/*</button></td>"
                . "<td >" . $row['email'] . "<button onclick=\"filtrar('cliente','email','" . $row['email'] . "')\" >/*</button></td>"
                . "<td><button onclick=\"deletar('cliente','" . $row['CPF'] . "')\">Deletar</button></td>";
                echo "</tr>";
            }
            $count++;
        }
    } else {
        
    }
    echo "</tbody>";
}
