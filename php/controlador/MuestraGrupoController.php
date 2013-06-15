<?php

	$grupoObj = new GruposDAO();
	$userObj = new UserDAO();
	$rumorObj = new rumoresDAO();
	$apoyos = new ApoyosDAO();
	$personajesObj = new PersonajesDAO();
	$grupo = $grupoObj->getInfoGrupo($id);
	$users = $userObj->getPorAnillo($id);
	$rumores = $rumorObj->getRumoresFromGrupos($_SESSION['id'],$id);
	$personajes = $personajesObj->getPersonajesFromGrupos($id);
	$contenido = "";
	$group = $grupo->fetch_object();
	$contenido .= "<div class=\"datosGrupo\">\n<div class=\"grupoPerfil\">\n<img src=\"image/grupo/$group->foto\" alt=\"$group->foto\">\n<h4>$group->nombre</h4>\n<span>$group->descripcion</span>\n</div>\n</div><div class=\"contenidoGrupo\">\n<div class=\"tabbable\">\n<ul class=\"nav nav-tabs\">\n<li class=\"active\"><a href=\"#usuarios\" data-toggle=\"tab\">Usuarios</a></li>\n<li><a href=\"#rumores\" data-toggle=\"tab\">Rumores</a></li>\n<li><a href=\"#personajes\" data-toggle=\"tab\">Personajes</a></li>\n</ul>\n<div class=\"tab-content\">\n<div class=\"tab-pane active\" id=\"usuarios\">\n";
	while($resultUsuario = $users->fetch_object()){
		$contenido .= "<div class=\"cajaUser caja\">\n<div class=\"fotoCajaUser\">\n<img class=\"fotoCajaUserImg\" src=\"image/usuario/$resultUsuario->foto\">\n</div>\n<div class=\"textosCaja\">\n<div class=\"nombreCaja\">\n<span>$resultUsuario->nombre</span>\n</div>\n<div class=\"nombreCaja\">\n<span>".$resultUsuario->apellido." ".$resultUsuario->apellido2."</span>\n</div>\n<div class=\"nombreCaja descripcionCaja\">\n<span>$resultUsuario->estado</span>\n</div>\n</div>\n</div>\n";
	}
	$contenido .= "</div>\n<div class=\"tab-pane\" id=\"rumores\">\n";
	while($resultRumores = $rumores->fetch_object()){
		$apoyar = $apoyos->checkApoyadoDesmentido($resultRumores->idrumores,$_SESSION['id']);
		$groupNom = $grupoObj->getNombreFromRumor($resultRumores->idrumores)->nombre;
		$contenido .= "<div class=\"cajaRumor caja rumorGrupo\">\n<div id=\"nombreRumor\">\n<p>$resultRumores->nombre</p>\n<p>$groupNom</p>\n</div>\n<div class=\"fotoRumorCaja\">\n<img class=\"fotoRumor\" src=\"image/rumor/$resultRumores->foto\" alt=\"$resultRumores->foto\">\n<br><a href=\"http://$resultRumores->enlace\" target=\"_blank\">$resultRumores->enlace</a>\n</div>\n<div class=\"descripcionRumor\">\n<span>$resultRumores->contenido</span>\n</div>\n<div class=\"lugarRumor\">\n<p>$resultRumores->lugar</p>\n</div>\n<a href=\"#contenidoModalRumor\" data-toggle=\"modal\" title=\"Ver mas\" class=\"btn btn-small btn-primary verMasRumor\" onclick=\"verMasRumor(this)\">Ver más</a>\n<input type=\"hidden\" id=\"idRumor\" value=\"$resultRumores->idrumores\">\n<input id=\"$resultRumores->idrumores\" type=\"hidden\" id=\"apoyado\" value=\"$apoyar\">\n</div>\n";
	}
	$contenido .= "</div>\n<div class=\"tab-pane\" id=\"personajes\">\n";
	while ($resultPersonaje = $personajes->fetch_object()){
		$contenido .= "<div class=\"cajaUser caja\">\n<div class=\"fotoCajaUser\">\n<img class=\"fotoCajaUserImg\" src=\"image/personaje/$resultPersonaje->foto\">\n</div>\n<div class=\"textosCaja\">\n<div class=\"nombreCaja\">\n<span>$resultPersonaje->mote</span>\n</div>\n<div class=\"nombreCaja\">\n<span>$resultPersonaje->nombre</span>\n</div>\n<div class=\"nombreCaja\">\n<span>".$resultPersonaje->apellido." ".$resultPersonaje->apellido2."</span>\n</div>\n<div class=\"nombreCaja descripcionCaja\">\n<span>$resultPersonaje->descripcion</span>\n</div>\n</div>\n</div>\n";
	}
	$contenido .= "</div>\n</div>\n</div>\n</div>\n";
	echo $contenido;

?>