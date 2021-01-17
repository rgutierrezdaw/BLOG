<?php
include 'userHeaderII.tpl.php';
?>
<script>var tagsId=[];</script>
<section>

<div class="col-sm-12 d-flex justify-content-center">
        <form class="d-flex flex-column col-md-8" name="newPost" action="<?=BASE;?>posts/addPost" method="POST" >
            <div class="form-group">
                <label for="task">Título:</label>
                <input type="text" name="tittle" placeholder="Añadir título" required>
            </div>           
            <div class="form-group d-flex flex-column">
                <label>Contenido:</label>
                <textarea type="text" name="content" placeholder="¿Que quieres contar?" maxlength="1500"></textarea>
            </div>   
            <div class="form-group">
                <label>Tema</label>
                <select id="themes" name='themeId' onchange=fillCboTags()>
                    <option value="selecciona">Selecciona
                   <?php
                    foreach($themes as $theme){
                        echo "<option value='".$theme['themeId']."'>".ucfirst($theme['theme'])."</option>";
                    }
                ?>                
                </select>                            
            </div>
            <div class="form-group ">                         
                <label>Escoge etiquetas</label>
                <select name="tags[]" id="tags" onchange=addTag()>
                    <option value="selecciona">Selecciona
                </select>               
                <div id="tagContent" class="d-flex flex-wrap justify-content-around col-md-4"></div>  
                <p id="add" onclick=sendTag()>Añadir etiquetas</p>                                                          
            </div>                     
            <div class="form-group">
                <input type="submit" name="envia" value="Publica entrada"> 
            </div>
            
        </form>
    </div>
    <script>
        
function fillCboTags(){ 
    var form = document.getElementsByName("newPost");
    var tags =document.getElementById("tags");
    if(tags.length == 1){
        getCbo();       
    }else{  
        tagsId=[];     
        emptyCbo();        
        getCbo();
    }

}
function getCbo(){
    var theme = document.getElementById('themes');
    var themeSelected=theme.options[theme.selectedIndex].value;
    var $select = document.querySelector("#tags");
    $.ajax({
        type: "POST",
        url: "<?=BASE?>posts/getThemeTags",
        dataType:'json',
        data: { "id" :  themeSelected },
        success: function(data){     
            for(let a=0; a<data.length; a++){
                var option = document.createElement("option");
                option.value=data[a]['tagId'];
                option.text=data[a]['tag'];
                option.id="tagChild";
                $select.appendChild(option);            
            }         
        } 
    });   
}

function emptyCbo(){
    var select = document.getElementById("tags");
    var i, length = select.options.length;
    for(i = length-1; i>0; i--){
        select.options.remove(i);
    } 
}

function addTag(){   
    var opt = this.document.getElementById("tags");    
    tagsId.push(opt.value);
    var currentDiv = document.getElementById("tagContent");
    var element = document.createElement("div");
    element.className+="btn btn-outline-primary";
    console.log(opt.options[opt.selectedIndex].text);
    var text = document.createTextNode(opt.options[opt.selectedIndex].text);
    element.appendChild(text);
    currentDiv.appendChild(element);
    document.getElementById('add').style.display="flex";
}


function sendTag(){
    $.ajax({
        type: "POST",
        url: "<?=BASE?>posts/saveTag",  
        data: { "tag" :  JSON.stringify(tagsId) },
        success: function(data){         
        }
    });
}
</script>
</section>

<?php
include 'footer.tpl.php';
?>