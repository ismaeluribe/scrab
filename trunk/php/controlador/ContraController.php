<?php

	session_start();
	$base_dir = realpath(dirname(__FILE__) . '/..');
	require_once("{$base_dir}/commons/bd.php");
	require_once("{$base_dir}/modelo/UserDAO.php");
	if(!isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])){
		header("location: ../../index.php");
	}
	$user = new UserDAO();
	if(hash("sha512", $_POST['antigua']) == $_SESSION['pass']){
		$user->cambiarContra(hash("sha512", $_POST['nueva1']),$_SESSION['id']);
	}
	header("location: ../../configuracion.php");

?>