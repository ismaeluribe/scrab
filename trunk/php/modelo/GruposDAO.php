<?php

/**
 * Description of gruposDAO
 *
 * @author nico
 * 
 * clase para el alta y la administracion 
 * de nuevoss grupos
 * 
 * Se tiene que controlar dos cosas, que cuando una persona crea un nuevo grupo
 * ha de ser mostrada como administrador del mismo y automaticamente pasa a 
 * formar parte del grupo
 * 
 */
define('base_dir', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dir . "/commons/bd.php";
require_once 'modeloException/GruposException.php';

class GruposDAO {
    /*
     * definimos como constantes el nombre de las tablas que vemos a utilizar
     */

    //const tablaPersonas = "personas";

    const tablaGrupos = "grupos";

    private $bd;

    public function __construct() {
        $obj = new bd();
        $this->bd = $obj->getDB();
    }

    public function insert_datos($id, $idUser, $name, $description, $nameFoto, $privacidad) {
        $query = "INSERT INTO " . self::tablaGrupos . " (idgrupos,usuarios_personas_idpersonas,nombre,fecha,descripcion,foto,privacidad)
                 VALUES ($id,$idUser,'" . $name . "',NOW(),'" . $description . "','$nameFoto','$privacidad')";
        if ($this->bd->query($query)) {
            return true;
        }
        else
            return false;
    }

    public function insert_fotos($foto) {
        $query = "INSERT INTO " . self::tablaGrupos . " (foto)
                VALUES ('$foto')";
        if ($this->bd->query($query))
            return true;
        else
            return false;
    }

    public function ultimoDato() {
        $query = "SELECT MAX(idgrupos) AS \"MAYOR\" FROM " . self::tablaGrupos;
        $stm = $this->bd->query($query);
        $dato = $stm->fetch_assoc();
        $dato['MAYOR']++;
        return $dato['MAYOR'];
    }

    public function nameNoRepeat($name) {
        $query = "SELECT nombre FROM " . self::tablaGrupos . " where nombre = '$name'";
    }

    public function getGroupDataByUserId($id) {
        $stm = $this->bd->prepare("SELECT nombre, idgrupos FROM grupos WHERE idgrupos IN (SELECT grupos_idgrupos FROM anillos WHERE usuarios_personas_idpersonas = ?) ORDER BY idgrupos ASC");
        if (1 != ($stm->bind_param("i", $id))) {
            throw new GruposException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nombre, $idgrupos);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[$idgrupos] = $nombre;
        }
        return $groupArray;
    }

    public function getGroupDataByString($name, $id) {
        $stm = $this->bd->prepare("SELECT idgrupos, nombre, descripcion FROM grupos 
                                        WHERE nombre LIKE ? 
                                            AND idgrupos NOT IN
                                                (SELECT idgrupos FROM  anillos WHERE usuarios_personas_idpersonas = ?) 
                                            AND privacidad LIKE 'publico'
                                         ORDER BY idgrupos ASC limit 3");
        //concatenamos los valores
        $name = '%' . $name . '%';
        if (1 != ($stm->bind_param("si", $name, $id))) {
            throw new GruposException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($idgrupos, $nombre, $descripcion);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[$idgrupos] = array($nombre, $descripcion);
        }
        if (count($groupArray)) {

            return $groupArray;
        }
        else
            return FALSE;
        /*
          echo '<meta charset="UTF-8">';
          echo '<pre>';
          //var_dump($groupArray);
          print_r($groupArray);
          echo '</pre>'; */
    }

}

//$obj = new GruposDAO();
//$obj->getGroupDataByString('u', 2);
//$obj->getGroupDataByUserId(1);
//$id=$obj->ultimoDato();
//if($obj->insert_datos($id, 1, 'j kljnk', 'kjnknjlk', 'kmlkm', 'secreto')){
//echo 'liadaaaa';
//}
//else echo ' mal';
?>
