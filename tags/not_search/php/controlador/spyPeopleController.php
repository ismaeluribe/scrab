<?php

/**
 * 
 *
 * @author nico
 * controladdor que lleva a cabo la accion de espiar
 */
session_start();

define('base_dir', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dir . "/modelo/EspiarDAO.php";
require_once base_dir . '/modelo/modeloException/EspiarException.php';
require_once base_dir . '/modelo/modeloException/ModeloException.php';


//si no esta definida la session vamos al formulario de entrada
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {

    header("location: ../../index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset($_POST['data'],$_POST['action'])) {
    header("location: ../../inicio.php");
}
$result=true;

try {
    $objE=new EspiarDAO();
    $id_user=$_SESSION['id'];
    $id_espiado=$_POST['data'];
    $action=$_POST['action'];
    $objE->espiarPeople($id_user, $id_espiado, $action);
    
}  catch (EspiarException $es){
    $result=false;
} catch (ModeloException $em){
    $result=false;
} catch (RuntimeException $er){
    $result=false;
} catch (Exception $e){
    $result=false;
}
//$actionArray=array("respuesta"=>$action,"id"=>);
echo json_encode($action);

?>
