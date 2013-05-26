<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nico
 * Date: 26/05/13
 * Time: 21:40
 * To change this template use File | Settings | File Templates.
 */

$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");
require_once 'modeloException/AnillosException.php';

class AnillosDAO {

    private $db;

    const tablaAnillos = "anillos";

    public function __construct() {
        $obj = new bd();
        $this->db = $obj->getDB();
    }

    public function getUserDataByAnillosGroup($name,$idGroup){
        $stm=$this->db->prepare("SELECT personas_idpersonas,nombreUser FROM usuarios
                                    WHERE personas_idpersonas IN (SELECT usuarios_personas_idpersonas FROM ".self::tablaAnillos.
                                        " WHERE grupos_idgrupos = ?)
                                    AND nombreUser LIKE ? ORDER BY personas_idpersonas DESC limit 3");
        $name=$this->db->real_escape_string($name);
        $name = '%' . $name . '%';
        if (1 != ($stm->bind_param("is",$idGroup, $name))) {
            throw new AnillosException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($id, $nombre);
        $userArray = array();
        while ($stm->fetch()) {
            $userArray[$id] = $nombre;
        }
        $stm->close();
        if (count($userArray)) {

            return $userArray;
        }
        else
            return FALSE;

    }

    public function getPersonajeDataByAnillosGroup($name,$idGroup){
        $stm=$this->db->prepare("SELECT personas_idpersonas, mote FROM personajes
	                              WHERE anillos_grupos_idgrupos = ?
		                          AND mote LIKE ?  ORDER BY personas_idpersonas DESC limit 3");
        $name=$this->db->real_escape_string($name);
        $name = '%' . $name . '%';
        if (1 != ($stm->bind_param("is",$idGroup, $name))) {
            throw new AnillosException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($id, $nombre);
        $personajeArray = array();
        while ($stm->fetch()) {
            $personajeArray[$id] = $nombre;
        }
        $stm->close();
        if (count($personajeArray)) {

            return $personajeArray;
        }
        else
            return FALSE;


    }


    public function insertUserInAnillos($idUser,$idGroup){
        $query = "INSERT INTO " . self::tablaAnillos . " (usuarios_personas_idpersonas,grupos_idgrupos,miembroDesde)
                 VALUES ($idUser,$idGroup,NOW())";
        if ($this->db->query($query)) {
            return true;
        }
        else
            return false;

    }
}