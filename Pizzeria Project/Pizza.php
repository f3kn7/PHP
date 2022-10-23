<?php

Class Pizza {
    
    private $idSabor = 0;
    private $sabor = "";
    private $valor = 0;
    private $taxa = 0;
    private $entrega = 0;

    
//GETTER =======================================================================   
    public function getIdSabor() {
        return $this->idSabor;
    }

    public function getSabor() {
        return $this->sabor;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getTaxa() {
        return $this->taxa;
    }

    public function getEntrega() {
        return $this->entrega;
    }

//SETTER =======================================================================       
    public function setIdSabor($idSabor): void {
        $this->idSabor = $idSabor;
    }

    public function setSabor($sabor): void {
        $this->sabor = $sabor;
    }

    public function setValor($valor): void {
        $this->valor = $valor;
    }

    public function setTaxa($taxa): void {
        $this->taxa = $taxa;
    }

    public function setEntrega($entrega): void {
        $this->entrega = $entrega;
    }
   
}