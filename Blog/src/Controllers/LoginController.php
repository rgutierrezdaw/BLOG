<?php
namespace App\Controllers;

use App\Controller;
use App\Request;
use App\Session;


class LoginController extends Controller{
    
    public function __construct(Request $request, Session $session){
        parent::__construct($request, $session);        
    }

    public function index(){ 
        $form=self::getForm();     
        $dataview=['form'=>$form]; 
        $this->render($dataview);
    }

    public function checkUser():bool{
        if(!isset($_COOKIE['user']) && !isset($_COOKIE['pwd'])){
            return false;   
        }else{
            return true; 
        }

    }

    public function getForm():String{
        $form="";
        if (self::checkUser()==true){
            $form="<form action='".BASE."user/login' method='POST'>
                        <h3>Inicia sesión</h3>
                        <div class='form-group'>
                            <label for='username'>User name</label>
                            <input type='text' class='form-control' name='username' value='".$_COOKIE['user']."' required>             
                        </div>
                        <div class='form-group'>
                            <label for='password'>Password</label>
                            <input type='password' class='form-control' name='password' value='".$_COOKIE['pwd']."' required>
                        </div>
                        <p class='font-weight-bold'>Última connexión realizada el: </p>  
                        <p>".$_COOKIE['lastconnection']."</p>                     
                        <input type='submit' class='btn btn-primary' value='Inicia sesión'> <br>
                        <a href='".BASE."user/change'>Canvia de compte</a>              
                    </form>";
                   
        } else {
            $form = "<form action='".BASE."user/login' method='POST'>
                        <h3>Inicia sesión</h3>
                        <div class='form-group'>
                            <label for='username'>User name</label>
                            <input type='text' class='form-control' name='username' placeholder='Nombre de ususario' required>             
                        </div>
                        <div class='form-group'>
                            <label for='password'>Password</label>
                            <input type='password' class='form-control' name='password' placeholder='Introduce la contraseña' required>
                        </div>
                        <input type='checkbox' name='rememberUser'>
                        <label for='rememberUser'>Recordar usuario en este equipo</label><br>
                        <input type='submit' class='btn btn-primary' value='Iniciar sesión'><br>             
                </form>";
        }
        return $form;
    }
}





