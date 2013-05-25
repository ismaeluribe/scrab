<?php
/*
 * Nicolas Quiceno
 * clase que contiene los formularios para crear personajes y grupos
 */
//los requires
require_once '/php/modelo/UserDAO.php';
require_once '/php/modelo/PersonasDAO.php';
session_start();
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {
    header("location: index.php");
}

try {
    $bd = new UserDAO();
    $privacidad = new UserDAO();
    $objPersonas = new PersonasDAO();
    //obtenemos los datos relativos a las personas
    $arrayPersonas = $objPersonas->getDataById($_SESSION['id']);
    $arrayUser = $bd->getUserInfo($_SESSION['id']);
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

            function nuevoRumor(){
                $.post("php/controlador/RumoresController.php",{grupo:$("#grupos").val(),contenido:$("#contenido").val(),lugar:$("#lugar").val(),enlace:$("#enlace").val()});
            }

            function configurarDatos(){
                var val1 = $("#nombre").val();
                var val2 = $("#apellido1").val();
                var val3 = $("#apellido2").val();
                var val4 = $("#correo").val();
                var val5 = $("input[name='privacidad']:checked").val();
                $.ajax({
                    url: 'php/controlador/ConfiguracionController.php',
                    type: 'POST',
                    data: 'name=' + val1 + '&apellido=' + val2 + '&apellido2=' + val3 + '&mail=' + val4 + '&privacidad=' + val5,
                    success: successfullAjaxResponse,
                    error: errorAjax
                });
            }

            function successfullAjaxResponse() {//respuesta a la creacion del grupo
                    $("#responseG").append("<div class=\"alert alert-success\">Se ha guardado la configuración</div>");
            }

            function errorAjax() {//si ha habido un problema con la peticion
                $("#responseG").html('<h3>Upsss hay un problema en el sevidor<h3>');
            }

            function compruebaContra(){
                var nueva1 = $("#nueva1").val();
                var nueva2 = $("#nueva2").val();
                if((/^\w{6,20}$/.test(nueva1)) && (/^\w{6,20}$/.test(nueva2)) && (nueva1 == nueva2)){
                    $("#nueva1").css("color","black");
                    $("#nueva2").css("color","black");
                }else{
                    $("#nueva1").css("color","red");
                    $("#nueva2").css("color","red");
                }
            }


        </script>

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
                            <li><a href="inicio.php"><i class="icon-home icon-white"></i>&nbsp;Inicio</a></li>
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
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img id="fperfil" src="image/usuario/<?php echo $arrayPersonas['imagen']; ?>">&nbsp;&nbsp;<?php echo $_SESSION['user']; ?></a>
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
                    <li class="active">
                        <a href="#datos" data-toggle="tab">Datos</a>
                    </li>
                    <li>
                        <a href="#contraseña" data-toggle="tab">Contraseña</a>
                    </li>
                </ul>
                <div class="tab-content centroInicio" id="centroInicioJs">
                    <div class="tab-pane active" id="datos">
                        <h3>Datos</h3>
                        <form name="datos" action="configuracion.php" method="POST">
                            <br><br>
                            <input id="nombre" type="text" placeholder="Nombre" value="<?php echo $arrayPersonas['nombre']; ?>"><br>
                            <input id="apellido1" type="text" placeholder="Primer apellido" value="<?php echo $arrayPersonas['apellido']; ?>"><br>
                            <input id="apellido2" type="text" placeholder="Segundo apellido" value="<?php echo $arrayPersonas['apellido2']; ?>"><br>
                            <input id="correo" type="text" placeholder="Correo electrónico" value="<?php echo $arrayUser['mail']; ?>"><br>
                            <h3>Privacidad</h3>
                            <?php
                                $privacidad->formPrivacidad($_SESSION['id']);
                            ?>
                            <input onclick="configurarDatos()" type="button" class="btn btn-primary" value="Enviar">
                        </form>
                        <div id="responseG" class="response-generic"></div>
                    </div>
                    <div class="tab-pane" id="contraseña">
                        <h3>Contraseña</h3>
                        <form name="contraseña" action="php/controlador/ContraController.php" method="POST">
                            <br><br>
                            <input oninput="compruebaContra()" id="nueva1" name="nueva1" type="password" placeholder="Nueva contraseña"><br>
                            <input oninput="compruebaContra()" id="nueva2" name="nueva2" type="password" placeholder="Repetir nueva Contraseña"><br>
                            <input name="antigua" type="password" placeholder="Antigua contraseña"><br><br>
                            <input type="submit" class="btn btn-primary" value="Enviar">
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>