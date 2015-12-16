<?php

class User {

    const Administrator = 0;
    const Equipo = 1;
    const User = 2;

    private $username;
    private $password;
    private $nombre;
    private $apellido;
    private $email;
    private $roll;
    private $direccion;
    private $id;
    
    public function __construct() {}

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        if (!filter_var($username, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z]{5,}/')))) {
            return false;
        }
        $this->username = $username;
        return true;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return true;
    }

    public function getnombre() {
        return $this->nombre;
    }

    public function setnombre($nombre) {
        $this->nombre = $nombre;
        return true;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
        return true;
    }

    public function getRoll() {
        return $this->roll;
    }

    public function setRoll($roll) {
        switch ($roll) {
            case self::Administrator:
            case self::Equipo:
            case self::User:
                $this->roll = $roll;
                return true;
            default:
                return false;
        }
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $this->email = $email;
        return true;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        if (!filter_var($id, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[0-9]/')))) {
            return false;
        }
        $this->id = $id;
        return true;
    }
    
    public function equals(User $user) {

        return  $this->id == $user->id &&
                $this->nombre == $user->nombre &&
                $this->apellido == $user->apellido &&
                $this->roll == $user->roll;
    }

}

?>
