<?php

/*
 * controlador para añadir un suario a un grupo
 */
session_start();

define('base_dirGCA', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirGCA . "/modelo/GruposDAO.php";
require_once base_dirGCA . '/modelo/modeloException/GruposException.php';
require_once base_dirGCA . '/modelo/modeloException/ModeloException.php';


//si no esta definida la session vamos al formulario de entrada
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {

    header("location: ../../index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
elseif (!isset($_POST['data'], $_POST['action'])) {
    header("location: ../../inicio.php");
}
try {
    $idUser = $_SESSION['id'];
    $idGroup = $_POST['data'];
    $objG = new GruposDAO();
    $i = $objG->insertUserInAnillos($idUser, $idGroup);
} catch (GruposException $eg) {
    echo $eg;
} catch (ModeloException $em) {
    echo $em;
} catch (RuntimeException $er) {
    echo $er;
} catch (Exception $exc) {
    echo $exc;
}


echo $i;
?>
