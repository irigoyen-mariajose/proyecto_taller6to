<?php
class Productos {
    private $idProductos;
    private $Nombre;
    private $Stock;
    private $Descripcion;
    private $Precio; 
    private $Marca;
    private $idCategoria;
    private $Nomcategoria;

     public function getProductos() {
        return $this->idProductos;
    }

    public function setidProductos($idProductos) {
        $this->idProductos = $idProductos;
    }
     public function getNombre() {
        return $this->  Nombre;
    }

    public function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }
     public function getStock() {
        return $this-> Stock;
    }
    public function setStock($Stock) {
        $this->Stock= $Stock;
    }
     public function getDescripcion() {
        return $this-> Descripcion;
    }
    public function setDescripcion($Descripcion) {
        $this->Descripcion= $Descripcion;
    }
     public function getPrecio() {
        return $this-> Precio;
    }
    public function setPrecio($Precio) {
        $this->Precio= $Precio;
    }
     public function getMarca() {
        return $this-> Marca;
    }
    public function setMarca($Marca) {
        $this->Marca= $Marca;
    }
     public function getCategoria() {
        return $this->idCategoria;
    }

    public function setidCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }
      public function getNomcategoria() {
        return $this->  Nomcategoria;
    }

    public function setNomcategoria($Nomcategoria) {
        $this->Nomcategoria = $Nomcategoria;
    }
}