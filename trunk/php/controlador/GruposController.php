<?php

/*
 * autor : nico
 * descripcion: codigo del controlador al recibir una peticion ajax
 */

$base_dir = realpath(dirname(__FILE__) . '/..');
//echo $base_dir;
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/gruposDAO.php");



//$dirImg=  realpath(dirname(__FILE__).'../../image/grupo');
//echo $dirImg;



if (isset($_POST['image']) && isset($_POST['imageName'])) {
    $file = $_POST['image'];
    $name = $_POST['imageName'];
    $data = explode(',', $file);
    // Encode it correctly
    $encodedData = str_replace(' ', '+', $data[1]);
    $decodedData = base64_decode($encodedData);
    if (file_put_contents( '../../image/grupo/'.$name, $decodedData)) {
        //echo $dirImg;
        echo $name . ":uploaded successfully";
    } else {
        // Show an error message should something go wrong.
        echo "Something went wrong. Check that the file isn't corrupted";
    }


    //echo "ha llegado la imagen";
}


$name = $_POST['name'];
$description = $_POST['description'];

$obj = new GruposDAO();
$id = $obj->ultimoDato();

if ($obj->insert_datos($id, 1, $name, $description)) {
    echo "<h1>exito</h1>";
} else {
    "<h1>fracaso</h1>";
}

echo $id;
echo "<br>$name<br>$description";

//class GruposController {
//put your code here
//}
?>
