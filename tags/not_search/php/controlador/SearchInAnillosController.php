<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nico
 * Date: 26/05/13
 * Time: 21:18
 * To change this template use File | Settings | File Templates.
 */

session_start();

define('base_dirSAni', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirSAni . "/modelo/AnillosDAO.php";
require_once base_dirSAni . '/modelo/modeloException/AnillosException.php';
require_once base_dirSAni . '/modelo/modeloException/ModeloException.php';


//si no esta definida la session vamos al formulario de entrada
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {

    header("location: ../../index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset($_POST['data'])) {
    header("location: ../../inicio.php");
}
try{
    $name=$_POST['data'];
    $idGroup=$_POST['group'];
    $objA=new AnillosDAO();
    $v_personajes=$objA->getPersonajeDataByAnillosGroup($name,$idGroup);
    $v_user=$objA->getUserDataByAnillosGroup($name,$idGroup);
    $totalArray = array(
        'personajes' => $v_personajes,
        'usuarios' => $v_user);

}catch (AnillosException $ea){
    echo $ea;
}catch (ModeloException $em){
    echo $em;
}catch (Exception $e){
    echo $e;
}

echo json_encode($totalArray);






?>