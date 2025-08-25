<?php
class Usuario {
    private $idUsuario;
    private $nombre;
    private $apellido;
    private $direccion;
    private $correo;
    private $clave;
    private $idCargo;
    private $cargo;
    private $fotoperfil;

    // GET Y SET (Observadores y Modificadores)
    public function getidusuario() {
        return $this->idUsuario;
    }

    public function setidusuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getnombre() {
        return $this->nombre;
    }

    public function setnombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getapellido() {
        return $this->apellido;
    }

    public function setapellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getdireccion() {
        return $this->direccion;
    }

    public function setdireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getcorreo() {
        return $this->correo;
    }

    public function setcorreo($correo) {
        $this->correo = $correo;
    }

    public function getclave() {
        return $this->clave;
    }

    public function setclave($clave) {
        $this->clave = $clave;
    }

    public function getidcargo() {
        return $this->idCargo;
    }

    public function setidcargo($idCargo) {
        $this->idCargo = $idCargo;
    }

    public function getcargo() {
        return $this->cargo;
    }

    public function setcargo($cargo) {
        $this->cargo = $cargo;
    }

    public function getfotoperfil() {
        return $this->fotoperfil;
    }

    public function setfotoperfil($fotoperfil) {
        $this->fotoperfil = $fotoperfil;
    }
}
?>
