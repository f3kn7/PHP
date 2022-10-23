<?php

require_once './session.php';
require_once './conn-pdo.php';

//negando acesso do conteúdo ao usuario não logado
//if (!$logado) {
// die('Você não tem permissão para acessar esse conteúdo!');
//}

echo "<font color='#0000FF'><strong>Bem Vindo</strong></font>" . " <font color='#04B404'><strong>$_SESSION[nome_user]!</strong></font> ";
echo "<br>";
echo "<br>";

if (isset($_POST["sair"])) {
    session_unset();
    session_destroy();
}
?>

<!doctype html>
<html>
    <head>
        <title>Painel - Aministrador</title>
    </head>
    <h1 >Painel Administrador</h1>
    <hr>
    <body>

        <a href="http://localhost/projeto01/EntregaOK/crud-usuario.php">Usuarios</a>&nbsp; &nbsp;
        <a href="http://localhost/projeto01/EntregaOK/crud-pizza.php">Pizzas</a>&nbsp; &nbsp;
        <a href="http://localhost/projeto01/EntregaOK/order.php">Pedidos</a>
        
        <br>
        <br>
        <!-- Botao Sair -->
        <form action ="login.php" method ="post">

            <button type=submit name="sair"> Sair </button>   

        </form>   
    </body>
</html>

