<?php
//los requires
require_once '/php/modelo/UserDAO.php';
require_once '/php/modelo/PersonasDAO.php';
//iniciamos secion
session_start();
if (!isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])) {
    header("location: index.php");
}

try {
    $bd = new UserDAO();
    $objPersonas = new PersonasDAO();
    //obtenemos los datos relativos a las personas
    $arrayPersonas = $objPersonas->getDataById($_SESSION['id']);
    //si el array esta vacio salta esta exepcion
    if (!$arrayPersonas)
        throw new ModeloException('no existe el id del usuario en personas');
} catch (UserException $eu) {
    
} catch (ModeloException $em) {
    
} catch (RuntimeException $e) {
    
}

if (isset($_GET['cerrar'])) {
    $bd->cerrarSesion();
}
?>
<!DOCTYPE html>
<html
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="img/scrab.png" type="image/png" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
        <!--<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />-->
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
        <script type="text/javascript">
            function scrollUp() {
                $('html,body').animate({
                    scrollTop: 0
                }, 250);
            }
            function nuevoRumor(){
                var parametros = {
                    "grupo":$("#grupos").val(),
                    "contenido":$("#contenido").val(),
                    "lugar":$("#lugar").val(),
                    "enlace":$("#enlace").val()
                };
                var ajax = $.ajax({
                    url: "php/nuevoRumor.php",
                    type: "POST",
                    data: {
                    "grupo":$("#grupos").val(),
                    "contenido":$("#contenido").val(),
                    "lugar":$("#lugar").val(),
                    "enlace":$("#enlace").val()
                }
                });
            }
        </script>
        <script src="js/nuevoRumor.js"></script>

        <!--/Scripts -->

        <title>Scrab</title>
    </head>
    <body>

        <!-- Barra de navegación -->

        <header class="navbar navbar-fixed-top">
            <nav class="navbar-inner" style="margin:auto;">
                <div class="container fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target="nav-collapse">
                    </a>
                    <a class="brand" href="index.php">Scrab</a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active"><a href="#"><i class="icon-home icon-white"></i>&nbsp;Inicio</a></li>
                            <li><a href="#">Perfil</a></li>
                            <li><a href="#">Grupos&nbsp;<span class="badge">8</span></a></li>
                            <li><a href="#">Espiados</a></li>
                        </ul>
                        <form class="navbar-search pull-left" action="">
                            <input type="text" class="search-query span3" placeholder="Buscar" />
                        </form>
                        <ul class="nav pull-right">
                            <li>
                                <a href="crear.php">Crear</a>
                            </li>
                            <li>
                                <a href="#nuevoRumor" data-toggle="modal" title="Nuevo rumor" class="btn-primary">
                                    <img src="img/scrab.png" style="height:17px;" />
                                </a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img id="fperfil" src="img/cosas.jpg">&nbsp;&nbsp;<?php echo $_SESSION['user']; ?></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Ayuda</a></li>
                                    <li><a href="#">Configuración</a></li>
                                    <li class="divider"></li>
                                    <li><a href="inicio.php?cerrar=1" name="cerrar">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- /Barra de navegación -->

        <br/>
        <br/>
        <div class="container" id="wrapper">
            <div class="modal hide fade" id="nuevoRumor">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">x</a>
                    <h3>Nuevo rumor</h3>
                </div>
                <div class="modal-body">
                    <select name="grupos" id="grupos">
                        <option value="1">Público</option>
                        <option value="2">Grupo1</option>
                    </select>
                    <textarea name="contenido" id="contenido" cols="30" rows="10" placeholder="Contenido"></textarea>
                    <input type="text" name="lugar" id="lugar" placeholder="Lugar">
                    <input type="text" name="enlace" id="enlace" placeholder="Enlace">
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                    <button onclick="nuevoRumor();" class="btn btn-primary" data-dismiss="modal">Enviar rumor</button>
                </div>
            </div>
            <div class="tabbable tabs-left todoInicio">
                <ul class="nav nav-tabs listaInicio" style="position:fixed;">
                    <li class="active"><a onclick="scrollUp();" href="#publico" data-toggle="tab">Público</a></li>
                    <li><a href="#grupo1" data-toggle="tab" onclick="scrollUp();">Grupo1</a></li>
                    <li><a href="#grupo2" data-toggle="tab" onclick="scrollUp();">Grupo2</a></li>
                </ul>
                <div class="tab-content centroInicio">
                    <div class="tab-pane active" id="publico">
                        <div class="caja">texto1.1</div>
                        <div class="caja">texto1.2</div>
                        <div class="caja">texto1.3</div>
                        <div class="caja">texto1.4</div>
                        <div class="caja">texto1.5</div>
                        <div class="caja">texto1.6</div>
                        <div class="caja">texto1.7</div>
                        <div class="caja">texto1.8</div>
                        <div class="caja">texto1.9</div>
                        <div class="caja">texto1.10</div>
                        <div class="caja">texto1.11</div>
                        <div class="caja">texto1.12</div>
                        <div class="caja">texto1.13</div>
                        <div class="caja">texto1.14</div>
                        <div class="caja">texto1.15</div>
                        <div class="caja">texto1.16</div>
                        <div class="caja">texto1.17</div>
                        <div class="caja">texto1.18</div>
                        <div class="caja">texto1.19</div>
                        <div class="caja">texto1.20</div>
                        <div class="caja">texto1.21</div>
                    </div>
                    <div class="tab-pane" id="grupo1">
                        <div class="caja">texto2.1</div>
                        <div class="caja">texto2.2</div>
                        <div class="caja">texto2.3</div>
                        <div class="caja">texto2.4</div>
                        <div class="caja">texto2.5</div>
                        <div class="caja">texto2.6</div>
                        <div class="caja">texto2.7</div>
                        <div class="caja">texto2.8</div>
                        <div class="caja">texto2.9</div>
                        <div class="caja">texto2.10</div>
                        <div class="caja">texto2.11</div>
                        <div class="caja">texto2.12</div>
                        <div class="caja">texto2.13</div>
                        <div class="caja">texto2.14</div>
                        <div class="caja">texto2.15</div>
                        <div class="caja">texto2.16</div>
                        <div class="caja">texto2.17</div>
                        <div class="caja">texto2.18</div>
                        <div class="caja">texto2.19</div>
                        <div class="caja">texto2.20</div>
                        <div class="caja">texto2.21</div>
                    </div>
                    <div class="tab-pane" id="grupo2">
                        <div class="caja">texto3.1</div>
                        <div class="caja">texto3.2</div>
                        <div class="caja">texto3.3</div>
                        <div class="caja">texto3.4</div>
                        <div class="caja">texto3.5</div>
                        <div class="caja">texto3.6</div>
                        <div class="caja">texto3.7</div>
                        <div class="caja">texto3.8</div>
                        <div class="caja">texto3.9</div>
                        <div class="caja">texto3.10</div>
                        <div class="caja">texto3.11</div>
                        <div class="caja">texto3.12</div>
                        <div class="caja">texto3.13</div>
                        <div class="caja">texto3.14</div>
                        <div class="caja">texto3.15</div>
                        <div class="caja">texto3.16</div>
                        <div class="caja">texto3.17</div>
                        <div class="caja">texto3.18</div>
                        <div class="caja">texto3.19</div>
                        <div class="caja">texto3.20</div>
                        <div class="caja">texto3.21</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>