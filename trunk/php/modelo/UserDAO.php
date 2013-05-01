<?php

//debido a un problema con el sistema de ruta de window al estar en una maquina virtual
//hemos de tener que hacer a mano las rutas de la aplicacion 
//la otra opcion es cambiar el path de php.ini
$base_dir = realpath(dirname(__FILE__) .'/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/clasesImportantes/bd.php");

class UserDAO {
    //definimos las propiedades
    private $db;

    public function __construct() {// Constructor de la clase
        $obj = new bd();
        $this->db = $obj->getDB();
        $this->userpass = $this->db->prepare("SELECT nombreUser FROM usuarios WHERE nombreUser = ? AND pass = ?");
        $this->registroPersonas = $this->db->prepare("INSERT INTO personas VALUES(?,?,?,?,?,?,?,?,?,0)");
        $this->registroUsuario = $this->db->prepare("INSERT INTO usuarios VALUES (?,?,?,'".date("Y-m-d")."','',0,?)");
        $this->userName = $this->db->prepare("SELECT personas_idpersonas FROM usuarios WHERE nombreUser = ?");
        $this->email = $this->db->prepare("SELECT nombreUser FROM usuarios WHERE email = ?");
        $this->image = $this->db->prepare("SELECT foto FROM personas WHERE idpersonas = (SELECT personas_idpersonas FROM usuarios WHERE nombreUser = ?)");
        $this->dropPersona = $this->db->prepare("DELETE FROM personas WHERE idpersonas = (SELECT personas_idpersonas FROM usuarios WHERE nombreUser = ?)");
        $this->dropUsuario = $this->db->prepare("DELETE FROM usuarios WHERE nombreUser = ?");
    }

    function userpass() {// Comprueba si el usuario y la contraseña introducidos son correctos
        $user = $_POST['user'];
        $pass = hash("sha512", $_POST['pass']);
        $this->userpass->bind_param("ss", $user, $pass);
        $this->userpass->execute();
        $this->userpass->bind_result($result);
        $this->userpass->fetch();
        if (isset($result)) {
            $_SESSION['user'] = $user;
            header("location: inicio.php");
        }
    }

    function registroUsuario() { // Hace el registro primero de persona y despues de usuario
        $tipo = "user";
        $nombre = $_POST['nom'];
        $ape1 = $_POST['ape1'];
        $ape2 = $_POST['ape2'];
        $nac = $_POST['nac'];
        $sexo = $_POST['sexo'];
        $nombreUser = $_POST['user'];
        $email = $_POST['email'];
        $pass = hash("sha512", $_POST['pass']);
        $formato = null;
        $foto = null;
        //para hacer el registro primero tenemos que comprobar que no existe un campo
        //con el mismo nombre de usuario ni el mismo correo en la bd
        if ($this->emailUser($email) && $this->nombreUser($nombreUser)) {
            $query1 = "SELECT MAX(idpersonas) AS \"mayor\" FROM personas";
            $result1 = $this->db->query($query1);
            $mayor = $result1->fetch_assoc();
            $mayor1 = $mayor['mayor'] + 1;
            //insertamos datos en personas
            $this->registroPersonas->bind_param("issssssbs",$mayor1,$tipo,$nombre,$ape1,$ape2,$nac,$sexo,$foto,$formato);
            $this->registroPersonas->execute();
            //migramos los datos de personas a aqui
            $this->registroUsuario->bind_param("isss",$mayor1,$nombreUser,$email,$pass);
            $this->registroUsuario->execute();
            header("location: index.php");
            $_SESSION['user'] = $_POST['user'];
        }else{
            echo("Usuario o email ya existe.<br/>");
        }
    }

    function cerrarSesion() {
        session_destroy();
        header("location:index.php");
    }

    private function nombreUser($user) {// Comprueba si el nombre de usuario está libre
        // Se inicializan las ? con los parametros dependiendo s - string, b - blob, i - int, etc...
        $this->userName->bind_param("s",$user); 
        $this->userName->execute(); // Se ejecuta la consulta.
        // Se dice donde guardar el resultado de cada una de los elementos de la consulta.
        $this->userName->bind_result($result); // Si se pidieran varios elementos habría que poner mas variables aqui.
        $this->userName->fetch();// Fetch para rellenar la variable.
        if (!isset($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function conexion($user){
        $this->userName->bind_param("s",$user);
        $this->userName->execute();
        $this->userName->bind_result($result);
        $this->userName->fetch();
        $datos = $this->getDatos();
        $query = "INSERT INTO conexiones (usuarios_personas_idpersonas,navegador,sistema,ip,fecha) VALUES (\"$result\",\"".$datos['2']."\",\"".$datos['1']."\",\"".$datos['0']."\",\"".date("Y-m-d")."\")";
        $this->db->query($query);
        echo $this->db->error;
    }

    private function emailUser($email) {
        $this->email->bind_param("s",$email);
        $this->email->execute();
        $this->email->bind_result($result);
        $this->email->fetch();
        if (!isset($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function getImage($user){ // No funciona todavia, no se si es xk no hay nada en los blob todavia.
        $this->image->bind_param("s",$user);
        $this->image->execute();
        $this->image->store_result();
        $this->image->bind_result($image);
        $this->image->fetch();
        header("Content-Type: image/jpg");
        echo $image; 
    }

    private function dropUser($user){
        $this->dropPersona->bind_param("s",$user);
        $this->dropUsuario->bind_param("s",$user);
        $this->dropUsuario->execute();
        $this->dropPersona->execute();
    }

    function getDatos(){
        $temp=array();
        $ip=$_SERVER['REMOTE_ADDR'];
        $datos=$_SERVER['HTTP_USER_AGENT'];
        array_push($temp,$ip);
        if(strpos($datos,"Windows")!==false)
            array_push($temp,"Windows");
        elseif(strpos($datos,"Mac")!==false)
            array_push($temp,"Mac");
        elseif(strpos($datos,"Linux")!==false)
            array_push($temp,"Linux");
        if(strpos($datos,"MSIE")!==false)
            array_push($temp,"Internet Explorer");
        elseif(strpos($datos,"Firefox")!==false)
            array_push($temp,"Firefox");
        elseif(strpos($datos,"Chrome")!==false)
            array_push($temp,"Google Chrome");
        elseif(strpos($datos,"Safari")!==false)
            array_push($temp,"Safari");
        elseif(strpos($datos,"Opera")!==false)
            array_push($temp,"Opera");
        else
            array_push($temp,"Navegador desconocido");
        return $temp;
    }
}

?>