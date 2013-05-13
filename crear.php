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
            function nuevoRumor() {
                var parametros = {
                    "grupo": $("#grupos").val(),
                    "contenido": $("#contenido").val(),
                    "lugar": $("#lugar").val(),
                    "enlace": $("#enlace").val()
                };
                $.ajax({
                    url: 'php/nuevoRumor.php',
                    type: 'POST',
                    data: parametros
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
                            <li><a href="inicio.php"><i class="icon-home icon-white"></i>&nbsp;Inicio</a></li>
                            <li><a href="#">Perfil</a></li>
                            <li><a href="#">Grupos&nbsp;<span class="badge">8</span></a></li>
                            <li><a href="#">Espiados</a></li>
                        </ul>
                        <form class="navbar-search pull-left" action="">
                            <input type="text" class="search-query span3" placeholder="Buscar" />
                        </form>
                        <ul class="nav pull-right">
                            <li class="active">
                                <a href="#">Crear</a>
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
                    <li class="active">
                        <a href="#intro" data-toggle="tab">Intro</a>
                    </li>
                    <li>
                        <a href="#CrearGrupo" data-toggle="tab">Grupos</a>
                    </li>
                    <li>
                        <a href="#CrearPersonaje" data-toggle="tab">Personajes</a>
                    </li>
                </ul>
                <div class="tab-content centroInicio" id="centroInicioJs">
                    <div class="tab-pane active" id="intro">
                        <h1>Aqui puedes crear grupos y personajes</h1>
                    </div>
                    <div class="tab-pane" id="CrearGrupo">
                        <h3>Grupos</h3>
                        <div class="formularioGrupos">
                            </br>
                            <input type="text" placeholder="Nombre del grupo" id="groupName" name="nombreGrupo" maxlength="30"/>
                            </br>
                            <textarea maxlength="255" id="groupDescription" name="descripcion" placeholder="Descripción" class="areaGroup"></textarea>
                            </br>
                            <label>Estado de privacidad del grupo</label>
                            <input type="radio" name="privacidad" value="privado"/> Privado &nbsp;&nbsp;
                            <input type="radio" name="privacidad" value="publico" checked="checked"/>Publico &nbsp;&nbsp;
                            <input type="radio" name="privacidad" value="secreto"/>Secreto
                            </br>
                            </br>
                            <button id="btnSubmit" class="btn btn-primary">Enviar</button>

                        </div>
                        <div id="not-drop-files">
                            <div id="drop-files"> 
                                Arrastra una imagen
                            </div>
                        </div>

                        <div id="response">
                            
                        </div>
                    </div>
                    <div class="tab-pane" id="CrearPersonaje">
                        <h3>Personajes</h3>
                    </div>
                </div>

            </div>
        </div>
        <script>
            //funcion que impide el arrastre en el cuadro interno
            function notDrop(e) {
                e.dataTransfer.dropEffect = 'none';
                return false;
            }

            //funcion para cambiar a copi la accionllevada acabo por elemento arrastrado
            function copyDrop(e) {
                e.dataTransfer.dropEffect = 'copy';
                return false;
            }

            //asignamos la funcion al manejador del evento del tag señalado
            document.getElementById("centroInicioJs").ondragover = notDrop;

            //funciones para cambiar el estado de los elemntos
            function over() {
                document.getElementById("centroInicioJs").ondragover = copyDrop;
                $("#drop-files").css("color", "#006699");
                $("#drop-files").css("background-color", "#cccfff");
            }
            function leave() {
                $("#drop-files").css("background-color", "#cccccc");
                $("#drop-files").css("color", "black");
                document.getElementById("centroInicioJs").ondragover = notDrop;
            }

            //asinamos a los manejadores las funciones para cambiar los elemtnos 
            //demanera que se permita dejar el elemento 
            // al entrar dentro del recuadro
            document.getElementById("drop-files").ondragover = over;
            document.getElementById("drop-files").ondragleave = leave;

            var imageG = null;
            var imageName = null;
            // var imgBool=false;

            function drop(e) {
                e.stopPropagation(); // para la propagacion
                e.preventDefault();
                //aciones al arrastrar la imagen


                //imagen
                var files = e.dataTransfer.files;

                if (!files[0].type.match('image.*')) {
                    $("#drop-files").html("tienes que arrastrar una imagen");
                    return false;
                }
                else {
                    var reader = new FileReader();
                    reader.onload = (function(theFile) {
                        return function(e) {
                            imageG = this.result;

                            $("#drop-files").empty();

                            $("#drop-files").append("<img class=\"imagen\" src=\"" + e.target.result + "\" alt=\"" + theFile.name + "\">");

                        };

                    })(files[0]);
                    //imageG = files[0].result;
                    imageName = files[0].name;
                    //alert (name);
                    reader.readAsDataURL(files[0]);
                    // alert(files[0]);
                    // var a = JSON.stringify(files[0]);
                    //  alert(a);
                }
                $("#drop-files").css("background-color", "#cccccc");
                $("#drop-files").css("color", "black");
                document.getElementById("centroInicioJs").ondragover = notDrop;
            }

            document.getElementById("drop-files").ondrop = drop;

            $("#btnSubmit").click(function() {
                var val1 = $("#groupName").val();
                var val2 = $("#groupDescription").val();
                var val3 = $("input[name='privacidad']:checked").val();
                // alert (val1 + val2 + val3);
                $("#drop-files").empty();
                $("#groupName").val("");
                $("#groupDescription").val("");
                $.ajax({
                    url: 'php/controlador/GruposController.php',
                    type: 'POST',
                    data: 'name=' + val1 + '&description=' + val2 + '&privacidad=' + val3 + '&image=' + imageG + '&imageName=' + imageName,
                    success: successfullAjaxResponse,
                    error: errorAjax
                });

            });

            function successfullAjaxResponse(e) {
                //$("#response").html(e);
                /*      <div class="alert alert-success">
                 
                 </div>*/
                //alert (e);
                var obj = JSON.parse(e)
                if (obj.data) {
                    $("#response").append("<div class=\"alert alert-success\">Se ha guardado el grupo</div>");
                }
                else{
                    $("#response").append("<div class=\"alert alert-error\">ha ocurrido un error</div>");
                }
                if(obj.img){
                    $("#response").append("<div class=\"alert alert-success\">Se ha guardado el la imagen</div>");
                }else if(obj.img===null){
                    $("#response").append("<div class=\"alert alert-block\">un grupo sin imagen es como un jardin sin flores</div>");
                }else{
                    $("#response").append("<div class=\"alert alert-error\">ha ocurrido un error</div>");
                }

                //alert(obj);
            }
            function errorAjax(e) {
                $("#response").html(e);
            }
        </script>
    </body>
</html>