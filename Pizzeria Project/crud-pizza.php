<?php

require_once './session.php';
// Cria a conexão com o banco de dados
require_once './conn-pdo.php';

//negando acesso do conteúdo ao usuario não logado
//if (!$logado) {
// die('Você não tem permissão para acessar esse conteúdo!');
//}

require_once './Pizza.php';

$pizza = new Pizza;

if (isset($_POST["sair"])) {
    session_unset();
    session_destroy();
}
 
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pizza->setIdSabor((isset($_POST["id_sabor"]) && $_POST["id_sabor"] != null) ? $_POST["id_sabor"] : "");
    $pizza->setSabor ((isset($_POST["sabor"]) && $_POST["sabor"] != null) ? $_POST["sabor"] : "");
    $pizza->setValor((isset($_POST["valor"]) && $_POST["valor"] != null) ? $_POST["valor"] : "");
    $pizza->setTaxa((isset($_POST["taxa"]) && $_POST["taxa"] != null) ? $_POST["taxa"] : "");
    $pizza->setEntrega((isset($_POST["entrega"]) && $_POST["entrega"] != null) ? $_POST["entrega"] : "");

// Se não se não foi setado nenhum valor para variável $id_usuario    
} else if (!isset($id_sabor)) {
    $pizza->setIdSabor((isset($_GET["id_sabor"]) && $_GET["id_sabor"] != null) ? $_GET["id_sabor"] : "");
    $pizza->setSabor(null);
    $pizza->setValor(null);
    $pizza->setTaxa(null);
    $pizza->setEntrega(null);
}
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $pizza->getSabor() != "") {
    
    try {
        if ($pizza->getIdSabor() != "") {
            
            $stmt = $conexao->prepare("UPDATE felipe_pizza SET sabor=?, valor=?, taxa=?, entrega=? WHERE id_sabor = ?");
            $stmt->bindValue(5, $pizza->getIdSabor(), PDO::PARAM_INT);
        } else {
            $stmt = $conexao->prepare("INSERT INTO felipe_pizza (sabor, valor, taxa, entrega) VALUES (?, ?, ?, ?)");
        }
        $stmt->bindValue(1, $pizza->getSabor());
        $stmt->bindValue(2, $pizza->getValor());
        $stmt->bindValue(3, $pizza->getTaxa());
        $stmt->bindValue(4, $pizza->getEntrega());
 
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Dados salvos com sucesso!')</script>";  
                $pizza->setIdSabor(null);
                $pizza->setSabor(null);
                $pizza->setValor(null);
                $pizza->setTaxa(null);
                $pizza->setEntrega(null);
             
            } else {
                echo "<script>alert('Erro ao tentar efetivar cadastro!')</script>";
               
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "<script>alert('Erro: {$erro->getMessage()}')</script>";
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $pizza->getIdSabor() != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM felipe_pizza WHERE id_sabor = ?");
        $stmt->bindValue(1, $pizza->getIdSabor(), PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $pizza->setIdSabor($rs->id_sabor);
            $pizza->setSabor($rs->sabor);
            $pizza->setValor($rs->valor);
            $pizza->setTaxa($rs->taxa);
            $pizza->setEntrega($rs->entrega);
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "<script>alert('Erro: {$erro->getMessage()}')</script>";
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $pizza->getIdSabor() != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM felipe_pizza WHERE id_sabor = ?");
        $stmt->bindValue(1, $pizza->getIdSabor(), PDO::PARAM_INT);
        if ($stmt->execute()) {
           echo "<script>alert('Registo foi excluído com êxito!')</script>";       
            $pizza->setIdSabor(null);
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "<script>alert('Erro: {$erro->getMessage()}')</script>";
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Cadastro de pizzas</title>
        </head>
        <body>
            <h1>Cadastro de pizzas</h1>
            
              <form action ="login.php" method ="post"><button type=submit name="sair" style="float: right;"> Sair </button></form>
              
              <form action ="index-administrador.php" method ="post"><button type=submit name="voltar" style="float: right;"> Voltar </button></form> 
              
            <form action="?act=save" method="POST" name="form1" >
                
                
                <hr>
                <input type="hidden" name="id_sabor" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if ($pizza->getIdSabor() != null || $pizza->getIdSabor() != "") {
                    echo "value=\"{$pizza->getIdSabor()}\"";
                }
                ?> />
                Sabor:
               <input type="text" name="sabor" <?php
 
               // Preenche o sabor no campo sabor com um valor "value"
               if ($pizza->getSabor() != null || $pizza->getSabor() != "") {
                   echo "value=\"{$pizza->getSabor()}\"";
               }
               ?> />
               Valor:
               <input type="text" name="valor" <?php
 
               // Preenche o valor no campo valor com um valor "value"
               if ($pizza->getValor() != null || $pizza->getValor() != "") {
                   echo "value=\"{$pizza->getValor()}\"";
               }
               ?> />
               Taxa:
               <input type="text" name="taxa" <?php
 
               // Preenche a taxa no campo taxa com um valor "value"
               if ($pizza->getTaxa() != null || $pizza->getTaxa() != "") {
                   echo "value=\"{$pizza->getTaxa()}\"";
               }
               ?> />
                Entrega:
               <input type="text" name="entrega" <?php
 
               // Preenche o entrega no campo entrega com um valor "value"
               if  ($pizza->getEntrega() != null || $pizza->getEntrega() != "") {
                   echo "value=\"{$pizza->getEntrega()}\"";
               }
               ?> />
               <input type="submit" value="salvar"/>
               <input type="reset" value="Novo"/>
               
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Sabor</th>
                    <th>Valor</th>
                    <th>Taxa</th>
                    <th>Entrega</th>
                    <th>Foto</th>
                    <th>Ação</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM felipe_pizza LEFT OUTER JOIN felipe_img_pizza ON felipe_pizza.id_sabor = felipe_img_pizza.imagem_pizza ");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->sabor."</td>".
                                 "<td>".$rs->valor."</td>".
                                 "<td>".$rs->taxa."</td>".
                                 "<td>".$rs->entrega."</td>".
                                 "<td><center><img src=" . $rs->caminho_imagem . "/" . $rs->nome_imagem . " width='80' height='80' > ".
                                 "<a href='/projeto01/EntregaOK/upload-imagem-pizza.php'>[Escolher]</a><center></td>".
                                 "<td><center><a href=\"?act=upd&id_sabor=".$rs->id_sabor."\">[Alterar]</a>".
                                 "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
                                 "<a href=\"?act=del&id_sabor=".$rs->id_sabor."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
        </body>
    </html>

