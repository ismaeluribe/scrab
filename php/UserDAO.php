<?php
require('bd.php');
class UserDAO{
	function __construct(){// Constructor de la clase
		$obj = new bd();
		$this->db = $obj->getDB();
	}

	function userpass(){// Comprueba si el usuario y la contraseña introducidos son correctos
		$user = $_POST['user'];
		$pass = hash("sha512",$_POST['pass']);
		$result = $this->db->query("SELECT * FROM USUARIOS WHERE nombreUser = '$user' AND pass = '$pass'");
		if($result->num_rows != 0){
			$_SESSION['user'] = $_POST['user'];
			header ("location: inicio.php");
		}
	}

	function registroUsuario(){// Hace el registro primero de persona y despues de usuario
		$nombre = $_POST['nom'];
		$ape1 = $_POST['ape1'];
		$ape2 = $_POST['ape2'];
		$nac = $_POST['nac'];
		$sexo = $_POST['sexo'];
		$nombreUser = $_POST['user'];
		$email = $_POST['email'];
		$pass = hash("sha512",$_POST['pass']);
		$formato = null;
		$foto = null;
		$query1 = "INSERT INTO personas (tipo,nombre,apellido,apellido2,fechaNac,sexo,foto,formato,eliminado) VALUES('user','$nombre','$ape1',$ape2',$nac,'$sexo',$foto,'$formato',0)";
		$query2 = "SELECT MAX(idpersona) FROM personas";
		$query3 = "INSERT INTO usuarios VALUES (/*id_persona*/,'$nombreUser','$email',".time().",'',0,'$pass')";

	}

	function cerrarSesion(){
		session_destroy();
		header("location:index.php");
	}

	function nombreUser(){// Comprueba si el nombre de usuario está libre
		$user = $_POST['user'];
		$result = $this->db->query("SELECT * FROM USUARIOS WHERE nombreUser = '$user'");
		if($result->num_rows != 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

?>