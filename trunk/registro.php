<?php
session_start();
//si esta session ya contiene esta variable volvemos automaticamente a la pagina de inciio
if (isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])) {
    //$objUser=new UserDAO() 
    header("location:inicio.php");
}
//fecha actual
$date= date('Y-m-d');
//fecha de hace 18 año en milisegundos
$oldDate=  strtotime( '-18 year' , strtotime ( $date ) ) ;
//fecha de hace 18 año en formato correcto yyyy-mm-dd
$oldDate =date ( 'Y-m-d' , $oldDate );
session_regenerate_id(true); //regeneramos el id por seguridad
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
                
                <form class="formularioReg modal" action="php/controlador/RegistroController.php?id=<?php echo session_id();?>" method="POST">
                    </br></br>
                    
                    <?php
                    if(isset($_GET['errno'])){
                        if($_GET['errno']<3)       echo '<h5>error en la base de datos</h5>';
                            elseif ($_GET['errno']==3) echo '<h5>el nombre de usuario ya existe</h5>';
                            else echo '<h5>error desconocido</h5>';
                        
                    }
                    
                    ?>
                    
                    <input type="text" placeholder="Nombre" name="nom" required="required"/>
                    </br>
                    <input type="text" placeholder="Primer Apellido" name="ape1" required="required"/>
                    </br>
                    <input type="text" placeholder="Segundo Apellido" name="ape2" />
                    </br>
                    <input type="date" value="<?php echo $oldDate; ?>" placeholder="Fecha de nacimiento" name="nac" required="required"/>
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
                    <input type="password" id="pass1" placeholder="Contraseña" name="pass" required="required" oninput="checkPass(this.value);"/>
                    </br>
                    <input type="password" id="pass2" placeholder="Repita la contraseña" name="pass2" required="required" disabled="disabled" oninput="getPass(this.value);"/>
                    </br>
                    <input type="checkbox" id="terminos" value="Terminos" required="required"/>&nbsp; Acepto los términos
                    </br>
                    </br>
                    <button type="submit" id="sbmit" class="btn btn-primary" name="submit" disabled="disabled" onsubmit="return datos();">Enviar</button>
                    <script>
                        function checkPass(para){
                       
                            if(/^\w{6,20}$/.test(para)){
                                //esta comprobacion deberia ser mucho mas grande, el unico inconveniente es que 
                                //al estar todavia en fase de pruebas es pesado estar introduciendo tantas contraseña
                                document.getElementById('pass2').disabled=false;
                            }else{
                                document.getElementById('pass2').disabled=true;
                            }
                        }
                        function getPass(para){
                            var val1=$("#pass1").val();
                            if(val1===para){
                                document.getElementById('sbmit').disabled=false;
                            }
                        }
                    </script>
                </form>
            </article>
        </div>
    </body>
</html>