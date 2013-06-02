<?php 

$base_dir = realpath(dirname(__FILE__) . '/..');
require_once("{$base_dir}/commons/bd.php");
//require_once('modeloException/ApoyosException.php');

	class ApoyosDAO{

		private $db;

		public function __construct(){
			$obj = new bd();
			$this->db = $obj->getDB();
		}

		function apoyar($idRumor, $idUser){
			$query = "INSERT INTO apoyos VALUES($idUser,$idRumor,now(),1)";
			echo $query;
			$this->db->query($query);
		}
	}

?>