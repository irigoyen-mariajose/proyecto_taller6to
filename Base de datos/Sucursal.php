<?php
class Usuario {
    private $id_sucursal;
    private $nombre;
    private $direccion;
    private $hora;
    private $localidad;
    private $codigo_postal;
    private $telefono;
    private $correo;

    // GET Y SET (Observadores y Modificadores)
    public function getid_sucursal() {
        return $this->id_sucursal;
    }

    public function setid_sucursal($id_sucursal) {
        $this->id_sucursal = $id_sucursal;
    }

    public function getnombre() {
        return $this->nombre;
    }

    public function setnombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getdireccion() {
        return $this->direccion;
    }

    public function setdireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function gethora() {
        return $this->hora;
    }

    public function sethora($hora) {
        $this->hora = $hora;
    }

    public function getlocalidad() {
        return $this->localidad;
    }

    public function setlocalidad($localidad) {
        $this->localidad = $localidad;
    }

    public function getcodigo_postal() {
        return $this->codigo_postal;
    }

    public function setcodigo_postal($codigo_postal) {
        $this->codigo_postal = $codigo_postal;
    }

    public function gettelefono() {
        return $this->telefono;
    }

    public function settelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getcorreo() {
        return $this->correo;
    }

    public function setcorreo($correo) {
        $this->correo = $correo;
    }
}
?>
