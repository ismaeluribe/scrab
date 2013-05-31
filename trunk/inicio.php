<?php
//los requires
require_once '/php/modelo/UserDAO.php';
require_once '/php/modelo/PersonasDAO.php';
require_once '/php/modelo/GruposDAO.php';
//iniciamos secion
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
            function scrollUp() {
                $('html,body').animate({
                    scrollTop: 0
                }, 250);
            }
            function nuevoRumor() {
                $.post("php/controlador/RumoresController.php", {grupo: $("#grupos").val(), contenido: $("#contenido").val(), lugar: $("#lugar").val(), enlace: $("#enlace").val()});
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
                            <li class="active"><a href="#"><i class="icon-home icon-white"></i>&nbsp;Inicio</a></li>
                            <li><a href="#">Perfil</a></li>
                            <li><a href="#">Grupos&nbsp;<span class="badge">8</span></a></li>
                            <li><a href="#">Espiados</a></li>
                        </ul>

                        <input type="text" id="searchContent" class="search-query span3" placeholder="Buscar" />
                        <button id="searchElem" class="btn btn-primary" >Busca</button>
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
        <script>
            $("#searchElem").click(function() {
                var ele = $("#searchContent").val();
                $("#searchResult").empty();
                //$("#searchResult").empty()
                if (ele) {
                    $.ajax({
                        type: "POST",
                        url: 'php/controlador/SearchController.php',
                        data: 'data=' + ele,
                        success: responseElements,
                        error: errorElements
                    });
                }
                else {
                    alert("tiene que introducir algun texto para buscar algo");
                }
            });

            function responseElements(e) {
                //*console.log(e);
                
                $("#container-serch-result").removeClass("searchResultOculto");
                var obj = JSON.parse(e);
                //console.log(obj);
                var v_grupos = obj.grupos;
                //console.log(v_grupos);
                var v_personajes = obj.personajes;
                //console.log(v_personajes);
                var v_usuarios = obj.usuarios;
                //console.log(v_usuarios);
                //escribimos los personajes
                $("#searchResult").append("<h5>personajes</h5>");
                if (v_personajes) {
                    //console.log(v_personajes);
                
                    //$("#searchResult").append("<h5>personajes</h5>");
                    for (var i in v_personajes) {
                        $("#searchResult").append("<div id=\"" + i + "p\" class=\"searchResultPersonajes\"></div>");
                        $("#" + i + "p").append("<img src=\"image/personaje/"+v_personajes[i][2]+"\" class=\"search-image\">");
                        $("#" + i + "p").append("<span>" + v_personajes[i][0] + "</span>");
                        $("#" + i + "p").append("<p>" + v_personajes[i][1] + "</p>");
                        if(v_personajes[i][3]!=0){
                           // console.log('entra '+ v_personajes[i][3]);
                            $("#" + i + "p").append("<button id=\"" + i + "p0boton\" value=\"" + i + "p0\" onclick=\"spyPeople(this.value);\" class=\"btn btn-success\">No espiar</button>");
                        }else {
                            $("#" + i + "p").append("<button id=\"" + i + "p1boton\" value=\"" + i + "p1\" onclick=\"spyPeople(this.value);\" class=\"btn btn-primary\">espiar</button>");
                        }
                        
                    }
                } else $("#searchResult").append("<h5>No se han encontrado personaajes</h5>");
                ////////////////////////////////////////////
                $("#searchResult").append("<h5>usuarios</h5>");
                if (v_usuarios) {
                    //$("#searchResult").append("<h5>usuarios</h5>");
                    for (var i in v_usuarios) {
                        $("#searchResult").append("<div id=\"" + i + "u\" class=\"searchResultUser\"></div>");
                        $("#" + i + "u").append("<img src=\"image/usuario/"+v_usuarios[i][2]+"\" class=\"search-image\">");
                        $("#" + i + "u").append("<span>" + v_usuarios[i][0] + "</span>");
                        $("#" + i + "u").append("<p>" + v_usuarios[i][1] + "</p>");
                        if(v_usuarios[i][3]!=0){
                            $("#" + i + "u").append("<button id=\"" + i + "u0boton\" value=\"" + i + "u0\" onclick=\"spyPeople(this.value);\" class=\"btn btn-success\">No espiar</button>");
                        }else {
                            $("#" + i + "u").append("<button id=\"" + i + "u1boton\" value=\"" + i + "u1\" onclick=\"spyPeople(this.value);\" class=\"btn btn-primary\">espiar</button>");
                        }
                        
                    }
                }else $("#searchResult").append("<h5>No se han encontrado usuarios</h5>");
                ///////////////////////////////////////////////
                $("#searchResult").append("<h5>grupos</h5>");
                if (v_grupos) {
                    
                    for (var i in v_grupos) {
                        $("#searchResult").append("<div id=\"" + i + "g\" class=\"searchResultGrupos\"></div>");
                        $("#" + i + "g").append("<img src=\"image/grupo/"+v_grupos[i][2]+"\" class=\"search-image\">");
                        $("#" + i + "g").append("<span>" + v_grupos[i][0] + "</span>");
                        $("#" + i + "g").append("<p>" + v_grupos[i][1] + "</p>");
                        $("#" + i + "g").append("<button id=\"" + i + "gboton\" value=\"" + i + "g\" onclick=\"spyPeople(this.value);\" class=\"btn btn-primary\">auto invitarme</button>");
                    }
                }else $("#searchResult").append("<h5>No se han encontrado grupos</h5>");

            }
            function errorElements(e) {
                alert('liada');
                console.log(e);
                var obj = JSON.parse(e);
                console.log(obj);
            }
            function hideSearch(){
                 $("#container-serch-result").addClass("searchResultOculto");
            }
            var idAction=null;
            function spyPeople(e){
                //console.log(e);
                idAction=e;
                var num=e.charAt(0);
                var controlador=e.charAt(1);
                var action=null;
                if(controlador == 'p' || controlador=='u'){
                    var action=e.charAt(2);
                    controlador='spyPeopleController.php';
                }else{
                    controlador='addGroupController.php';
                }
                
                //alert(action);
                //var tabla=e.charAt(1);
                $.ajax({
                        type: "POST",
                        url: 'php/controlador/'+controlador,
                        data: 'data=' + num+"&action="+action,
                        success: spyReport,
                        error: errorElements
                    });
            }
            function spyReport(e){
                var obj=JSON.parse(e);
                //console.log(e);
                //var r=parseInt(e);
                //console.log(r);
                var idboton="#"+idAction+'boton';
                $(idboton).removeAttr('onclick');
                $(idboton).bind("click", hideSearch);
                $(idboton).removeClass('btn-primary');
                if(obj=="1"){
                    $(idboton).html('Hecho');
                    $(idboton).addClass('btn-success');
                }else{
                    $(idboton).html('Listo');
                    $(idboton).addClass('btn-success');
                }
            }
            function verMas(elemento){
                var padre = elemento.parentNode;
                var nombre = padre.childNodes[1];
                var imagen = padre.childNodes[3];
                var descripcion = padre.childNodes[5];
                var spanNombre = nombre.childNodes[1].childNodes[0];
                var srcImage = imagen.childNodes[1].src;
                var spanDescripcion = descripcion.childNodes[1].childNodes[0];
                $("#nombreModal").append(document.createTextNode(spanNombre.nodeValue));
                $(".fotoModal").attr("src",srcImage);
                $("#descripcionModal").append(document.createTextNode(spanDescripcion.nodeValue));
            }
            $("#contenidoModal").blur(reiniciarModal);
            function reiniciarModal(){
                $("#nombreModal").contents().filter(function(){ return this.nodeType != 1;}).remove();
                $("#descripcionModal").contents().filter(function(){ return this.nodeType != 1;}).remove();
            }
        </script>

        <!-- /Barra de navegación -->

        <br/>
        <br/>
        <div class="container" id="wrapper">
            <div id="container-serch-result" class="searchResultOculto">
                <div id="searchResult">

                </div>
                <button onclick="hideSearch();" class="position-cerrar-search">cerrar</button>
            </div>
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
                    <input type="text" name="enlace" id="enlace" placeholder="Enlace">
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                    <button onclick="nuevoRumor();" class="btn btn-primary" data-dismiss="modal">Enviar rumor</button>
                </div>
            </div>

            <div class="modal hide fade" id="contenidoModal">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" onclick="reiniciarModal()M">x</a>
                    <h3>Grupo</h3>
                </div>
                <div class="modal-body">
                    <div><span id="nombreModal"></span></div>
                    <div class="fotoCajaGrupo">
                        <img class="fotoModal"/>
                    </div>
                    <div><span id="descripcionModal"></span></div>
                </div>
                <div class="modal-footer">
                    <a class="btn pointer" data-dismiss="modal" onclick="reiniciarModal();">Cerrar</a>
                </div>
            </div>

            <div class="tabbable tabs-left todoInicio">
                <ul class="nav nav-tabs listaInicio" style="position:fixed;">
                    <li class="active"><a onclick="scrollUp();" href="#publico" data-toggle="tab">Usuarios</a></li>
                    <li><a href="#grupo1" data-toggle="tab" onclick="scrollUp();">Grupos</a></li>
                    <li><a href="#grupo2" data-toggle="tab" onclick="scrollUp();">Rumores</a></li>
                </ul>
                <div class="tab-content centroInicio">
                    <div class="tab-pane active" id="publico">
                        <?php
                            require_once("php/controlador/MuestraUsersController.php");
                        ?>
                    </div>
                    <div class="tab-pane" id="grupo1">
                        <?php
                            require_once("php/controlador/MuestraGruposController.php");
                        ?>
                    </div>

                    <div class="tab-pane" id="grupo2">
                        <div class="caja cajaRumor">
                            
                        </div>
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