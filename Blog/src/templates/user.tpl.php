<?php
include 'userHeader.tpl.php';
$contentPosts="";

$startList="<ul>";
$list="";
$endList="</ul>";
if(count($dataUserPosts)>0){
    foreach($dataUserPosts as $post){
            $list.="<li>         
                    <form class='noform' action=".BASE."posts/viewPost method='POST'>                  
                    <input type='hidden' name='postId' value=".$post["postId"].">
                    <input type=submit value='".$post['tittle']."'></input>
                    </form>
                    </li>";                 
    }
    $content=$startList.$list.$endList;
} else {
        $content= "<h3>¡Vaya!</h3><p>Aún no has creado ninguna entrada, ¿a que esperas?</p>";
  }

?>
<section>
  <h1>Welcome <?=ucfirst($user)?></h1><br>
  <hr width=100% class="primary"/>
</section>
<section class="d-flex d-flex flex-row col-md-12 justify-content-between">   
    <div class="col-md-7 d-flex flex-wrap justify-content-around align-items-between">
      <?php
      if(count($lastsPosts) != 0 ){
        foreach ($lastsPosts as $post){
            echo "<div class='resume border col-md-5'>
                        <p id='tittlePost'>".$post['tittle']." <p id='date'>Publicado el ".$post['date']." per ".ucfirst($post['name'])."</p></p>                            
                        <p>".substr($post['content'],0, 50)."...</p>
                        <form class='noform' action=".BASE."posts/viewPost method='POST'>                  
                            <input type='hidden' name='postId' value=".$post["postId"].">
                            <input type=submit value='+Ver más'></input>
                        </form>
                    </div>";
          }
      } else {
          echo " <p id='tittlePost'>Todavía no habéis publicado contenido.¿A que esperáis?</p> ";
      }
      ?>
    </div>
    <div class="col-md-4">
      <h1>Tus posts:</h1>
          <?=$content?>
    </div>    
</section>



<?php
include 'footer.tpl.php';
?>