<?php
namespace App;

class DB extends \PDO{
    static $instance;
    protected  $config;

    public function __construct(){
        parent::__construct(DSN,USR,PWD);
    }
    
    static function singleton(){
        if(!(self::$instance instanceof self)){
            self::$instance=new self();
        }
        return self::$instance;
    }

    public function auth(String $uname, String $pass):bool{      
        try{  
            $stmt=self::singleton()->prepare('SELECT * FROM users WHERE name=:uname LIMIT 1');
            $stmt->execute([':uname'=>$uname]);
            $count=$stmt->rowCount();
            $row=$stmt->fetchAll(\PDO::FETCH_ASSOC);  
            if($count==1){       
                $user=$row[0];
                $res=password_verify($pass,$user['password']);                             
                if ($res){
                    $id=$user['userId'];                   
                    setcookie('userId', $id, time()+36000,'/');          
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }catch(PDOException $e){
                return false;
            }
    }

    public function checkUserName(string $name): bool{
        $stmt=self::singleton()->prepare("SELECT 'name' FROM users WHERE name = :name LIMIT 1;");
        $stmt->execute([':name'=> $name]);
        $count=$stmt->rowCount();       
        if($count == 1){           
           return true; 
        } else {
           return false; 
        }
    }

    public function newUser( array $data): bool{
        if($data){                     
            $newuser=$data["username"];
            $mail=$data["mail"];            
            $newpwd=$data["password"];         
            $stmt=self::singleton()->prepare("INSERT INTO users (name, mail, password) VALUES (:name, :mail, :password)");
            if($stmt->execute([':name'=> $newuser, ':mail'=>$mail ,':password'=> $newpwd])){
                return true;
                            
            }else{
                return false;               
            }
        } else {
            return false;
            
        }
    }

    public function getUserPosts(int $userId, array &$dataPost):array{       
        $db=self::singleton();
        $stmt=$db->prepare("SELECT * FROM posts WHERE userId = :userId");
        $stmt->execute([':userId'=>$userId]);
        $count=$stmt->rowCount();      
        $dataPost=$stmt->fetchAll(\PDO::FETCH_ASSOC);    
            if($count){       
                return $dataPost;
            }else{
                return $dataPost;
            }    
    }

    public function getPost(int $postId, array &$dataPost): array{
        $db=self::singleton();
        $stmt=$db->prepare("SELECT * FROM posts WHERE postId = :postId");
        $stmt->execute([':postId'=>$postId]);
        $count=$stmt->rowCount();      
        $dataPost=$stmt->fetchAll(\PDO::FETCH_ASSOC);    
            if($count){       
                return $dataPost;
            }else{
                return $dataPost;
            }  
    }

    public function getThemes(array &$themes):array{
       return self::getAllDataTable($themes, "themes");
       
    }

    public function getPostThemeTags(int $postId, array &$dataTags, String &$theme){
        $db=self::singleton();
        $stmt=$db->prepare("SELECT * FROM posts_tags WHERE postId = :postId");
        $stmt->execute([':postId'=>$postId]);
        $count=$stmt->rowCount();      
        $data=$stmt->fetchAll(\PDO::FETCH_ASSOC);    
            if($count){ 
                foreach($data as $tag){
                    $stmt=$db->prepare("SELECT tags.tag, tags.tagId FROM posts INNER JOIN posts_tags ON posts.postId=posts_tags.postId INNER JOIN tags ON tags.tagId=posts_tags.tagId WHERE posts.postId= :postId;");
                    $stmt->execute([':postId'=>$postId]);
                    $dataTags+=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $tagId=(int)$tag['tagId'];    
                } 
                $theme=self::getThemeByTag($tagId);               
            }
    }

    protected function getThemeByTag(int $tagId): String{
        $theme="";
        $db=self::singleton();
        $stmt=$db->prepare("SELECT themes.theme from themes INNER JOIN tags on themes.themeId=tags.theme where tags.tagId=:tagId");
        $stmt->execute([':tagId'=>(int)$tagId]);
        $t=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($t as $x){
            $theme=$x["theme"];
        }
        return $theme;
    }

    public function getPostByThemes(int $themeId):array{
        $db=self::singleton();
        $stmt=$db->prepare("SELECT posts.tittle, posts.content, posts.postId, posts.date, tags.tag, themes.theme, users.name FROM posts_tags INNER JOIN posts ON posts.postId=posts_tags.postId INNER JOIN users ON users.userId=posts.userId INNER JOIN tags on posts_tags.tagId=tags.tagId INNER JOIN themes ON tags.theme=themes.themeId where themes.themeId=:themeId;
        ");
        $stmt->execute([':themeId'=>$themeId]);
        $posts=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $posts;
    }

    public function getComents(int $postId, array &$coments){
        $stmt=self::singleton()->prepare("SELECT posts.tittle, coments.date, coments.coment, coments.user FROM coments inner join posts on coments.postId=posts.postId WHERE coments.postId=:postId;");
        if($stmt->execute([':postId'=>$postId])){
            $coments=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
    public function addComent(int $postId, String $coment, String $user, $date){
        $stmt=self::singleton()->prepare("INSERT INTO coments (postId, user, date, coment) VALUES(?, ?, ?, ?);");        
        $stmt->bindParam(1, $postId);
        $stmt->bindParam(2, $user);
        $stmt->bindParam(3, $date);
        $stmt->bindParam(4, $coment);
        $stmt->execute();
    }

    public function getLasts5(array &$data): array{
        $db=self::singleton();
        $stmt=$db->prepare("SELECT users.name, posts.postId, posts.tittle, posts.content, posts.likes, posts.date FROM posts INNER JOIN users ON posts.userId=users.userId; limit 5");
        $stmt->execute();
        $count=$stmt->rowCount();      
        $data=$stmt->fetchAll(\PDO::FETCH_ASSOC);    
        if($count){       
           return $data;
        }else{
            return $data;
        }  
    }

    public function getAllDataTable(array &$data, $table){
        $db=self::singleton();
        $stmt=$db->prepare("SELECT * FROM ".$table.";");
        $stmt->execute();
        $count=$stmt->rowCount();      
        $data=$stmt->fetchAll(\PDO::FETCH_ASSOC);    
        if($count){       
           return $data;
        }else{
            return $data;
        }  
    }

    public function insertPostTags(int $post, int $tag):bool{
        $stmt=self::singleton()->prepare("INSERT INTO posts_tags (postId, tagId) VALUES(?, ?)");
        $stmt->bindParam(1, $post);
        $stmt->bindParam(2, $tag);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function addTag(String $tag, int $themeId){
        $stmt=self::singleton()->prepare("INSERT INTO tags (tag, theme) VALUES(?, ?)");
        $stmt->bindParam(1, $tag);
        $stmt->bindParam(2, $themeId);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function getTags(int $themeId, array &$data):array{
        $db=self::singleton();   
        $stmt=$db->prepare("SELECT * FROM tags WHERE theme = :themeId");
        $stmt->execute([':themeId'=>$themeId]);
        $count=$stmt->rowCount();      
        $data=$stmt->fetchAll(\PDO::FETCH_ASSOC);    
        if($count){       
           return $data;
        }else{
            return $data;
        }  
        
    }
    public function addPost(String $tittle, String $content, int $userId, $date): int{
        $stmt=self::singleton()->prepare("INSERT INTO posts (tittle, content, userId, date) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $tittle);
        $stmt->bindParam(2, $content);
        $stmt->bindParam(3, $userId);
        $stmt->bindParam(4, $date);
        if($stmt->execute()){
            $db=self::singleton();
            $id=$db->lastInsertId();
           return $id;          
        }else {
            return 0;
        }
    }
   
}


/*
CREATE TABLE `users` (
	`userId` INT NOT NULL AUTO_INCREMENT UNIQUE,
	`name` varchar(20) NOT NULL UNIQUE,
	`mail` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	PRIMARY KEY (`userId`)
);

CREATE TABLE `post` (
	`postId` INT NOT NULL AUTO_INCREMENT,
	`tittle` varchar(50) NOT NULL,
	`content` varchar(600) NOT NULL,
	`multimedia` varchar(255),
	`likes` FLOAT,
	`userId` INT NOT NULL,
	PRIMARY KEY (`postId`)
);

CREATE TABLE `coments` (
	`comentId` INT NOT NULL AUTO_INCREMENT,
	`postId` INT NOT NULL,
	`userId` INT NOT NULL,
	`coment` varchar(255) NOT NULL,
	PRIMARY KEY (`comentId`)
);

CREATE TABLE `tags` (
	`tagId` INT NOT NULL AUTO_INCREMENT,
	`tag` varchar(255) NOT NULL,
	`postId` INT NOT NULL,
	PRIMARY KEY (`tagId`)
);

CREATE TABLE `topic` (
	`topicId` INT NOT NULL AUTO_INCREMENT,
	`topic` varchar(100) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`topicId`)
);

ALTER TABLE `post` ADD CONSTRAINT `post_fk0` FOREIGN KEY (`userId`) REFERENCES `users`(`userId`);

ALTER TABLE `coments` ADD CONSTRAINT `coments_fk0` FOREIGN KEY (`postId`) REFERENCES `post`(`postId`);

ALTER TABLE `coments` ADD CONSTRAINT `coments_fk1` FOREIGN KEY (`userId`) REFERENCES `users`(`userId`);

ALTER TABLE `tags` ADD CONSTRAINT `tags_fk0` FOREIGN KEY (`postId`) REFERENCES `post`(`postId`);



 */