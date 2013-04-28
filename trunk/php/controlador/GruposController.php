<?php

/*
 * autor : nico
 * descripcion: codigo del controlador al recibir una peticion ajax
 */

$base_dir = realpath(dirname(__FILE__) .'/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/gruposDAO.php");


$dir='image/';
$name = $_POST['name'];
$var2= $_POST['description'];

$obj = new GruposDAO();
if($obj->insert(2, 1, $name,  $var2)){
    echo "<h1>exito</h1>";
}
 else {
    "<h1>fracaso</h1>";
}


//class GruposController {
    //put your code here
//}

?>
