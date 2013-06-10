<?php
//los requires
require_once '/php/modelo/UserDAO.php';
require_once '/php/modelo/ApoyosDAO.php';
require_once '/php/modelo/RumoresDAO.php';
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
    $objG=new GruposDAO();
    $g_array = $objG->getGroupDataByUserId($_SESSION['id']);
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
                $("#fotosModalContent").val('');
                $("#contenido").val('');
                $("#lugar").val('');
                $("#enlace").val('');
                $("#modalSearchText").val('');
                $(".modalResultSearchUser").remove();
                $("#modalSearchUser").addClass('searchResultOculto');
                $.post("php/controlador/RumoresController.php",{grupo:$("#grupos").val(),contenido:$("#contenido").val(),lugar:$("#lugar").val(),enlace:$("#enlace").val(),tratade:$("#modalSearchUser").val(),imageFileM : imageModal,imageNameM : imageNameModal});
            }

                function notDrop(e) {//funcion que impide el arrastre en el cuadro interno
                    e.dataTransfer.dropEffect = 'none';
                    return false;
                }

                //funcion para cambiar a copi la accionllevada acabo por elemento arrastrado
                function copyDrop(e) {
                    e.dataTransfer.dropEffect = 'copy';
                    return false;
                }


            //funciones para cambiar el estado de los elemntos
            function over() {
               // document.getElementById("centroInicioJs").ondragover = copyDrop;
                document.getElementById("dropModal").ondragover = copyDrop;

                $(".drop-files-generic").css("color", "#006699");
                $(".drop-files-generic").css("background-color", "#cccfff");
            }
            function leave() {
                $(".drop-files-generic").css("background-color", "#cccccc");
                $(".drop-files-generic").css("color", "black");
                //document.getElementById("centroInicioJs").ondragover = notDrop;
                document.getElementById("dropModal").ondragover = notDrop;
            }
</script>
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
                            <li><a href="perfil.php">Perfil</a></li>
                            <li class="active"><a href="grupos.php">Grupos</a></li>
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
                    $("#container-serch-result").append("<img class=\"imageLoading\" id=\"loader\" src=\"img/ajax-loader.gif\" alt=\"Loader\">");
                    $("#container-serch-result").removeClass("searchResultOculto");
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
                $("#loader").remove();
                //$("#container-serch-result").removeClass("searchResultOculto");
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
                $("#tituloGrupo").contents().filter(function(){ return this.nodeType != 1;}).remove();
                $("#descripcionModal").contents().filter(function(){ return this.nodeType != 1;}).remove();
                $("#enlaceGrupo").contents().filter(function(){ return this.nodeType != 1;}).remove();
                var padre = elemento.parentNode;
                var nombre = padre.childNodes[1];
                var imagen = padre.childNodes[3];
                var descripcion = padre.childNodes[5];
                var enlace = nombre.childNodes[0].href;
                var textoEnlaceGrupo = nombre.childNodes[0].childNodes[0];
                var srcImage = imagen.childNodes[1].src;
                var spanDescripcion = descripcion.childNodes[1].childNodes[0];
                $("#tituloGrupo").append(document.createTextNode(textoEnlaceGrupo.nodeValue));
                $("#enlaceGrupo").attr("href",enlace);
                $("#enlaceGrupo").append(document.createTextNode(textoEnlaceGrupo.nodeValue));
                $(".fotoModal").attr("src",srcImage);
                $("#descripcionModal").append(document.createTextNode(spanDescripcion.nodeValue));
            }
            function verMasRumor(elemento){
                $("#tituloRumor").contents().filter(function(){return this.nodeType != 1;}).remove();
                $("#enlaceRumorModal").contents().filter(function(){return this.nodeType != 1;}).remove();
                $("#lugarRumor").contents().filter(function(){return this.nodeType != 1;}).remove();
                $("#descripcionModalRumor").contents().filter(function(){return this.nodeType != 1;}).remove();
                var padre = elemento.parentNode;
                var usuario = padre.childNodes[1].childNodes[1].childNodes[0];
                var grupo = padre.childNodes[1].childNodes[3].childNodes[0];
                var image = padre.childNodes[3].childNodes[1].src;
                var enlace = padre.childNodes[3].childNodes[4];
                var url = enlace.childNodes[0];
                var descripcion = padre.childNodes[5].childNodes[1].childNodes[0];
                var lugar = padre.childNodes[7].childNodes[1].childNodes[0];
                var id = padre.childNodes[11].value;
                var apoyo = padre.childNodes[13].value;
                if(apoyo == 0){
                    $("#desmentir").addClass('disabled');
                    $("#apoyar").removeClass('disabled');
                }else if(apoyo == 1){
                    $("#desmentir").removeClass('disabled');
                    $("#apoyar").addClass('disabled');
                }else{
                    $("#desmentir").removeClass('disabled');
                    $("#apoyar").removeClass('disabled');
                }
                $("#tituloRumor").append(document.createTextNode(usuario.nodeValue + " - " + grupo.nodeValue));
                $(".fotoModalRumor").attr("src",image);
                $("#enlaceRumorModal").attr("href",enlace.href);
                $("#idRumorModal").attr("value",id);
                $("#enlaceRumorModal").append(document.createTextNode(url.nodeValue))
                $("#lugarRumor").append(document.createTextNode(lugar.nodeValue));
                $("#descripcionModalRumor").append(document.createTextNode(descripcion.nodeValue));
            }
            function apoyar(elemento){
                var id = elemento.parentNode.parentNode.childNodes[3].childNodes[7].value;
                $("#"+id).attr("value",1);
                $("#desmentir").removeClass('disabled');
                $("#apoyar").addClass('disabled');
                $.ajax({
                        type: "POST",
                        url: 'php/controlador/apoyarController.php',
                        data: 'id=' + id
                    });
            }
            function desmentir(elemento){
                var id = elemento.parentNode.parentNode.childNodes[3].childNodes[7].value;
                $("#"+id).attr("value",0);
                $("#desmentir").addClass('disabled');
                $("#apoyar").removeClass('disabled');
                $.ajax({
                        type: "POST",
                        url: 'php/controlador/desmentirController.php',
                        data: 'id=' + id
                    });
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


                <div id="dropModal" class="modal-body">
                    <select name="grupos" id="grupos">
                        <option value="0">elige un grupo</option>
                        <?php
                        foreach($g_array as $key=>$value) {
                            echo "<option value=\"$key\">$value</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="modalSearchText" id="modalSearchText" placeholder="busca tu presa">
                    <button class="btn btn-primary" id="modalSearchButton">buscar</button>

                    <select name="modalSearchUser" id="modalSearchUser" class="searchResultOculto">
                        <option value="0">elige uno</option>
                    </select>
                    <div id="fotosModal" class="not-drop-files-generic">
                        <div id="fotosModalContent" class="drop-files-generic">
                            Arrastra una imagen
                        </div>
                    </div>

                    <br>
                    <textarea name="contenido" id="contenido" cols="30" rows="10" placeholder="Contenido"></textarea><br>
                    <input type="text" name="lugar" id="lugar" placeholder="Lugar"><br>
                    <input type="text" name="enlace" id="enlace" placeholder="Enlace"><br>
                </div>

                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                    <button onclick="nuevoRumor();" class="btn btn-primary" data-dismiss="modal">Enviar rumor</button>
                </div>
            </div>
            <script>
                document.getElementById("dropModal").ondragover = notDrop;
                document.getElementById("fotosModalContent").ondragover = over;
                document.getElementById("fotosModalContent").ondragleave = leave;

                var imageModal = null;
                var imageNameModal = null;
                // var imgBool=false;

                function dropImageModal(e) {//script para controlar el drag and drop de la imagen
                    e.stopPropagation(); // para la propagacion
                    e.preventDefault();
                    //aciones al arrastrar la imagen
                    //imagen
                    var files = e.dataTransfer.files;
                    if (!files[0].type.match('image.*')) {//si no ess una imegen
                        $("#fotosModalContent").html("tienes que arrastrar una imagen");
                        return false;
                    }
                    else {//si es una imagen
                        var reader = new FileReader();
                        reader.onload = (function(theFile) {//le asignamos un clouster a la carga de la iamgen
                            return function(e) {
                                imageModal = this.result;

                                $("#fotosModalContent").empty();

                                $("#fotosModalContent").append("<img class=\"imagen\" src=\"" + e.target.result + "\" alt=\"" + theFile.name + "\">");

                            };

                        })(files[0]);//parametros para el clouster autoinvocado
                        imageNameModal = files[0].name;//obtenemos el nombre de la imagen
                        reader.readAsDataURL(files[0]);
                    }
                    $(".drop-files-generic").css("background-color", "#cccccc");
                    $(".drop-files-generic").css("color", "black");
                    document.getElementById("dropModal").ondragover = notDrop;
                }
                document.getElementById("fotosModalContent").ondrop = dropImageModal;

                /****************************************************/
                $("#modalSearchButton").click(function() {
                    var ele = $("#modalSearchText").val();
                    var group=$("#grupos").val();
                    if (ele && group!=0){
                        if($(".modalResultSearchUser")){

                            $(".modalResultSearchUser").remove();
                        }
                        $.ajax({
                            type: "POST",
                            url: 'php/controlador/SearchInAnillosController.php',
                            data: 'data=' + ele+'&group='+group,
                            success: responseSearch,
                            error: errorSearch
                        });
                    }
                    else {
                        alert("tiene que introducir algun texto para buscar algo o selecciona un grupos");
                    }
                });
                function errorSearch(e){
                    alert('upss ha ocurrido algo inesperado');
                    console.log(e);
                }
                function responseSearch(e){

                    $('#modalSearchUser').removeClass('searchResultOculto');
                    var obj = JSON.parse(e);
                    var v_personajes = obj.personajes;
                    //console.log(v_personajes);

                    var v_usuarios = obj.usuarios;
                    //console.log(v_usuarios);
                    if(v_personajes){
                        for (var i in v_personajes){
                            $("#modalSearchUser").append("<option class='modalResultSearchUser' value=\""+i+"\">"+v_personajes[i]+"</option>");
                        }
                    }

                    if(v_usuarios){
                        for (var i in v_usuarios){
                            $("#modalSearchUser").append("<option class='modalResultSearchUser' value=\""+i+"\">"+v_usuarios[i]+"</option>")
                        }
                    }

                }

            </script>

            <div class="modal hide fade" id="contenidoModal">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">x</a>
                    <h3 id="tituloGrupo">Grupo</h3>
                </div>
                <div class="modal-body">
                    <div><span id="nombreModal"></span></div>
                    <div class="fotoCajaGrupoModal">
                        <img class="fotoModal"/>
                    </div>
                    <div class="CajaEnlaceGrupo">
                        <h3>Acceder:</h3><br>
                        <a href="" id="enlaceGrupo"></a>
                    </div>
                    <div><span id="descripcionModal"></span></div>
                </div>
                <div class="modal-footer">
                    <a class="btn pointer" data-dismiss="modal">Cerrar</a>
                </div>
            </div>
            <div>
                <?php
                    require_once 'php/controlador/MuestraGruposController.php';
                ?>
            </div>
    </body>
</html>