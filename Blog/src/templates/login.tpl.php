<?php 
include 'registerHeader.tpl.php';

?>
<section class="col-md-12 d-flex flex-column justify-content-center register">
  <div class="col-md-3 align-self-center">
  <?php
    if(isset($_SESSION['register'])){               
        echo "<h3>".$_SESSION['register']."</h3>";  
    } 
    
    if(isset($_SESSION['error'])){               
        echo "<h3>".$_SESSION['error']."</h3>";  
    }
    ?>
      <?=$form?>
  </div>  
</section>

<?php
include 'footer.tpl.php';

