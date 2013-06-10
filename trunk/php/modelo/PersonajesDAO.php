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

    public function registroPersonajes($id, $mote, $description, $idUser, $idGroup) {
        $stm = $this->db->prepare("INSERT INTO " . self::tablaPersonajes . " (personas_idpersonas, mote, descripcion, anillos_usuarios_idpersonas, anillos_grupos_idgrupos) 
                                            VALUES(?,?,?,?,?)");
        if (1 != ($stm->bind_param("issii", $id, $mote, $description, $idUser, $idGroup))) {
            throw new PersonajesException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1 != $stm->affected_rows) {
            throw new PersonajesException("errores en la inseccion de los datos");
        }
        $stm->close();
    }

    //funcion para busccar los personajes pertenecienetes al grupo publico
    //en funcion de que su mote este contenido dentro de una cadena
    public function getPersonajeDataByString($name) {
        $stm = $this->db->prepare("SELECT p.idpersonas, f.mote, p.nombre, p.apellido, p.apellido2, p.foto
                                        FROM personajes f, personas p
                                                WHERE f.personas_idpersonas = p.idpersonas 
                                                        AND f.mote LIKE ? 
                                                        AND f.anillos_grupos_idgrupos = 1
                                        ORDER BY p.idpersonas DESC LIMIT 3");

//concatenamos los valores
        $name=$this->db->real_escape_string($name);
        $name = '%' . $name . '%';
        if (1 != ($stm->bind_param("s", $name))) {
            throw new PersonajesException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($id, $mote, $name, $ape1, $ape2,$foto);
        $personajesArray = array();
        while ($stm->fetch()) {
            $personajesArray[$id] = array($mote, $name . " " . $ape1 . " " . $ape2,$foto);
        }
        $stm->close();
        if (count($personajesArray)) {

            return $personajesArray;
        }
        else
            return FALSE;

    }
    public function deletePersonajeByGroupId($id){
        $stm = $this->db->prepare("DELETE FROM personajes WHERE anillos_grupos_idgrupos = ?");
        $stm->bind_param('i',$id);
        $stm->execute();
        $stm->close();

    }

}

//$obj=new PersonajesDAO();
//$obj->getPersonajeDataByString('ñ');
?>
