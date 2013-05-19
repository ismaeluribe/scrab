<?php
session_start();
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/php/modelo/rumoresDAO.php");
	$rumor = new rumoresDAO();
	$rumor->registroRumor($_POST['grupo'],$_POST['lugar'],$_POST['enlace'],$_POST['contenido']);
	/*$rumor->registroRumor($_POST['grupo'],$_POST['lugar'],$_POST['enlace'],$_POST['contenido']);*/

?>