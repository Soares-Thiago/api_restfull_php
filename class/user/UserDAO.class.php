<?php
/* 
 * Projeto: BACKEND PHP - PROJETO API REST
 * Autor: Thiago Pontes Soares - thiago.devps@outlook.com - https://www.linkedin.com/in/thiago-pontes-soares-a59646179/
 * Data: 29/10/2020
 * Descricao: Classe de funcoes de usuario
 * Alterações:
 * Data  |  Descricao
 */
require_once ("User.class.php");
require_once ("conexao.php");

class UserDAO extends database {
    protected $cnx         = null;
    protected $nome_tabela = "user"; 
    
    public function __construct() {
        try{
            $this->cnx = database::connect();
            
        }catch(Exception $e){
            print "Erro ao conectar com base de dados";
        }
    }
    /*
     * Salvar usuario no BD
     * @usuario, obj de usuario
     */
    public function save(User $usuario){
        $sql = "INSERT INTO users
                   (name,
                    email,
                    password
                    )
                VALUES
                   ('".$usuario->getName()."', 
                    '".$usuario->getEmail()."',
                    '".$usuario->getPassword()."')";

        $stmt = $this->cnx->prepare($sql);
        //print $sql;
        $result = $stmt->execute();
        if(!$result){
            throw new Exception("Ocorreu um erro ao inserir o registro em usuario!");
        }
    }
    
    /*
     * Listar usuarios
     * return, array de usuarios
     */
    public function listAll(){
        $sql = "SELECT id,name,email FROM users";
        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();
        
        $resultados = array();

        while($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $rs;
        }
        
        if(empty($resultados)){
            throw new Exception("Nenhum usuario encontrado!");
        }
        
        return $resultados;
    }
    
    /*
     * Listar usuario
     * @id_user, id de usuario
     * return, array de usuario
     */
    public function listOne($id_user){
        $sql = "SELECT id,name,email FROM users WHERE id = $id_user";
        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();
        
        $resultados = array();

        while($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $rs;
        }
        
        if(empty($resultados)){
            throw new Exception("usuario nao encontrado!");
        }
        
        return $resultados;
    }
    
    /*
     * Login
     * @email, email de usuario
     * @password, senha de usuario
     * return, array de usuario
     */
    public function login($email, $password){
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        
        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();
        
        $resultados = array();

        while($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $rs;
        }
        
        if(empty($resultados)){
            throw new Exception("E-mail ou senha incorretas!");
        }
        
        return $resultados;
    }
    
    /*
     * Deletar usuario
     * @usuario_id, id de usuario
     */
    public function delete($usuario_id){
        $sql = "DELETE FROM users
                WHERE
                    id = $usuario_id";

         $stmt = $this->cnx->prepare($sql);
         $result = $stmt->execute();

         if(!$result){
             throw new Exception("Ocorreu um erro ao deletar o registro em usuario!");
         }
    }
    
    /*
     * Atualizar usuario
     * @usuario, Obj de usuario
     */
    public function update(User $usuario){
        $sql = "UPDATE users SET 
                    name = '".$usuario->getName()."',
                    email = '".$usuario->getEmail()."',
                    password = '".$usuario->getPassword()."'
                WHERE
                    id = '".$usuario->getId()."'";

        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();

        if(!$result){
            throw new Exception("Erro ao atualizar usuario.");
        }
    }
    
    /*
     * Beber
     * @usuario_id, id de usuario
     * @ml_water, ml de agua
     * @date, data da operacao
     */
    public function updateDrink($usuario_id, $ml_water, $date){
        $sql = "INSERT INTO drink
                   (user_id,
                    ml_water,
                    date
                    )
                VALUES
                   ('".$usuario_id."', 
                    '".$ml_water."',
                    '".$date."')";

        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();

        if(!$result){
             throw new Exception("Erro ao beber agua.");
        }
    }
    
    /*
     * Contar registro de bebidas
     * @usuario_id, id de usuario
     * return, qte de vezes que o usuario bebeu
    */
    public function countDrink($usuario_id){
        $sql = "SELECT count(id) as drink_counter FROM drink WHERE user_id = $usuario_id";

        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();
        
        $resultados = array();

        while($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $rs;
        }
        
        if(empty($resultados)){
            return 0;
        }
        
        return $resultados[0]['drink_counter'];
    }
    
    /*
     * Listar registro de bebidas
     * @usuario_id, id de usuario
     * return, lista de agua que o usuario bebeu
    */
    public function listtDrink($usuario_id){
        $sql = "SELECT * FROM drink WHERE user_id = $usuario_id";

        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();
        
        $resultados = array();

        while($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $rs;
        }
        
        if(empty($resultados)){
            return "este usuario nao bebeu agua ainda";
        }
        
        return $resultados;
    }
    
    /*
     * Listar usuario que mais bebeu agua hoje
     * data, data de hoje
     * return, usuario que mais bebeu agua hoje
    */
    public function listtDrinkRank($data){
        $sql = "SELECT 
                    u.name, (SUM(d.ml_water)) as total_ml_water
                FROM
                    drink d
                    INNER JOIN users u ON (u.id = d.user_id)
                WHERE
                    DATE_FORMAT(d.date,'%Y-%m-%d') = '$data'
                group by d.user_id
                order by total_ml_water DESC
                limit 1;";

        $stmt = $this->cnx->prepare($sql);
        $result = $stmt->execute();
        
        $resultados = array();

        while($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $rs;
        }
        
        if(empty($resultados)){
            return "Ninguem bebeu agua hoje ainda";
        }
        
        return $resultados;
    }
    
    /*
     * Verificacao de email ja existente
     * @usuario, Obj de usuario
     * return, 0 -> nao existe o email || 1 -> existe o e-mail
    */
    public function vldUsuario(User $usuario) {
        try {
            $sql = "SELECT
                        id,
                        name,
                        email
                    FROM users
                    WHERE ";
            
            if ($usuario->getID() > 0) {
                $sql .= " id <> '".$usuario->getId()."' AND ";
            }
            
            if ($usuario->getEmail() != "") {
                $sql .= "( UPPER(email) = '".strtoupper($usuario->getEmail())."' ) ";
            }
            $stmt = $this->cnx->prepare($sql);
            $result = $stmt->execute();
            
            $linhas = $stmt->rowCount();

            if($linhas <= 0) {
                return 0;
            }
            
            if ($result) {
               return 1;
            }
            
            
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro no ao validar dados de usuarios do sistema");
        }
    }
}

