<?php
	$user = new UserDAO();
	//$_SESSION['id']
	$usuarios = $user->getEstado($_SESSION['id']);
	$mostrar = "";
	while($result = $usuarios->fetch_object()){
		if($result->estado != null){
			$estado = $result->estado;
		}else{
			$estado = "Sin estado";
		}
		$mostrar .= "<div class=\"cajaUser caja\">\n<div class=\"fotoCajaUser\"><img class=\"fotoCajaUserImg\" src=\"image/usuario/$result->foto\" />\n</div>\n<div class=\"textosCaja\">\n<div class=\"nombreCaja\">\n<span>".$result->nombre."</span>\n</div>\n<div class=\"nombreCaja\">\n<span>".$result->apellido." ".$result->apellido2."</span>\n</div>\n<div class=\"nombreCaja descripcionCaja\">\n<span>".$estado."</span>\n</div>\n</div>\n</div>";
	}
	echo $mostrar;
?>