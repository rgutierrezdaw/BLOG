<?php
include 'registerHeader.tpl.php';
?>
<section class="col-md-12 d-flex flex-column justify-content-center register">
    <div class="col-md-3 align-self-center">
<?php 
        if(isset($_SESSION['register'])){               
            echo "<h3>".$_SESSION['register']."</h3>";  
        } 
    ?>     
            <form  action="<?php echo BASE;?>user/register" method="POST">
                <h3>Regístrate</h3>
                    <div class="form-group">
                        <label for="username">User name</label>
                        <input type="text" class="form-control" name="newuser" placeholder="Introducir usuario" required>             
                    </div>
                    <div class="form-group">
                        <label for="username">E-mail</label>
                        <input type="text" class="form-control" name="mail" placeholder="Introduce tu correo electrónico" required>             
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="newpwd" placeholder="Contraseña" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Registrar">
                </form> 
    </div>
</section>
  
<?php
include 'footer.tpl.php';
?>