<?php

session_start();

define('base_dir', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dir . "/modelo/GruposDAO.php";
require_once base_dir . '/modelo/PersonajesDAO.php';
require_once base_dir . '/modelo/UserDAO.php';
require_once base_dir . '/modelo/EspiarDAO.php';
require_once base_dir . '/modelo/modeloException/UserException.php';
require_once base_dir . '/modelo/modeloException/PersonajesException.php';
require_once base_dir . '/modelo/modeloException/GruposException.php';
require_once base_dir . '/modelo/modeloException/EspiarException.php';
require_once base_dir . '/modelo/modeloException/ModeloException.php';


 //si no esta definida la session vamos al formulario de entrada
  if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {

  header("location: ../../index.php");
  }
  //si no estan definidas las variables del post vamos a la pagina de inicio
  elseif (!isset($_POST['data'])) {
  header("location: ../../inicio.php");
  } 


$name = $_POST['data'];
if(isset($_POST['user'])){
    $user=true;
}else $user=false;

$id = $_SESSION['id'];

$objG = new GruposDAO();
$objP = new PersonajesDAO();
$objU = new UserDAO();
$objEs = new EspiarDAO();
try {
    //obtenmos los personajes en base al patron
    $v_personajes = $objP->getPersonajeDataByString($name);
    if ($v_personajes) {
        //miramos si ya estaamos espiando al personaje en concreto
        foreach ($v_personajes as $clave => $valor) {
            //$valor[]=$objEs->getSpyByids($id, $clave);
            $v_personajes[$clave][] = $objEs->getSpyByids($id, $clave);
        }
    }

    //lo mismo
    $v_user = $objU->getUserDataByString($name);
    if ($v_user) {
        foreach ($v_user as $clave => $valor) {
            //$valor[]=$objEs->getSpyByids($id, $clave);
            $v_user[$clave][] = $objEs->getSpyByids($id, $clave);
        }
    }

    //en los grupos no hace falta controlar si perteneces ya
    //que la propia consulta es discriminatoria pera no mostrar los grupos 
    //a los que pertenece el usuario
    if(!$user){
        $v_grupos = $objG->getGroupDataByString($name, $id);
    }
    else{
        $v_grupos=null;
    }
    /* foreach ($v_grupos as $clave=>$valor){
      //$valor[]=$objEs->getSpyByids($id, $clave);
      $v_grupos[$clave][]=$objG->getPerteneceByIds($id, $clave);
      } */

    $totalArray = array('grupos' => $v_grupos,
        'personajes' => $v_personajes,
        'usuarios' => $v_user);
} catch (UserException $eu) {
    echo $eu;
} catch (PersonajesException $ep) {
    echo $ep;
} catch (GruposException $eg) {
    echo $eg;
} catch (EspiarException $es){
    echo $es;
}catch (ModeloException $em) {
    echo $em;
} catch (RuntimeException $er) {
    echo $er;
} catch (Exception $e) {
    echo $e;
}

echo json_encode($totalArray);
?>
