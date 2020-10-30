<?php
/* 
 * Projeto: BACKEND PHP - PROJETO API REST
 * Autor: Thiago Pontes Soares - thiago.devps@outlook.com - https://www.linkedin.com/in/thiago-pontes-soares-a59646179/
 * Data: 29/10/2020
 * Descricao: Tratamento de rotas, recebimento de Json, chamada das funcoes da DAO e retorno de Json
 * Alterações:
 * Data  |  Descricao
 */

date_default_timezone_set('America/Sao_Paulo');

header('Content-Type: application/json; charset=utf-8');

require_once '../class/user/UserDAO.class.php';

class Rest{
    
    public static function open($requisicao){
        $url = explode('/', $requisicao['url']);

        $classe = isset($url[0])?$url[0]:'';
        $id_user = isset($url[1])?$url[1]:'';
        $metodo = isset($url[2])?$url[2]:'';
        
        $http_verb = $_SERVER['REQUEST_METHOD'];
        
        if($classe == 'login'){
            //login route
            try{
                $data = json_decode(file_get_contents("php://input"));
                $UserDAO = new UserDAO();
                $users = $UserDAO->login($data->email, $data->password);

                $users[0]['drink_counter'] = $UserDAO->countDrink($users[0]['id']);
                
                if (session_status() == PHP_SESSION_ACTIVE) {
                    session_destroy();
                    session_start();
                }else{
                    session_start();

                }
                
                $_SESSION['token'] = $users[0]['id'].'_'.$users[0]['drink_counter'];
                
                $users[0]['token'] = $_SESSION['token'];
                
                return json_encode(array('status' => 'sucesso', 'funcao' => 'login', 'data' => $users));
            } catch (Exception $ex) {
                return json_encode(array('status' => 'erro', 'funcao' => 'login', 'data' => $ex->getMessage()));
            }
        }

        if($classe == 'users'){

            //operations with user
            if($id_user != '' && is_numeric($id_user)){
                
                if($metodo == 'listdrink' && $http_verb == 'GET'){
                    try{
                        $UserDAO = new UserDAO();
                        $users = $UserDAO->listOne($id_user);
                        
                        $list_drink = $UserDAO->listtDrink($id_user);
                        
                        return json_encode(array('status' => 'sucesso', 'funcao' => 'registro de bebibdas', 'data' => $list_drink));
                    } catch (Exception $ex) {
                        return json_encode(array('status' => 'erro', 'funcao' => 'registro de bebibdas', 'data' => $ex->getMessage()));
                    }
                    
                }else if($metodo == 'drink' && $http_verb == 'POST'){
                    //drink user
                    try{
                        $UserDAO = new UserDAO();
                        $users = $UserDAO->listOne($id_user);

                        $data = json_decode(file_get_contents("php://input"));

                        $agora = date('Y-m-d H:i:s');
                        $UserDAO->updateDrink($id_user, $data->drink_ml, $agora);

                        $users = $UserDAO->listOne($id_user);
                        $users[0]['drink_counter'] = $UserDAO->countDrink($id_user);

                        return json_encode(array('status' => 'sucesso', 'funcao' => 'beber', 'data' => $users));
                    } catch (Exception $ex) {
                        return json_encode(array('status' => 'erro', 'funcao' => 'beber', 'data' => $ex->getMessage()));
                    }
                    
                }else if($http_verb == 'GET'){
                    //list user
                    try{
                        $UserDAO = new UserDAO();
                        $users = $UserDAO->listOne($id_user);

                        return json_encode(array('status' => 'sucesso', 'funcao' => 'listar usuario', 'data' => $users));
                    } catch (Exception $ex) {
                        return json_encode(array('status' => 'erro', 'funcao' => 'listar usuario', 'data' => $ex->getMessage()));
                    }

                }else if ($http_verb == 'PUT' && isset($_SESSION['token'])){
                    //edit user
                    try{
                        header('Content-Type: application/json; charset=utf-8; token='.$_SESSION['token'].'');
                        
                        $UserDAO = new UserDAO();
                        $users = $UserDAO->listOne($id_user);
                        
                        $data = json_decode(file_get_contents("php://input"));
                        
                        $User = new User();
                        $User->setId($id_user);
                        $User->setEmail(isset($data->email)?$data->email:$users['email']);
                        
                        if($UserDAO->vldUsuario($User) != 0){
                            throw new Exception("Já existe este e-mail no cadastro");
                        }
                       
                        $User->setName(isset($data->name)?$data->name:$users['name']);
                        $User->setPassword(isset($data->password)?$data->password:$users['password']);
                        $UserDAO = new UserDAO();
                        $UserDAO->update($User);

                        return json_encode(array('status' => 'sucesso', 'funcao' => 'atualizar usuario', 'data' => $data));
                    } catch (Exception $ex) {
                        return json_encode(array('status' => 'erro', 'funcao' => 'atualizar usuario', 'data' => $ex->getMessage()));
                    }
                }else if ($http_verb == 'DELETE' && isset($_SESSION['token'])){
                    //delete user
                    try{
                        header('Content-Type: application/json; charset=utf-8; token='.$_SESSION['token'].'');
                        
                        $UserDAO = new UserDAO();
                        $users = $UserDAO->listOne($id_user);
                        $UserDAO->delete($id_user);
                        return json_encode(array('status' => 'sucesso', 'funcao' => 'deletar usuario', 'data' => $id_user));
                    } catch (Exception $ex) {
                        return json_encode(array('status' => 'erro', 'funcao' => 'deletar usuario', 'data' => $ex->getMessage()));
                    }
                }
                
            }else if($id_user == 'drinkrank' && $http_verb == 'GET'){
                try{
                    $agora = date('Y-m-d');
                    
                    $UserDAO = new UserDAO();
                    $users = $UserDAO->listtDrinkRank($agora);

                    return json_encode(array('status' => 'sucesso', 'funcao' => 'rank de usuario que mais bebeu agua hoje', 'data' => $users));
                } catch (Exception $ex) {
                    return json_encode(array('status' => 'erro', 'funcao' => 'rank de usuario que mais bebeu agua hoje', 'data' => $ex->getMessage()));
                }
            }
            
            
            if($http_verb == 'GET'){
                //list user
                try{
                    $UserDAO = new UserDAO();
                    $users = $UserDAO->listAll();

                    return json_encode(array('status' => 'sucesso', 'funcao' => 'listar usuarios', 'data' => $users));
                } catch (Exception $ex) {
                    return json_encode(array('status' => 'erro', 'funcao' => 'listar usuarios', 'data' => $ex->getMessage()));
                }

            }else if ($http_verb == 'POST'){
                //create user
                $data = json_decode(file_get_contents("php://input"));
                
                try{
                    $User = new User();
                    $User->setName($data->name);
                    $User->setEmail($data->email);
                    $User->setPassword($data->password);
                    
                    $UserDAO = new UserDAO();
                    if($UserDAO->vldUsuario($User) != 0){
                        throw new Exception("Já existe este e-mail no cadastro");
                    }
                    
                    $UserDAO->save($User);

                    return json_encode(array('status' => 'sucesso', 'funcao' => 'criar usuario'));
                } catch (Exception $ex) {
                    return json_encode(array('status' => 'erro', 'funcao' => 'criar usuario', 'data' => $ex->getMessage()));
                }
                
            }

        }
         
    }
}

session_start();

if (isset($_REQUEST)) {
    echo Rest::open($_REQUEST);
}