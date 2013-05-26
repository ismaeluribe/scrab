<?php

$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");

class rumoresDAO{

	// Alta de modificacion de rumores, sin comprobaciones ninguna

    const tablaRumores='rumores';
    private $db;

	public function __construct(){
		$obj = new bd();
		$this->db = $obj->getDB();
		$this->registroRumor = $this->db->prepare("INSERT INTO rumores (anillos_grupos_idgrupos, anillos_usuarios_idpersonas, contenido, lugar, enlace, personas_idpersonas, foto)
		                                        VALUES (?,?,?,?,?,?,?)");
	}

	public function registroRumor($anillosIDgrupo,$idpersona,$contenido,$lugar,$enlace,$trataDe,$foto){
		$this->registroRumor->bind_param("iisssis", $anillosIDgrupo, $idpersona, $contenido, $lugar, $enlace, $trataDe,$foto);
		$this->registroRumor->execute();
	}
    public function getLastId(){
        $query = "SELECT MAX(idrumores) AS \"MAYOR\" FROM " . self::tablaRumores;
        $stm = $this->db->query($query);
        $dato = $stm->fetch_assoc();
        $dato['MAYOR']++;
        return $dato['MAYOR'];
    }

}

?>