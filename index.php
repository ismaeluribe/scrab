<!DOCTYPE html>
<?php 


session_start();



require_once ("php/modelo/UserDAO.php");

  if(isset($_SESSION['user'])){
    header ("location: inicio.php");
  }
  if(isset($_POST['pass'])){
    $bd = new UserDAO();
    $bd->userpass();
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

    <form class="formulario" action="index.php" method="POST">
      <?php

          if(!isset($_SESSION['user']) && isset($_POST['user'])){
            echo "<h5>Contraseña incorrecta</h5>";
          }

        ?>
      <input type="text" placeholder="Usuario" name="user" required="required"/>
      <input type="password" placeholder="Contraseña" name="pass" required="required" />
    </br>
      <button type="submit" class="btn btn-primary">Enviar</button>
      <a href="registro.php" class="btn btn-primary">Registro</a>
    </form>
  </article>
</div>
</body>
</html>