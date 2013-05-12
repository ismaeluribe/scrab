<?php

/*
 * autor : nico
 * descripcion: codigo del controlador al recibir una peticion ajax
 */

$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/gruposDAO.php");

$nameGroup = $_POST['name'];
$description = $_POST['description'];
$nameImage=null;

$obj = new GruposDAO();

if (($_POST['image']!='null') && ($_POST['imageName']!='null')) {

    //cargamos las variables
    $file = $_POST['image'];
    $name = $_POST['imageName'];
    
    
    //obtenemos el archivo
    $data = explode(',', $file);
    
    //obtenemos la extencion de la foto .jpeg / .png
    $getMime = explode('.', $name);
    $mime = end($getMime);
    
    //codificamos el archivo mime
    $encodedData = str_replace(' ', '+', $data[1]);
    $decodedData = base64_decode($encodedData);
    
    //le ponermos nombre al archivo del grupo
    $nameImage=$nameGroup.'.'.$mime;
    
    
    
    if (file_put_contents( '../../image/grupo/'.$nameImage, $decodedData)) {
        $mensaje = "imagen subida con exito";
    } else {
        $mensaje ="se a liado parda con la imagen";
    }
    
}

$id = $obj->ultimoDato();

if ($obj->insert_datos($id, 1, $nameGroup, $description,$nameImage)) {
    echo "<h1>exito</h1>";
} else {
    "<h1>fracaso</h1>";
}

?>
