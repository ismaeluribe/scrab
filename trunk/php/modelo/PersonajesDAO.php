<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonajesDAO
 *
 * @author nico
 */
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");
require_once 'modeloException/PersonasException.php';
require_once 'modeloException/PersonajesException.php';

class PersonajesDAO {
    private $db;
    const tablaPersonajes = "personajes";

    public function __construct() {
        $obj = new bd();
        $this->db = $obj->getDB();
    }
    public function registroPersonajes($id,$mote,$description,$idUser,$idGroup) {
        $stm = $this->db->prepare("INSERT INTO ".self::tablaPersonajes." (personas_idpersonas, mote, descripcion, anillos_usuarios_idpersonas, anillos_grupos_idgrupos) 
                                            VALUES(?,?,?,?,?)");
        if (1!=($stm->bind_param("issii", $id,$mote,$description,$idUser,$idGroup))) {
            throw new PersonajesException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1!=$stm->affected_rows) {
            throw new PersonajesException("errores en la inseccion de los datos");
        }
        $stm->close();
    }
}

?>
