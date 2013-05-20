
<?php

/* clase para la coneccion a la base de datos */

class bd {

    private $db;

    function __construct($user = "ilove", $pass = "running") {
        $usuario = $user;
        $password = $pass;
        $host = "localhost";
        $dbname = "scrab";
        $this->db = new mysqli($host, $usuario, $password, $dbname);
        if ($this->db->connect_error) {
            die("Error de conexion(" . $this->db->connect_errno . ")" . $this->db->connect_error);
        }
        //mysql_query();
        $this->db->query("SET NAMES 'utf8'");
    }

    function getDB() {
        return $this->db;
    }

}

?>