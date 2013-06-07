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

/*
SELECT apoyo FROM apoyos WHERE rumores_idrumores = 5 AND usuarios_personas_idpersonas = 4 AND apoyo = 1
*/

		function checkApoyadoDesmentido($idRumor, $idUser){
			$query = "SELECT apoyo FROM apoyos WHERE rumores_idrumores = $idRumor AND usuarios_personas_idpersonas = $idUser";
			$result = $this->db->query($query);
			if($result->num_rows != 0){
				$valor = $result->fetch_object();
				$return = $valor->apoyo;
			}else{
				$return = 2;
			}
			return $return;
		}

		function apoyar($idRumor, $idUser){
			$apoyo = $this->checkApoyadoDesmentido($idRumor, $idUser);
			if($apoyo == 2){
				$query = "INSERT INTO apoyos VALUES($idUser,$idRumor,now(),1)";
			}else{
				$query = "UPDATE apoyos SET apoyo = 1, fecha = now() WHERE rumores_idrumores = $idRumor AND usuarios_personas_idpersonas = $idUser";
			}
			echo $query;
			$this->db->query($query);
		}

		function desmentir($idRumor, $idUser){
			$apoyo = $this->checkApoyadoDesmentido($idRumor, $idUser);
			if($apoyo == 2){
				$query = "INSERT INTO apoyos VALUES($idUser,$idRumor,now(),0)";
			}else{
				$query = "UPDATE apoyos SET apoyo = 0, fecha = now() WHERE rumores_idrumores = $idRumor AND usuarios_personas_idpersonas = $idUser";
			}
			echo $query;
			$this->db->query($query);
		}
	}

?>