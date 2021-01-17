<?php
include 'indexHeader.tpl.php';

$contentPosts="";
$tittleThemes="";

if(count($posts) != 0 ){
    foreach ($posts as $post){
        $contentPosts.="<div class='resume border col-md-4'>
                    <p id='tittlePost'>".$post['tittle']." <p id='date'>Publicado el ".$post['date']." por ".ucfirst($post['name'])."</p></p>                            
                    <p>".substr($post['content'],0, 50)."...</p>
                    <form class='seePost' action=".BASE."posts/viewPost method='POST'>                  
                        <input type='hidden' name='postId' value=".$post["postId"].">
                        <input type=submit value='+Ver más'></input>
                    </form>
                </div>";
    }
} else {
    $contentPosts="";
}
if(count($themes) != 0){
    foreach($themes as $theme){
        $tittleThemes.="<ul>
                            <li>
                                <form class='noform' action='".BASE."posts/viewPostsByThemes' method='POST'>
                                    <input type='hidden' value=".$theme['themeId']." name='theme'>
                                    <input type='submit' value=".ucfirst($theme['theme']).">
                                </form>
                            </li>
                        </ul>";
    }
}
?>
<section class="d-flex d-flex flex-row col-md-12 justify-content-between">
    <div class="d-flex flex-column col-md-8 border-right border-info">
        <h1>Últimos posts</h1>
        <div class="d-flex flex-wrap ">         
            <div class="col-md-12"> 
                <?=$contentPosts;?>
            </div>            
        </div>
    </div>
    <div class="d-flex flex-column col-md-4">
        <h1>Temas</h1>
        <?=$tittleThemes?>
    </div>

</section>

<?php
include 'footer.tpl.php';
?>