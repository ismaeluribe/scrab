<?php

/**
 * Description of PersonasDAO
 *
 * @author nico
 */
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/clasesImportantes/bd.php");
require_once ('modeloException/PersonasException.php');

class PersonasDAO {
    //put your code here

    const tablaPersonas = "personas";

    private $db;

    public function __construct() {
        $obj = new bd();
        $this->db = $obj->getDB();
        ;
    }

    public function registroPersonas($idpersonas, $tipo, $nombre, $apellido, $apellido2, $fechaNac, $sexo) {
        $stm = $this->db->prepare("INSERT INTO " . self::tablaPersonas . " (idpersonas,tipo,nombre,apellido,apellido2,fechaNac,sexo) VALUES(?,?,?,?,?,?,?)");
        if (1!=($stm->bind_param("issssss", $idpersonas, $tipo, $nombre, $apellido, $apellido2, $fechaNac, $sexo))) {
            
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1!=$stm->affected_rows) {

            throw new PersonasException("errores en la inseccion de los datos");
        }
        $stm->close();
    }

    public function getUltimoId() {
        $query1 = "SELECT MAX(idpersonas) AS \"mayor\" FROM " . self::tablaPersonas;
        $result1 = $this->db->query($query1);
        $mayor = $result1->fetch_assoc();
        $id = $mayor['mayor'] + 1;
        $result1->close();
        return $id;
    }

}
/*
try {
    $obj = new PersonasDAO();
    $id = $obj->getUltimoId();
    $obj->registroPersonas($id, 'user', 'vcxv', 'dfv', 'cvcxv', '1990-12-19', 'h');
} catch (PersonasException $ep) {
    echo $ep;
} catch (Exception $exc) {
    echo $exc->getMessage();
}*/
?>
