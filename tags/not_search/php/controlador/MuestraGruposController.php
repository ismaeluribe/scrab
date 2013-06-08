<?php
	$grupos = new GruposDAO();
	$groups = $grupos->paraMostrar($_SESSION['id']);
	$mostrar = "";
	while($result = $groups->fetch_object()){
		$mostrar .= "<div class=\"cajaGrupo caja\">\n<div id=\"nombre\" />$result->nombre</div>\n<div class=\"fotoCajaGrupo\">\n<img class=\"fotoCajaGrupoImg\" src=\"image/grupo/$result->foto\" />\n</div>\n<div class=\"descripcionGrupo\">\n<span>".$result->descripcion."</span>\n</div>\n<a href=\"#contenidoModal\" data-toggle=\"modal\" title=\"Ver mas\" class=\"btn btn-small btn-primary verMas\" onclick=\"verMas(this)\">Ver más</a>\n</div>";
	}
	echo $mostrar;
?>