<?php

Class Usuario {

    private $idUser = 0;
    private $nome = "";
    private $email = "";
    private $senha = "";
    private $acesso = 0;

// GETTER ======================================================================

    public function getIdUser() {
        return $this->idUser;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getAcesso() {
        return $this->acesso;
    }

// SETTER ======================================================================
    public function setIdUser($idUser): void {
        $this->idUser = $idUser;
    }

    public function setNome($nome): void {
        $this->nome = $nome;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setSenha($senha): void {
        $this->senha = $senha;
    }

    public function setAcesso($acesso): void {
        $this->acesso = $acesso;
    }

//método =======================================================================   

    public function Logar($email, $senha) {

        $this->email = $email;
        $this->senha = $senha;

        global $conexao;

        $sql = $conexao->prepare("SELECT * FROM felipe_usuario WHERE email = :email AND senha = :senha ");

        $sql->bindValue(":email", $this->email);
        $sql->bindValue(":senha", $this->senha);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dado = $sql->fetch(PDO::FETCH_ASSOC);

            $_SESSION["id_user"] = $this->idUser = $dado["id_usuario"];
            $_SESSION["nome_user"] = $this->nome = $dado["nome"];
            $_SESSION["nivel"] = $this->acesso = $dado["acesso"];

            if ($this->acesso == 1) {

                return 1;
            }

            if ($this->acesso == 0) {

                return 0;
            }
         } elseif (empty($this->email) || empty($this->senha)) {
            $this->idUser = null;
            $this->nome = null;
            $this->acesso = null;
            
            return 2;
        } else {
            $this->idUser = null;
            $this->nome = null;
            $this->acesso = null;
               
            return 3;
        }
    }

}
