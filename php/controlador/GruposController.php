<?php

/*
 * autor : nico
 * descripcion: codigo del controlador al recibir una peticion ajax
 */
session_start();
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/gruposDAO.php");

//si no esta definida la session vamos al formulario de entrada
if(!isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])){
    
    header("location: index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset ($_POST['name'],$_POST['description'],$_POST['privacidad'])) {
    header("location: inicio.php");
}

$nameGroup = $_POST['name'];
$description = $_POST['description'];
$idUser=$_SESSION['id'];
$privacidad=$_POST['privacidad'];
$nameImage=null;
$img=null;
$obj = new GruposDAO();
//si es distinto de null significa que han subido fotos
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
    $nameImage=utf8_decode($nameGroup).'.'.$mime;
    //cargamos la imagen en el servidor
    if (file_put_contents( '../../image/grupo/'.$nameImage, $decodedData)) {
        //si ha salido bien la carga le damos este valor a esta variable
        $img = true;
    } else {
        //si ha salido mal ..
        $img = false;
    }
}

$id = $obj->ultimoDato();

//si hemos insertado los datos
if ($obj->insert_datos($id, $idUser, $nameGroup, $description,$nameImage,$privacidad)) {
    $data=true;
} else {//si se ha producido un error
    $data=false;
}

$arr=array('img'=>$img,'data'=>$data);
echo json_encode($arr);

?>
