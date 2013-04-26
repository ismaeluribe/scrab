<!DOCTYPE html>
<?php
session_start();

require_once("php/modelo/UserDAO.php");

if (isset($_SESSION['user'])) {
    header("location: inicio.php");
}
if (isset($_POST['submit'])) {
    $bd = new UserDAO();
    $bd->registroUsuario();
}
?>
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
        <div class="container" id="wrapper1">
            <header class="navbar navbar-fixed-top">
                <nav class="navbar-inner" style="margin:auto;">
                    <div class="container fluid">
                        <a class="brand" href="index.php">Scrab</a>
                    </div>
                </nav>
            </header>
            <article>

                <form class="formulario" action="registro.php" method="POST">
                    <input type="text" placeholder="Nombre" name="nom" />
                    </br>
                    <input type="text" placeholder="Primer Apellido" name="ape1" />
                    </br>
                    <input type="text" placeholder="Segundo Apellido" name="ape2" />
                    </br>
                    <input type="date" placeholder="Fecha de nacimiento" name="nac" />
                    </br>
                    Sexo: <select name="sexo">
                        <option value="s" selected="selected">Sin especificar</option>
                        <option value="h">Hombre</option>
                        <option value="m">Mujer</option>
                    </select>
                    </br>
                    <input type="text" placeholder="Nombre de Usuario" name="user" required="required"/>
                    </br>
                    <input type="email" placeholder="Email" name="email" required="required"/>
                    </br>
                    <input type="password" placeholder="Contraseña" name="pass" required="required" />
                    </br>
                    <input type="password" placeholder="Repira la contraseña" name="pass2" required="required" />
                    </br>
                    <input type="checkbox" id="terminos" value="Terminos" />&nbsp; Acepto los términos
                    </br>
                    </br>
                    <button type="submit" class="btn btn-primary" name="submit">Enviar</button>
                </form>
            </article>
        </div>
    </body>
</html>