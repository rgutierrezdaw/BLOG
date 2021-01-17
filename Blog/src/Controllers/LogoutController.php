<?php
namespace App\Controllers;

use App\Controller;
use App\Request;
use App\Session;


class LogoutController extends Controller{
    
    public function __construct(Request $request, Session $session){
        parent::__construct($request, $session);        
    }

    public function logout(){
        unset ($_SESSION['error']);
        unset ($_SESSION['register']);
        unset($_COOKIE['userId']);
        setcookie('userId',null, time()-60000,'/'); 
        header('Location:'.BASE.'index');  
    }
}
