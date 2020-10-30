<?php
/* 
 * Projeto: BACKEND PHP - PROJETO API REST
 * Autor: Thiago Pontes Soares - thiago.devps@outlook.com - https://www.linkedin.com/in/thiago-pontes-soares-a59646179/
 * Data: 29/10/2020
 * Descricao: Classe para conexao com BD
 * Alterações:
 * Data  |  Descricao
 */
abstract class database{
    private function __construct(){}
     
    private function __clone(){}
     
    public function __destruct() {
        $this->disconnect();
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }
     
    public function connect(){
        $dsn = 'mysql:dbname=stautrh_apirest;host=localhost';
        $user = 'root';
        $password = '';

        try {
            $this->conexao = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return ($this->conexao);
    }
     
    private function disconnect(){
        $this->conexao = null;
    }
     
   
}
?>