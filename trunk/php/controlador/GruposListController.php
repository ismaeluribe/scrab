<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/gruposDAO.php");

//si no esta definida la session vamos al formulario de entrada
if (!isset($_SESSION['user'], $_SESSION['id'], $_SESSION['pass'], $_SESSION['email'])) {
    header("location: index.php");
}
//si no estan definidas las variables del post vamos a la pagina de inicio
if (!isset($_POST['groupListBy'])) {
    header("location: inicio.php");
}
$obj=new GruposDAO();
$arrayGrupos=$obj->getGroupDataByUserId($_SESSION['id']);
//echo utf8_encode($arrayGrupos);

if($arrayGrupos){
    echo json_encode($arrayGrupos);
}
else{
    echo null;
}/*
echo '<pre>';
print_r($_POST);
echo '</pre>'*/
?>
