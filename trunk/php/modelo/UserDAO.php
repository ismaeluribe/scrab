<?php
//debido a un problema con el sistema de ruta de window al estar en una maquina virtual
//hemos de tener que hacer a mano las rutas de la aplicacion 
//la otra opcion es cambiar el path de php.ini

$ds="/";//variable con el separador
$base_dir= realpath(dirname(__FILE__).$ds.'..').$ds;
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}clasesImportantes{$ds}bd.php");
class UserDAO{

    //definimos las tablas con las que intercambian datos los usuarios como constantes
   const tablaPersonas="personas";
   const tablaUsuarios="usuarios";
   //definimos las propiedades
   private $db;
   
    function __construct(){// Constructor de la clase
		$obj = new bd();
		$this->db = $obj->getDB();
	}

    function userpass(){// Comprueba si el usuario y la contraseña introducidos son correctos
		$user = $_POST['user'];
		$pass = hash("sha512",$_POST['pass']);
		$result = $this->db->query("SELECT * FROM usuarios WHERE nombreUser = '$user' AND pass = '$pass'");
		if($result->num_rows != 0){
			$_SESSION['user'] = $_POST['user'];
			header ("location: inicio.php");
		}
	}
        
       
    function registroUsuario(){ // Hace el registro primero de persona y despues de usuario
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
                $query1 = "SELECT MAX(idpersonas) AS \"mayor\" FROM personas";
		$result1 = $this->db->query($query1);
		$mayor = $result1->fetch_assoc();
		$mayor1 = $mayor['mayor']+1;
		$query2 = "INSERT INTO personas VALUES($mayor1,'user','$nombre','$ape1','$ape2','$nac','$sexo','$foto','$formato',0)";
		$result = $this->db->query($query2);
		$query3 = "INSERT INTO usuarios VALUES ($mayor1,'$nombreUser','$email','".time()."','',0,'$pass')";
		$this->db->query($query3);
		header ("location: index.php");
		$_SESSION['user'] = $_POST['user'];
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