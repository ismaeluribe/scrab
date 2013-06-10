<?php
/*
* controlador para borrar un grupo se ha tener en cuenta que han de ser borrado
 * los personaajes del grupos, los rumores con los apoyos lanzados en el grupos
 * los registros de los usuarios pertenecientes al grupo
 * y por ultimo el grpo en si
*/
session_start();

define('base_dirEC', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirEC . "/modelo/AnillosDAO.php";
require_once base_dirEC. "/modelo/GruposDAO.php";
require_once base_dirEC. "/modelo/RumoresDAO.php";
require_once base_dirEC. "/modelo/PersonajesDAO.php";
require_once base_dirEC . '/modelo/modeloException/GruposException.php';
require_once base_dirEC . '/modelo/modeloException/ModeloException.php';


//si no esta definida la session vamos al formulario de entrada
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {
header("location: ../../index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset($_POST['grupo'])) {
header("location: ../../inicio.php");
}
try {
$idGroup = $_POST['grupo'];
$objA = new AnillosDAO();
$objG = new GruposDAO();
$objR = new RumoresDAO();
$objPer = new PersonajesDAO();

$objPer->deletePersonajeByGroupId($idGroup);
$objR->deleteRumorByGroupId($idGroup);
$objA->deleteDataByGroupId($idGroup);
$objG->deleteGroup($idGroup);


} catch (GruposException $eg) {
echo $eg;
} catch (ModeloException $em) {
echo $em;
} catch (RuntimeException $er) {
echo $er;
} catch (Exception $exc) {
echo $exc;
}

echo true;