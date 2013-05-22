<?php

//debido a un problema con el sistema de ruta de window al estar en una maquina virtual
//hemos de tener que hacer a mano las rutas de la aplicacion 
//la otra opcion es cambiar el path de php.ini
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");
require_once('modeloException/UserException.php');

class UserDAO {

    //definimos las propiedades
    private $db;

    public function __construct() {// Constructor de la clase
        $obj = new bd();
        $this->db = $obj->getDB();
        $this->userpass = $this->db->prepare("SELECT personas_idpersonas,email FROM usuarios WHERE nombreUser = ? AND pass = ?");
        $this->registroUsuario = $this->db->prepare("INSERT INTO usuarios (personas_idpersonas,nombreUser,email,fechaReg,pass) VALUES (?,?,?,now(),?)");
        $this->userName = $this->db->prepare("SELECT nombreUser FROM usuarios WHERE nombreUser = ?");
        $this->email = $this->db->prepare("SELECT nombreUser FROM usuarios WHERE email = ?");
        $this->dropUsuario = $this->db->prepare("UPDATE personas SET eliminado=1 WHERE id_personas = ?");
    }

    function userpass($user, $pass) {
        // Comprueba si el usuario y la contraseña introducidos son correctosy el correo electronico
        //si son correctos devuleve el id del usuario 
        $bool = null;
        if (1 != $this->userpass->bind_param("ss", $user, $pass)) {
            throw new UserException('error en el registro y la contraseña, asignacion de parametros');
        }
        $this->userpass->execute();
        if (!$this->userpass->bind_result($col1, $col2)) {
            throw new UserException('error en el statement');
        }
        if ($this->userpass->fetch()) {
            $bool = array($col1, $col2);
        }

        $this->userpass->close();
        return $bool;
    }

    public function registroUsuario($mayor1, $nombreUser, $email, $pass) {
        // Hace el registro primero de persona y despues de usuario
        if (1 != $this->registroUsuario->bind_param("isss", $mayor1, $nombreUser, $email, $pass)) {
            throw new \UserException('error en la asignacion de parametros');
        }
        $this->registroUsuario->execute();
        if (1 != $this->registroUsuario->affected_rows) {
            throw new \UserException('error en la en la inserccion en la bd');
        }
        $this->registroUsuario->close();
    }

    function cerrarSesion() {
        session_destroy();
        header("location:index.php");
    }

    public function nombreUser($user) {// Comprueba si el nombre de usuario está libre
        // Se inicializan las ? con los parametros dependiendo s - string, b - blob, i - int, etc...
        $bool = false;
        $this->userName->bind_param("s", $user);
        $this->userName->execute(); // Se ejecuta la consulta.
        // Se dice donde guardar el resultado de cada una de los elementos de la consulta.
        $this->userName->bind_result($result); // Si se pidieran varios elementos habría que poner mas variables aqui.
        $this->userName->fetch(); // Fetch para rellenar la variable.
        if (!isset($result)) {
            $bool = true;
        }
        $this->userName->close();
        return $bool;
    }

    public function emailUser($email) {
        $bool = false;
        $this->email->bind_param("s", $email);
        $this->email->execute();
        $this->email->bind_result($result);
        $this->email->fetch();
        if (!isset($result)) {
            $bool = TRUE;
        }
        $this->email->close();
        return $bool;
    }

    public function conexion($id) {
        $bool = false;
        $datos = $this->getDatos();
        $idconexiones= $this->getConnById($id);
        $query = "INSERT INTO conexiones (usuarios_personas_idpersonas,idconexiones,navegador,sistema,ip,fecha) VALUES ($id, $idconexiones, \"" . $datos['2'] . "\",\"" . $datos['1'] . "\",\"" . $datos['0'] . "\",now())";
        if ($this->db->query($query))
            $bool = true;
        return $bool;
        // echo $this->db->error;
    }

    private function dropUser($user) {
        $this->dropUsuario->bind_param("i",$user);
        $this->dropUsuario->execute();
    }

    function getDatos() {
        $temp = array();
        $ip = $_SERVER['REMOTE_ADDR'];
        $datos = $_SERVER['HTTP_USER_AGENT'];
        array_push($temp, $ip);
        if (strpos($datos, "Windows") !== false)
            array_push($temp, "Windows");
        elseif (strpos($datos, "Mac") !== false)
            array_push($temp, "Mac");
        elseif (strpos($datos, "Linux") !== false)
            array_push($temp, "Linux");
        if (strpos($datos, "MSIE") !== false)
            array_push($temp, "Internet Explorer");
        elseif (strpos($datos, "Firefox") !== false)
            array_push($temp, "Firefox");
        elseif (strpos($datos, "Chrome") !== false)
            array_push($temp, "Google Chrome");
        elseif (strpos($datos, "Safari") !== false)
            array_push($temp, "Safari");
        elseif (strpos($datos, "Opera") !== false)
            array_push($temp, "Opera");
        else
            array_push($temp, "Navegador desconocido");
        return $temp;
    }
    
    private function getConnById($id){
        $query1 = "SELECT MAX(idconexiones) AS \"last\" FROM conexiones WHERE usuarios_personas_idpersonas = $id";
        $result1 = $this->db->query($query1);
        $mayor = $result1->fetch_assoc();
        $id = $mayor['last'] + 1;
        //echo $id;
        $result1->close();
        return $id;
    }

}


  //$obj=new UserDAO();
  //$obj->registroUsuario(1, 'nico', 'nicoqb@gmail.com', '123456789');
  /*
  $pass='123456';
  $pass=hash("sha512", $pass);
  $user='nico3';
  try{
  $var=$obj->userpass($user, $pass);
  var_dump($var);
  }  catch (Exception $e){
  echo "<br>$e<br>";
  }
 */
?>

