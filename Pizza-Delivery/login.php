<?php

require_once './session.php';
require_once './conn-pdo.php';
require_once './Usuario.php';

$user = new Usuario;

if (isset($_POST['btnEntrar'])) {

    $valido = $user->Logar($_POST["email"], $_POST["senha"]);

    $erros = array();

    if ($valido == 2) {
        $erros[] = "<font face='arial' color='#424242' size='4'><li> Campo login/senha precisa ser preenchido!</li></font>";
        session_unset();
        session_destroy();
    }

    if ($valido == 3) {
        $erros[] = "<font face='arial' color='#FF0000' size='4'><li>Usuario não autorizado!</li></font>";
        session_unset();
        session_destroy();
    }

    if ($valido == 1) {

        header("Location:http://localhost/projeto01/EntregaOK/index-administrador.php");
    }
    if ($valido == 0) {

        header("Location:http://localhost/projeto01/EntregaOK/index-atendente.php");
    }
}
?>
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body bgcolor="#FFFFFF">
        <div align="center">
            <h1 align="center">Login</h1>
            <?php
            if (!empty($erros)):
                foreach ($erros as $erro):
                    echo $erro;
                endforeach;

            endif;
            ?>
            <hr>
            <form action ="login.php" method ="post">
                <p>
                    Email:<br> 
                    <input type="text" name="email" requerid>
                </p>
                <p>
                    Senha:<br>
                    <input type="password" name="senha" requerid>
                </p>
                <p>
                    <button type=submit name="btnEntrar"> Entrar </button>    
                </p>
            </form>
        </div>
    </body>
</html>


