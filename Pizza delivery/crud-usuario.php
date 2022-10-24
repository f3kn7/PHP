<?php

require_once './session.php';
// Cria a conexão com o banco de dados
require_once './conn-pdo.php';

//negando acesso do conteúdo ao usuario não logado
//if (!$logado) {
// die('Você não tem permissão para acessar esse conteúdo!');
//}

require_once './Usuario.php';

$user = new Usuario;

if (isset($_POST["sair"])) {
    session_unset();
    session_destroy();
}

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->setIdUser((isset($_POST["id_usuario"]) && $_POST["id_usuario"] != null) ? $_POST["id_usuario"] : "");
    $user->setNome((isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "");
    $user->setEmail((isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "");
    $user->setSenha((isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "");
    $user->setAcesso((isset($_POST["acesso"]) && $_POST["acesso"] != null) ? $_POST["acesso"] : "");

   // Se não se não foi setado nenhum valor para variável $id_usuario   
} else if (!isset($id_usuario)) {
    $user->setIdUser((isset($_GET["id_usuario"]) && $_GET["id_usuario"] != null) ? $_GET["id_usuario"] : "");
    $user->setNome(null);
    $user->setEmail(null);
    $user->setSenha(null);
    $user->setAcesso(null);
}

// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $user->getNome() != "") {
    try {
        if ($user->getIdUser() != "") {

            $stmt = $conexao->prepare("UPDATE felipe_usuario SET nome=?, email=?, senha=?, acesso=? WHERE id_usuario = ?");
            $stmt->bindValue(5, $user->getIdUser(), PDO::PARAM_INT);
        } else {
            $stmt = $conexao->prepare("INSERT INTO felipe_usuario (nome, email, senha, acesso) VALUES (?, ?, ?, ?)");
        }
        $stmt->bindValue(1, $user->getNome());
        $stmt->bindValue(2, $user->getEmail());
        $stmt->bindValue(3, $user->getSenha());
        $stmt->bindValue(4, $user->getAcesso());

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Dados salvos com sucesso!')</script>";
                $user->setIdUser(null);
                $user->setNome(null);
                $user->setEmail(null);
                $user->setSenha(null);
                $user->setAcesso(null);
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $user->getIdUser() != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM felipe_usuario WHERE id_usuario = ?");
        $stmt->bindValue(1, $user->getIdUser(), PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $user->setIdUser($rs->id_usuario);
            $user->setNome($rs->nome);
            $user->setEmail($rs->email);
            $user->setSenha($rs->senha);
            $user->setAcesso($rs->acesso);
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "<script>alert('Erro: {$erro->getMessage()}')</script>";
    }
}

// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $user->getIdUser() != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM felipe_usuario WHERE id_usuario = ?");
        $stmt->bindValue(1, $user->getIdUser(), PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "<script>alert('Registo foi excluído com êxito!')</script>";
            $user->setIdUser(null);
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
        <title>Cadastro de usuários</title>
    </head>
    <body>
        <h1>Cadastro de usuários</h1>

        <form action ="login.php" method ="post"><button type=submit name="sair" style="float: right;"> Sair </button></form>

        <form action ="index-administrador.php" method ="post"><button type=submit name="voltar" style="float: right;"> Voltar </button></form> 

        <form action="?act=save" method="POST" name="form1" >

            <hr>
            <input type="hidden" name="id_usuario" <?php
            // Preenche o id no campo id com um valor "value"
            if ($user->getIdUser() != null || $user->getIdUser() != "") {
                echo "value=\"{$user->getIdUser()}\"";
            }
            ?> />
            Nome:
            <input type="text" name="nome" <?php
            // Preenche o nome no campo nome com um valor "value"
            if ($user->getNome() != null || $user->getNome() != "") {
                echo "value=\"{$user->getNome()}\"";
            }
            ?> />
            E-mail:
            <input type="text" name="email" <?php
            // Preenche o email no campo email com um valor "value"
            if ($user->getEmail() != null || $user->getEmail() != "") {
                echo "value=\"{$user->getEmail()}\"";
            }
            ?> />
            Senha:
            <input type="text" name="senha" <?php
            // Preenche a senha no campo senha com um valor "value"
            if ($user->getSenha() != null || $user->getSenha() != "") {
                echo "value=\"{$user->getSenha()}\"";
            }
            ?> />
            Acesso:
            <input type="text" name="acesso" <?php
            // Preenche o acesso no campo acesso com um valor "value"
            if ($user->getAcesso() != null || $user->getAcesso() != "") {
                echo "value=\"{$user->getAcesso()}\"";
            }
            ?> />
            <input type="submit" value="salvar" />
            <input type="reset" value="Novo" />
            <hr>
        </form>
        <table border="1" width="100%">
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Senha</th>
                <th>Acesso</th>
                <th>Foto</th>
                <th>Ação</th>
            </tr>
            <?php
            // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
            try {
                $stmt = $conexao->prepare("SELECT * FROM felipe_usuario LEFT OUTER JOIN felipe_img_user ON felipe_usuario.id_usuario = felipe_img_user.imagem_user ");

                if ($stmt->execute()) {
                    while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                        echo "<tr>";
                        echo "<td>" . $rs->nome . "</td>" .
                        "<td>" . $rs->email . "</td>" .
                        "<td>" . $rs->senha . "</td>" .
                        "<td>" . $rs->acesso . "</td>" .
                        "<td><center><img src=" . $rs->caminho_arquivo . "/" . $rs->nome_arquivo . " width='80' height='80' > " .
                        "<a href='/projeto01/EntregaOK/upload-imagem-user.php'>[Escolher]</a><center></td>" .
                        "<td><center><a href=\"?act=upd&id_usuario=" . $rs->id_usuario . "\">[Alterar]</a>" .
                        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
                        "<a href=\"?act=del&id_usuario=" . $rs->id_usuario . "\">[Excluir]</a></center></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "Erro: Não foi possível recuperar os dados do banco de dados";
                }
            } catch (PDOException $erro) {
                echo "Erro: " . $erro->getMessage();
            }
            ?>
        </table>
    </body>
</html>