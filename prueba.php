<?php
	require_once 'php/modelo/GruposDAO.php';
	$grupos = new GruposDAO();
	$groups = $grupos->getGroupDataByUserId(4);
	while($result = $groups->fetch_object()){
		$mostrar .= "<div class=\"cajaGrupo caja\">\n<div><img id=\"nombre\" />\n</div>\n<div class=\"fotoCajaGrupo\">\n<img class=\"fotoCajaGrupoImg\" src=\"image/grupo/$result->foto\" />\n</div>\n<div class=\"descripcionGrupo\">\n<span>".$result->descripcion."</span>\n</div>\n<a href=\"#contenidoModal\" data-toggle=\"modal\" title=\"Ver mas\" class=\"btn btn-small btn-primary verMas\" onclick=\"verMas(this)\">Ver más</a>";
	}
	echo $mostrar;
?>