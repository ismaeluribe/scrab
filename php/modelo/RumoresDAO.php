<?php

$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");

class rumoresDAO{

	// Alta de modificacion de rumores, sin comprobaciones ninguna

	function __construct(){
		$obj = new bd();
		$this->db = $obj->getDB();
		$this->registroRumor = $this->db->prepare("INSERT INTO rumores (anillos_grupos_idgrupo,anillos_usuarios_id_personas,foto,formato,lugar,enlace,personas_idpersonas) VALUES (?,?,?,?,?,?,?)");
	}

	function registroRumor(){
		$anillosIDgrupo = $_POST['anilloIDgrupo'];
		$idPersona = $_SESSION['id'];
		$lugar = $_POST['lugar'];
		$enlace = $_POST['enlace'];
		$trata = $_POST['trata'];
		$this->registroRumor->bind_param("iibsssi",$anillosIDgrupo,$idPersona,null,null,$lugar,$enlace,$trata);
		$this->registroRumor->execute();
	}
}

?>