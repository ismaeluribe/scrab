<?php
/*
 * Controlador para recuperar la información personal del usuario
*/
	$base_dir = realpath(dirname(__FILE__) . '/..');
	require_once("{$base_dir}/modelo/PersonasDAO.php");
	require_once ("{$base_dir}/modelo/UserDAO.php");

	class GetUserDataController{
		function __construct($id){
			$this->persona = new PersonasDAO();
			$this->user = new UserDAO();
			$this->id = $id;
		}

		function getUser(){
			return $this->user->getUserInfo($this->id);
		}

		function getPersona(){
			return $this->persona->getDataById($this->id);
		}
	}

?>