<?php
if(isset($_COOKIE["userId"])){
    include 'userHeaderII.tpl.php';  
} else {
    include 'visitorHeader.tpl.php';
}
$contentPosts="";
$theme="";
if(count($posts)>0){
  foreach ($posts as $post){
    $contentPosts.="<div class='card border-dark mb-3' style='max-width: 18rem;'>
                        <div class='card-header'><h2>".$post['tittle']."</h2></div>
                        <div class='card-body text-dark'>
                        <h5 class='card-title'>Publicado el ".$post['date']." por ".ucfirst($post['name'])."</h5>
                        <p class='card-text'>".substr($post['content'],0, 50)."...</p>
                        <form class='noform' action=".BASE."posts/viewPost method='POST'>                  
                            <input type='hidden' name='postId' value=".$post["postId"].">
                            <input type=submit value='+Ver más'></input>
                        </form>
                    </div>"; 
    $theme=$post['theme'];                     
    }  
} else {
    $contentPosts="<h1>No hay posts sobre este tema</h1>";
}
?>
<section class="d-flex flex-wrap">
<h1>Posts de <?=$theme?></h1>
        <div class="d-flex col-md-12 ">         
            <div class="col-sm-12"> 
                <?=$contentPosts;?>
            </div>            
        </div>
</section>

