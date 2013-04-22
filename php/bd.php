<?php

class bd{
	function __construct(){
		$usuario = "root";
		$password = "despliegue";
		$host = "localhost";
		$dbname = "scrab";
		$this->db = new mysqli($host,$usuario,$password,$dbname);
		if($this->db->connect_error){
		die("Error de conexion(".$this->db->connect_errno.")".$this->db->connect_error);
		}
	}
	
	function getDB(){
		return $this->db;
	}
}
?>