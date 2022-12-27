<?php

require_once './session.php';
require_once './conn-pdo.php';
require_once './Usuario.php';

$user = new Usuario;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $arquivo = $_FILES["arquivo"];
    $dir = "uploads/";

    $name_arq = $arquivo["name"];
    
    //Movendo o upload do arquivo para o diretório especificado
    if (move_uploaded_file($arquivo['tmp_name'], $dir . $arquivo["name"])) {
        
    } else {
        echo "Erro: Arquivo nao enviado para o diretorio!";
    }

    //Inserindo dados no banco
    try {
        $stmt = $conexao->prepare("INSERT INTO felipe_img_user (imagem_user, nome_arquivo, caminho_arquivo) VALUES (?, ?, ?)");

        $stmt->bindValue(1, $user->setIdUser($_SESSION["id_user"]));
        $stmt->bindValue(2, $name_arq);
        $stmt->bindValue(3, $dir);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Imagem salva com sucesso!')</script>";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: {$erro->getMessage()}";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Imagem - Upload</title>
    </head>
    <body>
        <h1>Cadastrar Imagem</h1>
        <hr>

        <form action="#" method="post" enctype="multipart/form-data">

            <label>Imagem</label>
            <input type="file" name="arquivo"><br><br>

            <input type="submit" value="Enviar">
        </form>
        <br>
        <a href="/projeto01/EntregaOK/crud-usuario.php">Voltar</a>
    </body>
</html>



