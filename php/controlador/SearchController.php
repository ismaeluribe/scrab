<?php

session_start();

define('base_dir', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dir."/modelo/GruposDAO.php";
require_once base_dir.'/modelo/PersonajesDAO.php';
require_once base_dir.'/modelo/UserDAO.php';
require_once base_dir.'/modelo/modeloException/UserException.php';
require_once base_dir.'/modelo/modeloException/PersonajesException.php';
require_once base_dir.'/modelo/modeloException/GruposException.php';
require_once base_dir.'/modelo/modeloException/ModeloException.php';

/*
  //si no esta definida la session vamos al formulario de entrada
  if(!isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])){

  header("location: ../../index.php");
  }
  //si no estan definidas las variables del post vamos a la pagina de inicio
  elseif (!isset ($_POST['name'],$_POST['description'],$_POST['privacidad'])) {
  header("location: ../../inicio.php");
  }
 */

$name = $_POST['data'];
$id = $_SESSION['id'];

$objG = new GruposDAO();
$objP = new PersonajesDAO();
$objU = new UserDAO();
try{
    $totalArray = array('grupos' => $objG->getGroupDataByString($name, $id),
    'personajes' => $objP->getPersonajeDataByString($name),
        'usuarios' => $objU->getUserDataByString($name));
} catch (UserException $eu){
    echo $eu;
} catch (PersonajesException $ep){
    echo $ep;
} catch (GruposException $eg){
    echo $eg;
} catch (ModeloException $em){
    echo $em;
} catch (RuntimeException $er){
    echo $er;
}catch (Exception $e){
    echo $e;
}

echo json_encode($totalArray);
?>
