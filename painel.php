<?php
include 'dbconnect.php';
include 'user.php';

$user = $_REQUEST['user'];
$password = $_REQUEST['password'];
?>

<!DOCTYPE html>
<html>
    <head>       
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Painel</title>         
        <link rel="stylesheet" href="css/painel.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.bootpag.js"></script>       
        <script>
            var key = '<?php echo userValidation($user, $password, $conn); ?>';

            if (key.length === 0) {
                var aux = window.location + "";
                window.location = aux.substr(0, aux.indexOf("painel.php"));
            }

            var pedidoPage = 1;
            var produtoPage = 1;
            var clientePage = 1;

            function getPage(table) {
                switch (table) {
                    case 'pedido' :
                        return pedidoPage;
                    case 'produto' :
                        return produtoPage;
                    case 'cliente' :
                        return clientePage;
                }
            }
            var filtroPedido = {};
            var filtroProduto = {};
            var filtroCliente = {};

            function getFiltro(table) {
                switch (table) {
                    case 'pedido' :
                        return filtroPedido;
                    case 'produto' :
                        return filtroProduto;
                    case 'cliente' :
                        return filtroCliente;
                }
            }

            function getRadio(table) {
                var pedidoatt = document.forms["radio" + table];
                var i;
                for (i = 0; i < pedidoatt.length; i++) {
                    if (pedidoatt[i].checked) {
                        return pedidoatt[i].value;
                    }
                }
            }

            function getTable(table) {
                $.ajax({
                    url: 'table.php',
                    type: 'GET',
                    data: {
                        key: key,
                        table: table,
                        order: getRadio(table),
                        page: getPage(table),
                        filtro: getFiltro(table)
                    },
                    success: function (result) {
                        $('.content' + table).html(result);
                    }
                });
            }

            function registrarPedido() {

                $.ajax({
                    url: 'setTablePedido.php',
                    type: 'GET',
                    data: {
                        key: key,
                        id: document.getElementById('pedidoId').value,
                        cpf: document.getElementById('pedidoCPF').value,
                        date: document.getElementById('pedidoData').value,
                        produtoId: document.getElementById('pedidoProdutoId').value,
                        quantidade: document.getElementById('pedidoQuantidade').value,
                        desconto: document.getElementById('pedidoDesconto').value,
                        status: document.getElementById('pedidoStatus').value
                    },
                    success: function (feedback) {                        
                        if (feedback === 'ok') {
                            setTablePages('pedido', 20);
                            getTable('pedido');
                        }
                    }
                });
            }

            function registrarProduto() {

                $.ajax({
                    url: 'setTableProduto.php',
                    type: 'GET',
                    data: {
                        key: key,
                        id: document.getElementById('idProduto').value,
                        codBarras: document.getElementById('produtoCodBarras').value,
                        nomeProduto: document.getElementById('produtoNomeProduto').value,
                        valor: document.getElementById('produtoValor').value
                    },
                    success: function (feedback) {

                        if (feedback === 'ok') {
                            setTablePages('produto', 20);
                            getTable('produto');
                            getTable('pedido');
                        }

                    }
                });
            }


            function registrarCliente() {

                $.ajax({
                    url: 'setTableCliente.php',
                    type: 'GET',
                    data: {
                        key: key,
                        cpf: document.getElementById('clienteCPF').value,
                        nomeCliente: document.getElementById('clienteNomeCliente').value,
                        email: document.getElementById('clienteEmail').value
                    },
                    success: function (feedback) {

                        if (feedback === 'ok') {
                            setTablePages('produto', 20);
                            getTable('cliente');
                            getTable('pedido');
                        }

                    }
                });
            }

            function deletar(table, id) {
                $.ajax({
                    url: 'delete.php',
                    type: 'GET',
                    data: {
                        key: key,
                        table: table,
                        id: id
                    },
                    success: function (feedback) {
                        if (feedback !== "") {
                            alert(feedback);
                        }
                        setTablePages(table, 20);
                        getTable(table);
                    }
                });
            }

            function filtrar(table, column, value) {

                switch (table) {
                    case 'pedido' :
                        filtroPedido[column] = value;
                        var html = "<a onclick=\"desfiltrar('" + table + "','" + column + "')\">" + column + "=" + filtroPedido[column] + "</a>";
                        $('#filtroPedido' + column).html(html);                        
                        break;
                    case 'produto' :
                        filtroProduto[column] = value;
                        var html = "<a onclick=\"desfiltrar('" + table + "','" + column + "')\">" + column + "=" + filtroProduto[column] + "</a>";
                        $('#filtroProduto' + column).html(html);
                        break;
                    case 'cliente' :
                        filtroCliente[column] = value;
                        var html = "<a onclick=\"desfiltrar('" + table + "','" + column + "')\">" + column + "=" + filtroCliente[column] + "</a>";
                        $('#filtroCliente' + column).html(html);
                        break;
                }
                setTablePages(table, 20);
                getTable(table);
            }

            function desfiltrar(table, column) {
                switch (table) {
                    case 'pedido' :
                        delete filtroPedido[column];                       
                        $('#filtroPedido' + column).html('');
                        break;
                    case 'produto' :
                        delete filtroProduto[column];                        
                        $('#filtroProduto' + column).html('');
                        break;
                    case 'cliente' :
                        delete filtroCliente[column];                       
                        $('#filtroCliente' + column).html('');
                        break;
                }
                setTablePages(table, 20);
                getTable(table);
            }

            function disableTab() {
                document.getElementsByClassName('active')[0].className = document.getElementsByClassName('active')[0].className.replace('active', "");
            }

            function showCliente(cpf) {
                desfiltrar('cliente', 'nomeCliente');
                desfiltrar('cliente', 'email');
                filtrar('cliente', 'cpf', cpf);
                disableTab();
                setTablePages('cliente',20);
                getTable('cliente');
            }

            function showProduto(nome) {
                desfiltrar('produto', 'idProduto');
                desfiltrar('produto', 'codBarras');
                desfiltrar('produto', 'valorUnitario');
                filtrar('produto', 'nomeProduto', nome);
                disableTab();
                setTablePages('produto',20);
                getTable('produto');
            }

            function showPedido(coluna, item) {
                                
               filtroPedido = {};
               $('#filtroPedidoNumeroPedido').html('');
               $('#filtroPedidoCPF').html('');
               $('#filtroPedidoDtPedido').html('');
               $('#filtroPedidoNomeProduto').html('');
               $('#filtroPedidoquantidade').html('');
               $('#filtroPedidovalorTotal').html('');
               $('#filtroPedidostatus').html('');

                filtrar('pedido', coluna, item);
                disableTab();
                setTablePages('pedido',20);
                getTable('pedido');
            }
        </script>
    </head>
    <body>    

        <div class="container" align="center">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="pedidos-tab" data-toggle="tab" href="#pedidos" role="tab" aria-controls="pedidos" aria-selected="true" aria-expanded="true">Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="produtos-tab" data-toggle="tab" href="#produtos" role="tab" aria-controls="produtos" aria-selected="false">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab" aria-controls="clientes" aria-selected="false">Clientes</a>
                </li>                
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">


                <div class="tab-pane active" id="pedidos" role="tabpanel" aria-labelledby="pedidos-tab">


                    <form id="radiopedido">
                        Ordenar por<br>
                        <input class="radiospace" onclick="getTable('pedido')" type="radio" name="pedido" value="NumeroPedido" checked>#
                        <input class="radiospace"  onclick="getTable('pedido')" type="radio" name="pedido" value="CPF">CPF
                        <input class="radiospace"  onclick="getTable('pedido')" type="radio" name="pedido" value="DtPedido">Data
                        <input class="radiospace"  onclick="getTable('pedido')" type="radio" name="pedido" value="NomeProduto">Produto
                        <input  class="radiospace" onclick="getTable('pedido')" type="radio" name="pedido" value="quantidade">Quantidade
                        <input  class="radiospace" onclick="getTable('pedido')" type="radio" name="pedido" value="valorTotal">Total
                        <input  class="radiospace" onclick="getTable('pedido')" type="radio" name="pedido" value="status">Status                                           

                    </form>



                    <div id="filtroPedidoNumeroPedido"></div>
                    <div id="filtroPedidoCPF"></div>
                    <div id="filtroPedidoDtPedido"></div>
                    <div id="filtroPedidonomeProduto"></div>                    
                    <div id="filtroPedidoquantidade"></div>
                    <div id="filtroPedidovalorTotal"></div>
                    <div id="filtroPedidostatus"></div>


                    <p class="pedido_page_top"></p>

                    <table class="contentpedido table table-striped">

                    </table>

                    <p class="pedido_page_bottom"></p>


                </div>

                <div class="tab-pane" id="produtos" role="tabpanel" aria-labelledby="produtos-tab">
                    <form id="radioproduto">
                        Ordenar por<br>
                        <input  class="radiospace" onclick="getTable('produto')" type="radio" name="pedido" value="idProduto" checked>#
                        <input  class="radiospace" onclick="getTable('produto')" type="radio" name="pedido" value="codBarras">Código de Barras   
                        <input  class="radiospace" onclick="getTable('produto')" type="radio" name="pedido" value="nomeProduto">Nome do Produto
                        <input  class="radiospace" onclick="getTable('produto')" type="radio" name="pedido" value="valorUnitario">Valor                                                                                                 
                    </form>

                    <div id="filtroProdutoidProduto"></div>
                    <div id="filtroProdutocodBarras"></div>
                    <div id="filtroProdutonomeProduto"></div>
                    <div id="filtroProdutovalorUnitario"></div>
                    
                    <p class="produto_page_top"></p>
                    <table class="contentproduto table table-striped">

                    </table>
                    <p class="produto_page_bottom"></p>


                </div>
                <div class="tab-pane" id="clientes" role="tabpanel" aria-labelledby="clientes-tab">
                    <form id="radiocliente">
                        Ordenar por<br>
                        <input  class="radiospace" onclick="getTable('cliente')" type="radio" name="pedido" value="cpf" checked>CPF
                        <input  class="radiospace" onclick="getTable('cliente')" type="radio" name="pedido" value="nomeCliente">Nome   
                        <input  class="radiospace" onclick="getTable('cliente')" type="radio" name="pedido" value="email">Email
                    </form>
                    <div id="filtroClientecpf"></div>
                    <div id="filtroClientenomeCliente"></div>
                    <div id="filtroClienteemail"></div>

                    <p class="cliente_page_top"></p>
                    <table class="contentcliente table table-striped">

                    </table>
                    <p class="cliente_page_bottom"></p>


                </div>                
            </div>




        </div>
        <script>



            function setTablePages(table, maxPageSize) {
                $.ajax({
                    url: 'tableSize.php',
                    type: 'GET',
                    data: {
                        key: key,
                        table: table,
                        filtro: getFiltro(table)
                    },
                    success: function (tableSize) {                                                
                        var total = Number(1 + tableSize / maxPageSize - (tableSize / maxPageSize) % 1).toFixed(1);

                        $('.' + table + '_page_top,.' + table + '_page_bottom').bootpag({
                            total: total,
                            page: getPage(table),
                            maxVisible: 10,
                            leaps: true,
                            firstLastUse: true,
                            first: '←',
                            last: '→',
                            wrapClass: 'pagination',
                            activeClass: 'active',
                            disabledClass: 'disabled',
                            nextClass: 'next',
                            prevClass: 'prev',
                            lastClass: 'last',
                            firstClass: 'first'
                        }).on("page", function (event, num) {
                            switch (table) {
                                case 'pedido' :
                                    pedidoPage = num;
                                    break;
                                case 'produto' :
                                    produtoPage = num;
                                    break;
                                case 'cliente' :
                                    clientePage = num;
                                    break;
                            }
                            getTable(table);
                        });

                    }
                });
            }
            setTablePages('pedido', 20);
            setTablePages('produto', 20);
            setTablePages('cliente', 20);
            getTable('pedido');
            getTable('produto');
            getTable('cliente');

        </script>
    </body>
</html>
