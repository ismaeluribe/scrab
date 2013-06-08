<?php

	session_start();
	$base_dir = realpath(dirname(__FILE__) . '/..');
	require_once("{$base_dir}/commons/bd.php");
	require_once("{$base_dir}/modelo/PersonasDAO.php");
	require_once("{$base_dir}/modelo/UserDAO.php");
	if(!isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])){
		header("location: ../../index.php");
	}
	$name = $_POST['name'];
	$ape1 = $_POST['apellido'];
	$ape2 = $_POST['apellido2'];
	$mail = $_POST['mail'];
	$privacidad = $_POST['privacidad'];
	$user = new UserDAO();
	$persona = new PersonasDAO();
	$user->modificaMail($mail,$_SESSION['id'],$privacidad);
	$persona->modificaPersona($name,$ape1,$ape2,$_SESSION['id']);
	echo true;

?>