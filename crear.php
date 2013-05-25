<?php
/*
 * Nicolas Quiceno
 * clase que contiene los formularios para crear personajes y grupos
 */
//los requires

require_once 'php/modelo/UserDAO.php';
require_once 'php/modelo/PersonasDAO.php';
require_once 'php/modelo/modeloException/UserException.php';
require_once 'php/modelo/modeloException/ModeloException.php';


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
            function nuevoRumor(){
                $.post("php/controlador/RumoresController.php",{grupo:$("#grupos").val(),contenido:$("#contenido").val(),lugar:$("#lugar").val(),enlace:$("#enlace").val()});
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
                            <li class="active">
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
                                    <li><a href="configuracion.php">Configuración</a></li>
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
                    </select><br>
                    <textarea name="contenido" id="contenido" cols="30" rows="10" placeholder="Contenido"></textarea><br>
                    <input type="text" name="lugar" id="lugar" placeholder="Lugar"><br>
                    <input type="text" name="enlace" id="enlace" placeholder="Enlace"><br>
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
                        <a href="#CrearPersonaje" data-toggle="tab" onclick="groupList();">Personajes</a>
                    </li>
                </ul>
                <div class="tab-content centroInicio" id="centroInicioJs">
                    <div class="tab-pane active" id="intro">
                        <h1>Aqui puedes crear grupos y personajes</h1>
                    </div>

                    <!--*******************************************************-->

                    <div class="tab-pane" id="CrearGrupo">
                        <h3>Grupos</h3>
                        <div class="content-drop-and-form">
                            <div class="formularioGrupos">
                                </br>
                                <label>Nombre del grupo</label>
                                <input type="text" placeholder="Nombre del grupo" id="groupName" name="nombreGrupo" maxlength="30"/>
                                </br>
                                <label>Descripcion del grupo</label>
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
                            <div id="not-drop-files" class="not-drop-files-generic">
                                <div id="drop-filesG" class="drop-files-generic"> 
                                    Arrastra una imagen
                                </div>
                            </div>
                        </div>
                        <div id="responseG" class="response-generic">

                        </div>
                    </div>


                    <!--*********************************************-->



                    <div class="tab-pane" id="CrearPersonaje">
                        <h3>Personajes</h3>

                        <div class="content-drop-and-form">
                            <div id="personajesForm" class="formularioGrupos">
                                </br>
                                <label>Nombre *</label>
                                <input type="text" placeholder="Nombre" id="nombrePer" maxlength="20"/>
                                </br>
                                <label>Primer apellido *</label>
                                <input type="text" placeholder="Apellido" id="apellidoPer1"  maxlength="20"/>
                                </br>
                                <label>Segundo apellido</label>
                                <input type="text" placeholder="Apellido" id="apellidoPer2"  maxlength="20"/>
                                </br>
                                <label>Cuando nacio</label>
                                <input type="date" placeholder="dd-mm-yyyy" id="nacPer" required="required"/>
                                </br>
                                <label>Sexo si tiene...</label>
                                <select name="sexo" id="sexoPer">
                                    <option value="s" selected="selected">Desconocido</option>
                                    <option value="h">Hombre</option>
                                    <option value="m">Mujer</option>
                                </select>
                                </br>
                                <label>Mote, apodo, segundo nombre... *</label>
                                <input type="text" placeholder="lo que te salga, se duro" id="motePer" maxlength="30"/>
                                </br>


                                <label>Descripcion</label>
                                <textarea maxlength="255" id="descripcionPer" placeholder="Describelo, se todo lo cruel que puedas" class="areaGroup"></textarea>
                                </br>
                                <button id="btnSubmit-personaje" class="btn btn-primary">Nuevo</button>
                            </div>
                            <div class="not-drop-files-generic">
                                <div id="drop-filesP" class="drop-files-generic"> 
                                    Arrastra una imagen
                                </div>
                            </div>
                            <h4>Elige los grupos</h4>
                            <div id="groupList">

                            </div>
                        </div>
                        <div id="responseP" class="response-generic">

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <script>
            //primer script para la validacion y envio de la creacion de grupos
            //
            //

            var objG = null;//esta variable se carga cuando se haace una peeticion post con el nombre de
            //los grupos por usuario, de manera que solo se realiza una peticion al hacer un nuevo grupo
            function notDrop(e) {//funcion que impide el arrastre en el cuadro interno
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
                $(".drop-files-generic").css("color", "#006699");
                $(".drop-files-generic").css("background-color", "#cccfff");
            }
            function leave() {
                $(".drop-files-generic").css("background-color", "#cccccc");
                $(".drop-files-generic").css("color", "black");
                document.getElementById("centroInicioJs").ondragover = notDrop;
            }

            //asinamos a los manejadores las funciones para cambiar los elemtnos 
            //demanera que se permita dejar el elemento 
            // al entrar dentro del recuadro
            document.getElementById("drop-filesG").ondragover = over;
            document.getElementById("drop-filesG").ondragleave = leave;

            var imageG = null;
            var imageNameG = null;
            // var imgBool=false;

            function drop(e) {
                e.stopPropagation(); // para la propagacion
                e.preventDefault();
                //aciones al arrastrar la imagen

                //imagen
                var files = e.dataTransfer.files;

                if (!files[0].type.match('image.*')) {
                    $("#drop-filesG").html("tienes que arrastrar una imagen");
                    return false;
                }
                else {
                    var reader = new FileReader();
                    reader.onload = (function(theFile) {
                        return function(e) {
                            imageG = this.result;

                            $("#drop-filesG").empty();

                            $("#drop-filesG").append("<img class=\"imagen\" src=\"" + e.target.result + "\" alt=\"" + theFile.name + "\">");

                        };

                    })(files[0]);
                    //imageG = files[0].result;
                    imageNameG = files[0].name;
                    //alert (name);
                    reader.readAsDataURL(files[0]);
                    // alert(files[0]);
                    // var a = JSON.stringify(files[0]);
                    //  alert(a);
                }
                $(".drop-files-generic").css("background-color", "#cccccc");
                $(".drop-files-generic").css("color", "black");
                document.getElementById("centroInicioJs").ondragover = notDrop;
            }

            document.getElementById("drop-filesG").ondrop = drop;

            $("#btnSubmit").click(function() {//funcion que se activa al apretar el boton de envio
                var val1 = $("#groupName").val();
                var val2 = $("#groupDescription").val();
                var val3 = $("input[name='privacidad']:checked").val();
                // alert (val1 + val2 + val3);
                $("#drop-filesG").empty();
                $("#groupName").val("");
                $("#groupDescription").val("");
                objG = null;//volvemos a poner el objeto a null para volver a realizar una peticion
                //console.log('es nulll');
                $.ajax({
                    url: 'php/controlador/GruposController.php',
                    type: 'POST',
                    data: 'name=' + val1 + '&description=' + val2 + '&privacidad=' + val3 + '&image=' + imageG + '&imageName=' + imageNameG,
                    success: successfullAjaxResponse,
                    error: errorAjax
                });

            });

            function successfullAjaxResponse(e) {//respuesta a la creacion del grupo
                var obj = JSON.parse(e);
                if (obj.data) {//si el nombre y la descripcion se han insertado
                    $("#responseG").append("<div class=\"alert alert-success\">Se ha guardado el grupo</div>");
                }
                else {
                    $("#responseG").append("<div class=\"alert alert-error\">ha ocurrido un error</div>");
                }
                if (obj.img) {//si la imagen se ha posido guardar en el servidor
                    $("#responseG").append("<div class=\"alert alert-success\">Se ha guardado el la imagen</div>");
                } else if (obj.img === null) {//si no habia imagen
                    $("#responseG").append("<div class=\"alert alert-block\">un grupo sin imagen es como un jardin sin flores</div>");
                } else {
                    $("#responseG").append("<div class=\"alert alert-error\">ha ocurrido un error</div>");
                }
            }
            function errorAjax(e) {//si ha habido un problema con la peticion
                $("#responseG").html('<h3>upsss hay un problema en el sevidor<h3>');
            }
        </script>


        <script>
            //segundo script paara el envio y validacion del formulario de personajes
            document.getElementById("drop-filesP").ondragover = over;
            document.getElementById("drop-filesP").ondragleave = leave;

            var imageP = null;
            var imageNameP = null;
            // var imgBool=false;

            function dropImageP(e) {//script para controlar el drag and drop de la imagen
                e.stopPropagation(); // para la propagacion
                e.preventDefault();
                //aciones al arrastrar la imagen
                //imagen
                var files = e.dataTransfer.files;
                if (!files[0].type.match('image.*')) {//si no ess una imegen
                    $("#drop-filesP").html("tienes que arrastrar una imagen");
                    return false;
                }
                else {//si es una imagen
                    var reader = new FileReader();
                    reader.onload = (function(theFile) {//le asignamos un clouster a la carga de la iamgen
                        return function(e) {
                            imageP = this.result;

                            $("#drop-filesP").empty();

                            $("#drop-filesP").append("<img class=\"imagen\" src=\"" + e.target.result + "\" alt=\"" + theFile.name + "\">");

                        };

                    })(files[0]);//parametros para el clouster autoinvocado
                    imageNameP = files[0].name;//obtenemos el nombre de la imagen
                    reader.readAsDataURL(files[0]);
                }
                $(".drop-files-generic").css("background-color", "#cccccc");
                $(".drop-files-generic").css("color", "black");
                document.getElementById("centroInicioJs").ondragover = notDrop;
            }

            document.getElementById("drop-filesP").ondrop = dropImageP;
            function groupList() {
                if (objG === null) {//si esto esta a null es por que o bien no se a ha realizado nunguna peticion post
                    // o si se ha realizado no ha habido modificacion en la bd insertando otro grupo
                    $.ajax({
                        url: 'php/controlador/GruposListController.php',
                        type: 'POST',
                        data: 'groupListBy=<?php //echo $_SESSION['id']; ?>',
                        success: printList,
                        error: errorListAjax
                    });
                }
            }

            function printList(e) {//funcion para pintar la lista de grupos
                //console.log(e);
                $("#groupList").empty();//borramos el contenido en caso de que haya algo
                objG = JSON.parse(e);
                for (var i in objG) {
                    $("#groupList").append("<p class=\"pointer\" onMouseOver=\"overGroup(this);\" onMouseOut=\"outGroup(this);\" onclick=\"selectedGroup(this);\">" + objG[i] + "</p>");
                }
            }
            function errorListAjax(e) {
                $("#groupList").html('<h3>liada</h3>');
            }
            function overGroup(e) {
                $(e).addClass("overGroup");
            }
            function outGroup(e) {
                $(e).removeClass("overGroup");
            }
            var objGselected = new Object();
            function selectedGroup(e) {
                var a = true;
                for (var i in objGselected) {
                    //recorremoss el objeto par ver si tenemos
                    //los datos ya guardado
                    if (objGselected[i] == $(e).html()) {//si los tenemos guardados borramos
                        delete objGselected[i];
                        a = false;//le damos false para que no entre en el condicional
                        $(e).removeClass("selectedGroup");//le quitamos los estilos de seleccionado
                    }
                }
                if (a) {
                    for (var i in objG) {//recorremos el objeto de los grupos 
                        //para almacenar el nombre y el indice
                        if (objG[i] == $(e).html()) {
                            objGselected[i] = objG[i];
                            $(e).addClass("selectedGroup");
                        }
                    }
                }
            }


            $("#btnSubmit-personaje").click(function() {
                //console.log(objGselected[1]);
                //console.log($('#sexoPer').val());
                var a = Object.keys(objGselected);
                if (a[0] && $("#nombrePer").val() && $("#apellidoPer1").val() && $("#motePer").val()) {
                    //objGselected.data = getForm();
                    //console.log(objGselected);
                    var objSent= new Object();
                    
                    objSent.groups=objGselected;
                    objSent.data=getForm();
                    if(imageNameP&&imageP){
                        var objImg=new Object();
                        objImg.imageName=imageNameP;
                        objImg.imageData=imageP;
                        objSent.img=objImg;
                    }
                    
                    //console.log(JSON.stringify(objGselected));
                    $.ajax({
                        type: "POST",
                        url: 'php/controlador/PersonajesController.php',
                        data: 'personajeData='+ JSON.stringify(objSent),
                        //contentType: "application/json; charset=utf-8",
                        //dataType: "json",
                        success: personajeAjax,
                        error: personajeAjaxError
                    });
                } else {
                    $("#responseP").append("<div class=\"alert alert-error\">Faltan datos para crear al personaje</div>");
                
                }
            });
            
            function personajeAjax(e){
               // alert(e);
               var obj = JSON.parse(e);
                console.log(obj);
                if(obj.cont>0){
                    $("#responseP").append("<div class=\"alert alert-success\">Se han creado "+obj.cont+"</div>");
                }else{
                    $("#responseP").append("<div class=\"alert alert-error\">No se ha creado nada en la bd </div>");
                }
                var contS=0;
                var contN=0;
                var v=obj.img;
                for(var i in v){
                    if(v[i]){
                            contS++;
                    }else {
                        contN++;
                        }
                }
                if(contS){
                    $("#responseP").append("<div class=\"alert alert-success\">Se han guardado "+contS+" imagenes</div>");
                }
                if(contN){
                    $("#responseP").append("<div class=\"alert alert-error\">No se han guardado "+contN+" </div>");
                }
                
                
                    
                //$("#responseP").html(e);
            }
            
            function personajeAjaxError(e){
                //alert(e);
                
                //var obj = JSON.parse(e);
                console.log(obj);
                alert('error en la peticion');

            }

            function getForm() {//para obtener los datos del formulario
                var dataArray = new Array();
                $("#personajesForm [id*=Per]").each(function(indice, valor) {
                    dataArray.push($(valor).val());

                });
                return dataArray;
            }
        </script>
    </body>
</html>
