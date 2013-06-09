<?php

/*
 * autor : nico
 * descripcion: codigo del controlador al recibir una peticion ajax
 */
session_start();
define('base_dirA', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirA."/modelo/UserDAO.php";
require_once base_dirA."/modelo/PersonasDAO.php";
require_once base_dirA."/commons/CaracteresRaros.php";

//si no esta definida la session vamos al formulario de entrada
if(!isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])){

    header("location: ../../index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset ($_POST['image'],$_POST['imageName'],$_POST['estado'])) {
    header("location: ../../inicio.php");
}


$idUser=$_SESSION['id'];
$estado=$_POST['estado'];
$nameImage='noimage.png';
$img=false;
$obj = new UserDAO();
$objPersonas= new PersonasDAO();
/*echo $_POST['image'];
echo '<br>';
echo $_POST['imageName'];*/
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

    $nameImage=utf8_decode($_SESSION['user']).'.'.$mime;
    $nameImage=str_replace(" ","-",$nameImage);
    //$objCarecter=new CaracteresRaros();
    //$nameImage=$objCarecter->remplaceCaracteres($nameImage);

    //cargamos la imagen en el servidor
    if (file_put_contents( '../../image/usuario/'.$nameImage, $decodedData)) {
        //si ha salido bien la carga le damos este valor a esta variable
        $img = true;
    }
    $nameImage=  utf8_encode($nameImage);

}
$response=true;
try{
    if($estado!=''){
        $obj->setEstadoByUserId($idUser,$estado);
    }
    if(($nameImage!='noimage.png'||$nameImage!='noimage.jpg')&&$img){
        $objPersonas->setImageByUserId($idUser,$nameImage);
    }
}catch (UserException $eu){
   // echo $eu;
    $response=false;
}
catch (PersonasException $ep){
    // echo $ep;
    $response=false;
}catch (ModeloException $em){
    $response=false;
}
catch (RuntimeException $er){
    //echo $er;
    $response=false;
}

echo json_encode($response);

?>
