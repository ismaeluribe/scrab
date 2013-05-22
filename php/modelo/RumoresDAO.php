<?php

$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");

class rumoresDAO{

	// Alta de modificacion de rumores, sin comprobaciones ninguna

	function __construct(){
		$obj = new bd();
		$this->db = $obj->getDB();
		$this->registroRumor = $this->db->prepare("INSERT INTO rumores (anillos_grupos_idgrupos,anillos_usuarios_idpersonas,contenido,foto,lugar,enlace,personas_idpersonas) VALUES (?,?,?,?,?,?,?)");
	}

	function registroRumor($anillosIDgrupo,$lugar,$enlace,$trata){
		$idPersona =1;
		$foto = null;
		$this->registroRumor->bind_param("iissssi", $anillosIDgrupo, $idPersona, $trata, $foto, $lugar, $enlace, $idPersona);
		$this->registroRumor->execute();
	}
}

?>