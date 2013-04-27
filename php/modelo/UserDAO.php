<?php

//debido a un problema con el sistema de ruta de window al estar en una maquina virtual
//hemos de tener que hacer a mano las rutas de la aplicacion 
//la otra opcion es cambiar el path de php.ini
$ds = "/"; //variable con el separador
$base_dir = realpath(dirname(__FILE__) .$ds.'..') . $ds;
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}clasesImportantes/bd.php");

class UserDAO {
    //definimos las tablas con las que intercambian datos los usuarios como constantes

    const tablaPersonas = "personas";
    const tablaUsuarios = "usuarios";

    //definimos las propiedades
    private $db;

    public function __construct() {// Constructor de la clase
        $obj = new bd();
        $this->db = $obj->getDB();
        $this->userpass = $this->db->prepare("SELECT * FROM usuarios WHERE nombreUser = '?' AND pass = '?'");
        $this->registroPersonas = $this->db->prepare("INSERT INTO personas VALUES(?,'?','?','?','?','?','?','?','?',0)");
        $this->registroUsuario = $this->db->prepare("INSERT INTO usuarios VALUES (?,'?','?','".date("Y-m-d")."','?',0,'?')");
    }

    function userpass() {// Comprueba si el usuario y la contraseña introducidos son correctos
        $user = $_POST['user'];
        $pass = hash("sha512", $_POST['pass']);
        $result = $this->db->query("SELECT * FROM " . self::tablaUsuarios . " WHERE nombreUser = '$user' AND pass = '$pass'");
        if ($result->num_rows != 0) {
            $_SESSION['user'] = $_POST['user'];
            header("location: inicio.php");
        }
    }

    function registroUsuario() { // Hace el registro primero de persona y despues de usuario
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
            //no eciste el email ni el nombre de usuario
            $query1 = "SELECT MAX(idpersonas) AS \"mayor\" FROM personas";
            $result1 = $this->db->query($query1);
            $mayor = $result1->fetch_assoc();
            $mayor1 = $mayor['mayor'] + 1;
            //insertamos datos en personas
            $query2 = "INSERT INTO " . self::tablaPersonas . " VALUES($mayor1,'user','$nombre','$ape1','$ape2','$nac','$sexo','$foto','$formato',0)";
            if (!$this->db->query($query2)) {
                //echo $this->db->errno;
                throw die("eror introduciendo los datos en la tabla personas");
            }

            //migramos los datos de personas a aqui
            $query3 = "INSERT INTO " . self::tablaUsuarios . " VALUES ($mayor1,'$nombreUser','$email','" . date("Y-m-d") . "','',0,'$pass')";
            if (!$this->db->query($query3)) {
                //echo $this->db->errno;
                throw die("error introduciendo los datos en la tabla usuarios");
            }
        } else {
            throw die("el e-mail o el nombre de usuario introducido no son validos");
        }

        //obtenemos el ultimos usuario


        header("location: index.php");
        $_SESSION['user'] = $_POST['user'];
    }

    function cerrarSesion() {
        session_destroy();
        header("location:index.php");
    }

    private function nombreUser($user) {// Comprueba si el nombre de usuario está libre
        //$user = $_POST['user'];
        $result = $this->db->query("SELECT nombreUser FROM " . self::tablaUsuarios . " WHERE nombreUser = '$user'");
        if ($result->num_rows == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function emailUser($email) {
        $result = $this->db->query("SELECT nombreUser FROM " . self::tablaUsuarios . " WHERE email = '$email'");
        if ($result->num_rows == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>