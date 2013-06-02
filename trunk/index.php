
<?php

session_start();


if (isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])) {
    header("location: ./php/controlador/inicioController.php");
}
session_regenerate_id(true);

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8"> 
        <link rel="shortcut icon" href="img/scrab.png" type="image/png" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />

        <!-- Scripts-->

        <script src="js/libs/jquery/jquery.js"></script>
        <script src="js/libs/bootstrap/bootstrap-affix.js"></script>
        <script src="js/libs/bootstrap/bootstrap-alert.js"></script>
        <script src="js/libs/bootstrap/bootstrap-button.js"></script>
        <script src="js/libs/bootstrap/bootstrap-carousel.js"></script>
        <script src="js/libs/bootstrap/bootstrap-collapse.js"></script>
        <script src="js/libs/bootstrap/bootstrap-dropdown.js"></script>
        <script src="js/libs/bootstrap/bootstrap-modal.js"></script>
        <script src="js/libs/bootstrap/bootstrap-transition.js"></script>
        <script src="js/libs/bootstrap/bootstrap-popover.js"></script>
        <script src="js/libs/bootstrap/bootstrap-scrollspy.js"></script>
        <script src="js/libs/bootstrap/bootstrap-tab.js"></script>
        <script src="js/libs/bootstrap/bootstrap-tooltip.js"></script>
        <script src="js/libs/bootstrap/bootstrap-typeahead.js"></script>

        <!--/Scripts -->

        <title>Scrab</title>
    </head>
    <body>
        <div class="container">
            <header class="navbar navbar-fixed-top">
                <nav class="navbar-inner" style="margin:auto;">
                    <div class="container fluid">
                        <a class="brand" href="index.php">Scrab</a>
                </div>
                </nav>
            </header>
            <article>
                <form class="formulario modal" action="php/controlador/InicioController.php?id=<?php echo session_id();?>" method="POST">
                    <br/>
                    <br/>
                    <?php
                    if(isset($_GET['errno'])){
                        if($_GET['errno']==1)       echo '<h5>error en la base de datos</h5>';
                            elseif ($_GET['errno']==2) echo '<h5>contraseña incorrecta</h5>';
                            else echo '<h5>error desconocido</h5>';
                        
                    }
                    
                    ?>
                    <input type="text" placeholder="Usuario" name="user" required="required" />
                    <input type="password" placeholder="Contraseña" name="pass" required="required" />
                    <br/>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="registro.php" class="btn btn-primary">Registro</a>
                </form>
            </article>
        </div>
    </body>
</html>