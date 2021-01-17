<?php
namespace App\Controllers;

use App\Controller;
use App\Request;
use App\Session;
use App\DB;
use App\User;

class PostsController extends Controller{
    
    public function __construct(Request $request, Session $session){
        parent::__construct($request, $session);   
        $this->tags=array();           
    }

    public function index(){
        $user=$this->session->get('user');
        $themes=[];
        $tags=[];
        $this->getDB()->getAllDataTable($themes, 'themes');
        $this->render(['user'=>$user, 'themes'=>$themes],'newPost');
    }

    public function newTag(){
        $newtag=filter_input(INPUT_POST,'newTag');
        $theme=filter_input(INPUT_POST,'newTag');
        if($newtag != null || $newtag !=''){
            $this->getDB()->addTag();
        }
    }

    public function getThemeTags():void{
        $theme=$_POST["id"];
        $data=[];
        if($theme != null && $theme!=''){
            $this->getDB()->getTags($theme, $data);
        }        
        $data=json_encode($data);        
        echo $data;
    }

    public function addComment():void{
        $postId=(int)filter_input(INPUT_POST,'postId');
        $coment=filter_input(INPUT_POST,'coment');
        $user=$_SESSION['user'];
        $date=date('Y-m-d');
        $check=[$postId,$coment,$user];
        if(self::checkData($check) == true){
           $add=$this->getDB()->addComent($postId, $coment, $user, $date);
           self::viewPost($postId);
        }       
    }

    public function saveTag():void{
        $t=json_decode($_POST["tag"]);
        $this->session->set("tags", $t);
    }

    public function addPost(){
        $tittle=filter_input(INPUT_POST,'tittle');
        $content=filter_input(INPUT_POST,'content');
        $theme=filter_input(INPUT_POST, 'themeId');         
        $userId=(int)$_COOKIE['userId'];
        $date=date('Y-m-d');
        $data=[$tittle, $content, $theme, $userId, $date];
        if(self::checkData($data) == true){  
            $postId=$this->getDB()->addPost($tittle, $content, $userId, $date);      
            if($postId!= 0){
                if(isset($_SESSION["tags"]) && !empty($_SESSION["tags"])){
                   for($x=0; $x<count($_SESSION["tags"]); $x++){
                    $this->getDB()->insertPostTags($postId, $_SESSION["tags"][$x]);
                    } 
                    $this->session->delete("tags"); 
                }                
                               
            }         
        }header('Location:'.BASE.'user/index');
    }  

    protected function checkData(array $data):bool{
        $result=false;
        for($x=0; $x<count($data); $x++){
            if($data[$x] != null & $data[$x]!= ""){
                $result=true;
            }else{
                $result=false;
            }
        }
        return $result;
    }

    public function viewPostsByThemes(){
        $themeId=(int)filter_input(INPUT_POST,'theme');       
        $posts=$this->getDB()->getPostByThemes($themeId);            
        $this->render(['posts'=>$posts],'viewPostThemes');     
    }

    public function viewPost(?int &$postId=null){
        $dataPost=[];
        $dataPostTags=[];
        $dataComents=[];
        $theme="";
        $id=filter_input(INPUT_POST,'postId');
        if(isset($id) && !empty($id) && $postId == null){
            $postId=$id;
        }
        if($postId){
            $this->getDB()->getPostThemeTags($postId, $dataPostTags, $theme);
            $this->getDB()->getPost($postId, $dataPost);
            $this->getDB()->getComents($postId, $dataComents);
            $this->render(['post'=>$dataPost, 'tags'=>$dataPostTags,'theme'=>$theme,'postComents'=>$dataComents,'user'=>$this->session->get('user')],'viewPost');        
        }
    }  

}

