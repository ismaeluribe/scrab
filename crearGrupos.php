<!--
Nicolas Quiceno
Pagina para la creacion de grupos

-->
<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Introduce tu grupos</title>
        <script src="js/libs/jquery/jquery.js"></script>
        <style type="text/css">
            #drop-files {
                width: 300px;
                height: 150px;
                background: #cccccc;
                border-radius: 10px;
                border: 4px dashed rgba(0,0,0,0.2);
                /*color: #ccccff;*/
                padding: 25px 0 0 0;
                text-align: center;
                font-size: 2em;
                font-weight: bold;
                font-family: arial;
                margin:  60px auto;
            }
            #not-drop-files {
                width: 400px;
                height: 300px;
                background-color: #ffffff;
                border-radius: 10px;
                border: 4px dashed rgba(0,0,0,0.2);

            }
            .imagen{
                width: auto;
                height: 100px;
                max-width: 300px;
                opacity: 0.4;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <form action="php/controlador/GruposController.php" method="POST">
            <!-- id/propietario/nombre/fecha/descripcion/privacidad-->
            <input type="text" name="nombreGrupo" placeholder="Nombre del grupo">
            <textarea  name="descripcion">
                Descripcion
            </textarea>

            <input type="radio" name="privacidad" value="privado">
            <input type="radio" name="privacidad" value="publico">
            <div id="cargaImg"> arrasta para soltar</div>
            <button id="btnSubmit" name="envio"> envio </button>
            <button type="reset">reset</button>


        </form>
        <img src="" alt="">
        <div id="not-drop-files">
            <div id="drop-files"> 
                Arrastra una imagen
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
            document.getElementById("not-drop-files").ondragover = notDrop;

            //funciones para cambiar el estado de los elemntos
            function over() {
                document.getElementById("not-drop-files").ondragover = copyDrop;
                $("#drop-files").css("color", "#006699");
                $("#drop-files").css("background-color", "#cccfff");
            }
            function leave() {
                $("#drop-files").css("background-color", "#cccccc");
                $("#drop-files").css("color", "black");
                document.getElementById("not-drop-files").ondragover = notDrop;
            }

            //asinamos a los manejadores las funciones para cambiar los elemtnos 
            //demanera que se permita dejar el elemento 
            // al entrar dentro del recuadro
            document.getElementById("drop-files").ondragover = over;
            document.getElementById("drop-files").ondragleave = leave;


            function drop(e){
               e.stopPropagation(); // para la propagacion
               e.preventDefault(); 
               //aciones al arrastrar la imagen
               
               
               //imagen
               var files = e.dataTransfer.files;7
               //var files = e.target.files;
               if(!files[0].type.match('image.*')){
                   $("#drop-files").html("tienes que arrastrar una imagen");
                   return false;
               }
               else{
                   $("#drop-files").html("imagen arrastrada");
               
                   var reader= new FileReader();
                   reader.onload=(function(theFile) {
                        return function(e){
                            $("#drop-files").empty();
                           //document.getElementById("drop-files").innerHtml="";
                            $("#drop-files").append("<img class=\"imagen\" src=\""+e.target.result+"\" alt=\""+theFile.name+"\">");
                        
                        };
                            
               })(files[0]);
              reader.readAsDataURL(files[0]);
              alert (files[0]);
              var a=JSON.stringify(files[0]);
              alert (a);
            }
            $("#drop-files").css("background-color", "#cccccc");
            $("#drop-files").css("color", "black");
            document.getElementById("not-drop-files").ondragover = notDrop;
            }
            document.getElementById("drop-files").ondrop = drop;
           // var col = $("#drop-files");
          
          // document.getElementById("drop-files").ondragend=notDrop;
          // col.addEventListener('dragend', notDrop, false);
            $("#btnSubmit").click(function (){
                $.ajax({
                url: '/ruta/hasta/pagina.php',
                type: 'POST',
                async: true,
                data: 'parametro1=valor1&parametro2=valor2',
                success: procesaRespuesta,
                error: muestraError
                });
            });
        </script>


    </body>
</html>
