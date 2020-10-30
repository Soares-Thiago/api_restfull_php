<?php
/* 
 * Projeto: BACKEND PHP - PROJETO API REST
 * Autor: Thiago Pontes Soares - thiago.devps@outlook.com - https://www.linkedin.com/in/thiago-pontes-soares-a59646179/
 * Data: 29/10/2020
 * Descricao: Classe de usuario
 * Alterações:
 * Data  |  Descricao
 */
class User{
    private $id = 0;
    private $name = "";
    private $email = 0;
    private $password = "";
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($password) {
        $this->password = $password;
    }
    
    public function mostrar(){
        $array = array();
        
        $array = [
                    'nome'=>'aaaaaa',
                    'preco'=>'2.50'
                 ];

        return $array;
    }


}


?>