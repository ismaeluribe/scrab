<?php
	session_start();
	require_once '../modelo/ApoyosDAO.php';
	$idRumor = $_POST['id'];
	$idUsuario = $_SESSION['id'];
	$apoyo = new ApoyosDAO();
	$apoyo->desmentir($idRumor,$idUsuario);
?>