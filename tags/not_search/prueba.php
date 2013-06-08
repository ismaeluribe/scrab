<?php
require_once '/php/modelo/UserDAO.php';
require_once '/php/modelo/RumoresDAO.php';
require_once '/php/modelo/GruposDAO.php';
	$grupos = new GruposDAO();
	$rumores = new rumoresDAO();
	$tabbable = "";
	$contenido = "";
	$primero = true;
	$tabbable .= "<div class=\"tabbable tabs-left todoInicio\">\n<ul class=\"nav nav-tabs listaInicio\" style=\"position:fixed;\">\n";
	$contenido .= "<div class=\"tab-content centroInicio\">";
	$groups = $grupos->getGruposByUser(4);
	while($group = $groups->fetch_object()){
		if($primero){
			$tabbable .= "<li class=\"active\"><a onclick=\"scrollUp();\" href=\"#$group->nombre\" data-toggle=\"tab\">$group->nombre</a></li>\n";
			$primero = !$primero;
		}else{
			$tabbable .= "<li><a onclick=\"scrollUp();\" href=\"#$group->nombre\" data-toggle=\"tab\">$group->nombre</a></li>\n";
		}
		$contenido .= "<div class=\"tab-pane\" id=\"$group->nombre\">\n";
		$rumors = $rumores->getRumoresFromGrupos(4,$group->idgrupos);
		while ($rum = $rumors->fetch_object()){
			$contenido .= "<div class=\"cajaRumor caja\">\n<div id=\"nombreRumor\">\n<p>$rum->nombre</p>\n<p>$group->nombre</p>\n</div>\n<div class=\"fotoRumorCaja\">\n<img class=\"fotoRumor\" src=\"image/rumor/$rum->foto\" alt=\"$rum->foto\">\n<a href=\"http://$rum->enlace\" target=\"_blank\">$rum->enlace</a>\n</div>\n<div class=\"descripcionRumor\">\n<span>$rum->contenido</span>\n</div>\n<div class=\"lugarRumor\">\n<p>$rum->lugar</p>\n</div>\n<a href=\"#contenidoModalRumor\" data-toggle=\"modal\" title=\"Ver mas\" class=\"btn btn-small btn-primary verMasRumor\" onclick=\"verMasRumor(this)\">Ver más</a>\n<input type=\"hidden\" id=\"idRumor\" value=\"$rum->idrumores\">\n";
		}
		$contenido .= "</div>\n";
	}
	$tabbable .= "</ul>\n";
	echo $tabbable;
	echo $contenido;
?>