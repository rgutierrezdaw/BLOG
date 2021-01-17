<?php

namespace App\Controllers;
use App\Controller;

use App\View;
use App\Model;
use App\Request;
use App\Session;
use App\DB;

class UserController extends Controller implements View,Model{

    public function __construct(Request $request, Session $session){
        parent::__construct($request, $session);     
    }

    public function Index(){
        $user=$this->session->get('user');
        $dataUserPost=[]; 
        $dataLastsPosts=[];       
        $this->getDB()->getUserPosts((int)$_COOKIE['userId'], $dataUserPost);
        $this->getDB()->getLasts5($dataLastsPosts);       
        $this->render(['user'=>$user,'dataUserPosts'=>$dataUserPost,'lastsPosts'=>$dataLastsPosts,'user']);       
    }

    public function goRegister(){
        $this->render(null,'register');
    }
    
    public function change(){   
            self::deleteCookies ();
            header('Location:'.BASE.'index/index');        
    }

    public function login(){         
        if(filter_input(INPUT_POST, 'username') != null && filter_input(INPUT_POST, 'password') != null){
            $user=filter_input(INPUT_POST, 'username');
            $pwd=filter_input(INPUT_POST, 'password');         
            if($this->getDB()->auth($user, $pwd) == true){
                $this->session->set('user', $user);
                if(filter_input(INPUT_POST,"rememberUser") != NULL){
                    //$passwd=password_hash($pwd, PASSWORD_BCRYPT, ["cost"=>4]);
                    self::saveUser($user, $pwd);                                                  
                } 
                header('Location:'.BASE.'user/index');                                          
            }else {
                $this->session->set('error', 'Usuari o contrasenya incorrectes.');
                header('Location:'.BASE.'index/index');}
        }
    }

    public function register(){
        $data=[];
        $newuser=filter_input(INPUT_POST,'newuser');
        $userMail=filter_input(INPUT_POST,'mail');
        $newpwd=filter_input(INPUT_POST,'newpwd');        
        if($this->getDB()->checkUserName($newuser) == false){
            $pwd=password_hash($newpwd, PASSWORD_BCRYPT, ["cost"=>4]);
            $data=["username"=>$newuser, "mail"=>$userMail, "password"=>$pwd];                  
            $register=$this->getDB()->newUser($data);
                if($register == true){ 
                    $this->session->set('register', 'Registre completat.<br>Ja pots iniciar sessió!');
                    $this->session->delete('registerError');
                    header('location:'.BASE.'login/index');
                } else {
                    $this->session->set('register', 'Error al registrar-se.');
                    self::goRegister();
                }            
        } else {
            $this->session->set('registerError', 'Aquest nickname no està disponible, prova amb un altre.');
            self::goRegister();
        }
    }
    private function saveUser($user, $pwd){
        $date=date("d/m/y , g:i a");           
        setcookie('lastconnection', $date, time()+5000,'/');
        setcookie('user', $user, time()+5000,'/');
        setcookie('pwd', $pwd, time()+5000,'/');   
    }

    private function deleteCookies(){
        if(isset($_COOKIE['user']) && isset($_COOKIE['pwd']) && isset($_COOKIE['pwd'])){
            setcookie('lastconnection',null, time()-6000,'/');
            setcookie('user',null, time()-6000,'/');
            setcookie('pwd',null, time()-6000,'/');   
            setcookie('userId',null, time()-6000,'/');  
                   
        }
    }    
}