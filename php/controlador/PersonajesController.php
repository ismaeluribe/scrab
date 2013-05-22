<?php

/*
 * controlador para la inserccion de personajes
 */
session_start();
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/PersonasDAO.php");
require_once ("{$base_dir}/modelo/PersonajesDAO.php");
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {
    header("location: index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
if (!isset($_POST['personajeData'])) {
    header("location: inicio.php");
}

$jsonArray = json_decode($_POST['personajeData'], true);
$jDataArray = $jsonArray['data'];
$jDataGroups = $jsonArray['groups'];
$img = false;
if (isset($jsonArray['img'])) {
    $jImage = $jsonArray['img'];
    $img = true;
}


$objPersonas = new PersonasDAO();
$objPersonajes = new PersonajesDAO();

foreach ($jDataArray as $clave => $valor) {//damos valores null a los campos que nos faltan
    if ($valor == "") {
        $jDataArray[$clave] = null;
    }
}

try {
    


$tipo = 'perso';
$nombre = $jDataArray[0];
$ape1 = $jDataArray[1];
$ape2 = $jDataArray[2];
$fechaNac = $jDataArray[3];
$sexo = $jDataArray[4];

$mote = $jDataArray[5];
$description = $jDataArray[6];

if ($img) {

    //cargamos las variables
    $file = $jImage['imageData'];
    $name = $jImage['imageName'];
    //obtenemos el archivo
    $data = explode(',', $file);
    //obtenemos la extencion de la foto .jpeg / .png
    $getMime = explode('.', $name);
    $mime = end($getMime);
    //codificamos el archivo mime
    $encodedData = str_replace(' ', '+', $data[1]);
    $decodedData = base64_decode($encodedData);
    //le ponermos nombre al archivo del grupo
    $nameImage = $mote . '.' . $mime;
}

$idUser = $_SESSION['id'];

$i=0;
$imgR=array();
foreach ($jDataGroups as $idGroup => $name) {
    
    $id = $objPersonas->getUltimoId();
    $objPersonas->registroPersonas($id, $tipo, $nombre, $ape1, $ape2, $fechaNac, $sexo);
    $objPersonajes->registroPersonajes($id, $mote, $description, $idUser, $idGroup);
    //cargamos la imagen en el servidor
    if ($img) {
            $name=$id . '_' . $nameImage;
            $name=  utf8_decode($name);
            $name=str_replace(" ","-",$name);
            
        if (file_put_contents('../../image/personaje/' . $name, $decodedData)) {
            //si ha salido bien la carga guardamios en la bd
            $name=  utf8_encode($name);
            $objPersonas->insertImage($name, $id);
            $imgR[] = true;
        } else {
            //si ha salido mal ..
            $imgR[] = false;
        }
    }
    $i++;
}
} catch (Exception $exc) {
    echo $exc;
}

$resultArray=array('cont'=>$i,'img'=>$imgR);
echo json_encode($resultArray);

?>
