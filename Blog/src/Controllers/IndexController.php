<?php
namespace App\Controllers;

use App\Request;
use App\Controller;
use App\Session;

final class IndexController extends Controller{

    public function __construct(Request $request, Session $session){
        parent::__construct($request, $session);
    }

    public function index(){ 
        $posts=[];
        $themes=[];
        $this->getDB()->getLasts5($posts);
        $this->getDB()->getThemes($themes);
        $this->render(['posts'=>$posts,'themes'=>$themes, 'index' ]);
    }

}
/*db5001159986.hosting-data.io */