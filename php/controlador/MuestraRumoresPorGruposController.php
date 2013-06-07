<?php
	$grupos = new GruposDAO();
	$rumores = new rumoresDAO();
	$apoyos = new ApoyosDAO();
	$tabbable = "";
	$contenido = "";
	$primero = true;
	$segundo = true;
	function quitar_tildes($cadena) {
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}
	$tabbable .= "<div class=\"tabbable tabs-left todoInicio\">\n<ul class=\"nav nav-tabs listaInicio\" style=\"position:fixed;\">\n";
	$contenido .= "<div class=\"tab-content centroInicio\">";
	$groups = $grupos->getGruposByUser($_SESSION['id']);
	while($group = $groups->fetch_object()){
		if($primero){
			$tabbable .= "<li class=\"active\"><a onclick=\"scrollUp();\" href=\"#".str_replace(' ','',quitar_tildes($group->nombre))."\" data-toggle=\"tab\">$group->nombre</a></li>\n";
			$contenido .= "<div class=\"tab-pane active\" id=\"".str_replace(" ","",quitar_tildes($group->nombre))."\">\n";
			$primero = false;
		}else{
			$tabbable .= "<li><a onclick=\"scrollUp();\" href=\"#".str_replace(" ","",quitar_tildes($group->nombre))."\" data-toggle=\"tab\">$group->nombre</a></li>\n";
			$contenido .= "<div class=\"tab-pane\" id=\"".str_replace(" ","",quitar_tildes($group->nombre))."\">\n";
		}
		$rumors = $rumores->getRumoresFromGrupos($_SESSION['id'],$group->idgrupos);
		while ($rum = $rumors->fetch_object()){
			$apoyar = $apoyos->checkApoyadoDesmentido($rum->idrumores,$_SESSION['id']);
			$contenido .= "<div class=\"cajaRumor caja\">\n<div id=\"nombreRumor\">\n<p>$rum->nombre</p>\n<p>$group->nombre</p>\n</div>\n<div class=\"fotoRumorCaja\">\n<img class=\"fotoRumor\" src=\"image/rumor/$rum->foto\" alt=\"$rum->foto\">\n<br><a href=\"http://$rum->enlace\" target=\"_blank\">$rum->enlace</a>\n</div>\n<div class=\"descripcionRumor\">\n<span>$rum->contenido</span>\n</div>\n<div class=\"lugarRumor\">\n<p>$rum->lugar</p>\n</div>\n<a href=\"#contenidoModalRumor\" data-toggle=\"modal\" title=\"Ver mas\" class=\"btn btn-small btn-primary verMasRumor\" onclick=\"verMasRumor(this)\">Ver más</a>\n<input type=\"hidden\" id=\"idRumor\" value=\"$rum->idrumores\">\n<input id=\"$rum->idrumores\" type=\"hidden\" id=\"apoyado\" value=\"$apoyar\">\n</div>\n";
		}
		if($rumors->num_rows == 0){
			$contenido .= "<h3>No hay rumores en este grupo</h3>";
		}
		$contenido .= "</div>\n";
	}
	
	$tabbable .= "</ul>\n";
	echo $tabbable;
	echo $contenido;
?>