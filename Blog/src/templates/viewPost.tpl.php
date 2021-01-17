<?php
$tittle="";
$content="";
$postId="";
$tagsList="";
$formComents="";
$coment="";
if(count($post)>0){
    foreach($post as $p){
        $tittle=$p['tittle'];
        $content=$p['content'];
        $postId=$p['postId'];
    }
} else {
    $tittle="Vaya :(";
    $content="Ha ocurrido un error";
}
if(count($tags)>0){
  foreach($tags as $tag){
    $tagsList.="<p class='text-secondary'>/".ucfirst($tag['tag'])."</p>";    
  }  
} else {
    $tagsList="Vaya, parece que ha habido un error";
}

if(isset($_COOKIE["userId"])){
    include 'userHeaderII.tpl.php';
    $formComents="<form id='addComent' action='".BASE."posts/addComment' method='POST'>
                <input type='text' name='coment' placeholder='Comenta'>
                <input type='hidden' name='postId' value='".$postId."' >
                <input type='submit' value='Envia'>
            </form>"; 
} else {
    include 'visitorHeader.tpl.php';
    $formComents="<h4 class='text-danger'>Si quieres comentar regístrate</h4>";
}
if(count($postComents)>0){
    foreach($postComents as $com){
        $coment.="<div class='coment'>
                    <p>".ucfirst($com['user']).": ".$com['coment']."</p>                  
                </div>";
    }
}
?>

<section class="col-md-12 d-flex flex-column align-items-center">
    <div class="d-flex col-md-12">
        <p class="fw-bold text-primary"><?=ucfirst($theme)?></p>
        <?=ucfirst($tagsList)?>
    </div>
    <div class="contentComent col-md-6">
        <h1><?=$tittle?></h1>
        <p><?=$content?></p>
        <div class="coments">
            <?=$formComents?>
            <?=$coment?>
        </div>
        
    </div>   
</section>

<?php
include 'footer.tpl.php';
?>