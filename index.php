<?php
header('Access-Control-Allow-Origin: *'); 
?>
<!DOCTYPE html>
<html>
    <head>       
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
         <link rel="icon" href="imagem/favicon.ico">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/bootstrap.css">        
    </head>
    <body>       
        <div class="container">
            <div class="form-signin">
                <a href="gerardados.php">Clique aqui para gerar os dados no banco.</a>
                <form method="post" action="painel.php" name="formlogin">                    
                    <label for="inputEmail" class="sr-only">Nome de usuário</label>
                    <input name="user" type="text" id="user" class="form-control" placeholder="Email" value="adm" required autofocus>
                    <label for="inputPassword" class="sr-only">Senha</label>
                    <input name="password" type="password" id="password" class="form-control" placeholder="Senha" value="123" required>
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Entrar</button>
                </form>
            </div>
        </div>        
    </body>
</html>