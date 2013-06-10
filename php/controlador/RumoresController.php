<?php
session_start();
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/rumoresDAO.php");

if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {

    header("location: ../../index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset($_POST['grupo'],$_POST['lugar'],$_POST['enlace'],$_POST['contenido'])) {
    header("location: ../../inicio.php");
}

$idPersona=$_SESSION['id'];
$idGroup=$_POST['grupo'];
$contenido=$_POST['contenido'];
$enlace=$_POST['enlace'];
$lugar=$_POST['lugar'];
$trataDe=$_POST['tratade'];
$imageFile=$_POST['imageFileM'];
$imageName=$_POST['imageNameM'];


$rumor = new rumoresDAO();
$idR=$rumor->getLastId();
$slug='imageRumor_id';
echo $imageFile;
echo '<br>';
echo $imageName;

if ($imageName!='null'&& $imageFile!='null') {

    //cargamos las variables
    $file = $imageFile;
    $name = $imageName;
    //obtenemos el archivo
    $data = explode(',', $file);
    //obtenemos la extencion de la foto .jpeg / .png
    $getMime = explode('.', $name);
    $mime = end($getMime);
    //codificamos el archivo mime
    $encodedData = str_replace(' ', '+', $data[1]);
    $decodedData = base64_decode($encodedData);
    //le ponermos nombre al archivo del grupo
    $nameImage = $slug . '_' .$idR.'.'. $mime;
    if (file_put_contents( '../../image/rumor/'.$nameImage, $decodedData)) {
        //si ha salido bien la carga le damos este valor a esta variable

    } else {
        //si ha salido mal ..
        $nameImage ='noimage.png';
    }

}

$rumor->registroRumor($idGroup,$idPersona,$contenido,$lugar,$enlace,$trataDe,$nameImage);


?>