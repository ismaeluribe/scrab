<?php

/*
 * autor : nico
 * descripcion: codigo del controlador al recibir una peticion ajax
 */

$base_dir = realpath(dirname(__FILE__) .'/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/gruposDAO.php");


if(!isset($_POST['image'])){
    echo "ha llegado la imagen";
}
$dir='image/';
$name = $_POST['name'];
$description= $_POST['description'];

$obj = new GruposDAO();
$id=$obj->ultimoDato();

if($obj->insert_datos($id, 1, $name, $description)){
    echo "<h1>exito</h1>";
}
 else {
    "<h1>fracaso</h1>";
}

echo $id;
echo "<br>$name<br>$description";

//class GruposController {
    //put your code here
//}

?>
