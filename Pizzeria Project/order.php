<?php

require_once './session.php';
require_once './conn-pdo.php';
require_once './Pizza.php';

//negando acesso do conteúdo ao usuario não logado
//if (!$logado) {
// die('Você não tem permissão para acessar esse conteúdo!');
//}

if (isset($_POST["sair"])) {
    session_unset();
    session_destroy();
}

$pizza = new Pizza;
?>
<!DOCTYPE html>
<html>
    <body>

        <h1>Painel Pedidos</h1>
        <hr>

        <form action ="login.php" method ="post"><button type=submit name="sair" style="float: right;"> Sair </button></form>

        <form action ="index-atendente.php" method ="post"><button type=submit name="voltar" style="float: right;"> Voltar </button></form> 

        <form action="#" method="post">
            <label>Sabores:</label>
            <select  name="pizza[sabor]" >
                <option value="" selected></option>
                <?php
                $stmt = $conexao->prepare("SELECT sabor FROM felipe_pizza  ");
                if ($stmt->execute()) {
                    while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                        ?>

                        <option value="<?php echo $rs->sabor; ?>"><?php echo $rs->sabor; ?></option> <?php
            }
        }
                ?>

            </select>

            &nbsp;Quantidade:<input type="text" name="pizza[quantidade]"> 

            <button type=submit name="adicionar"> Adicionar </button>
            <button type=submit name="total"> Total </button>
            &nbsp;&nbsp;&nbsp;<button type=submit name="novo"> Novo Pedido </button>


        </form>    

        <?php
        //Adicionar o sabor ao pedido calcunlando o preço da taxa e quantidade
        if (isset($_POST["adicionar"])):

            if (!isset($_SESSION["pedido"]["sabores"], $_SESSION["pedido"]["sabores"], $_SESSION["pedido"]["sabores"])):
                $_SESSION["pedido"]["sabores"] = array();
                $_SESSION["pedido"]["valores"] = array();
                $_SESSION["pedido"]["quantidade"] = array();

            endif;

            $stmt = $conexao->prepare("SELECT * FROM felipe_pizza WHERE sabor = ?  ");

            $stmt->bindValue(1, $_POST["pizza"]["sabor"]);

            if ($stmt->execute()) {
                
            }
            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                $pizza->setSabor($rs->sabor);
                $pizza->setValor($rs->valor);
                $pizza->setTaxa($rs->taxa);
                $pizza->setEntrega($rs->entrega);
            }

            //calculo da quantidade e taxa de acordo com o banco de dados
            function calculo($valor, $qnt, $taxa) {

                $total = $parcial = $valor * $qnt;

                return (( $taxa / 100 ) * $parcial) + $total;
            }

            array_push($_SESSION["pedido"]["sabores"], $_POST['pizza']['sabor']);
            array_push($_SESSION["pedido"]["quantidade"], $_POST["pizza"]["quantidade"]);

            //usando função calcular e gravando o valor na session
            $passa = (calculo($pizza->getValor(), $_POST["pizza"]["quantidade"], $pizza->getTaxa()));

            array_push($_SESSION["pedido"]["valores"], $passa);

            echo "<br>";
            echo "<br>";
            
            //imprimindo na tela a adição das pizzas, quantidade e valor
            function recurse_array($values) {
                $content = '';
                if (is_array($values)) {
                    foreach ($values as $key => $value) {
                        if (is_array($value)) {
                            $content .= "<strong><u>$key:</u></strong> " . recurse_array($value);
                        } else {
                            $content .= "$value - ";
                        }
                    }
                }
                return $content;
            }

            echo recurse_array($_SESSION["pedido"]);

        endif;

        //Calcular o total do pedido e valor da entrega
        if (isset($_POST["total"])):

            $total = array_sum($_SESSION["pedido"]["valores"]);

            $qnt = array_sum($_SESSION["pedido"]["quantidade"]);

            //verificando e calculando o bonus do atendente
            if ($qnt >= 15):

                $bonus = ( 0.5 / 100 ) * $total;
                echo "<br>";
                echo "<font face='arial' color='#424242' size='3'><li>Atendente ganhou bonus de <strong>R$: $bonus </li></strong></font>";
            endif;

            //mostrando o total do pedido
            echo "<br>";
            echo "<font face='arial' color='#424242' size='5'>TOTAL DO PEDIDO <strong>R$: $total </strong></font>";
            $_SESSION["pedido"]["Total"] = $total;

            //verificando se o cliente tem direito ao brinde
            if ($qnt >= 5):
                echo "<br>";
                echo "<br>";
                echo "<li><strong>Cliente tem direito a uma Coca-Cola de brinde</strong></li>";
                $_SESSION["pedido"]["Refri_Brinde"] = "Sim";
            else:
                $_SESSION["pedido"]["Refri_Brinde"] = "";
            endif;

        endif;

        //ao clicar em novo pedido a sessão é gravada em arquivo txt e depois apagada
        if (isset($_POST["novo"])):

            $conteudo = json_encode($_SESSION);

            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/PEDIDOS.txt", "a");
            fwrite($fp, "\n" . $conteudo);
            fclose($fp);

            unset($_SESSION["pedido"]);

        endif;
        ?>

    </body>
</html>




