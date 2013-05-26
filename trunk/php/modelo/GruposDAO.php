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
define('base_dirGD', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirGD . "/commons/bd.php";
require_once 'modeloException/GruposException.php';

class GruposDAO {
    /*
     * definimos como constantes el nombre de las tablas que vemos a utilizar
     */

    //const tablaPersonas = "personas";

    const tablaGrupos = "grupos";
    const tablaAnillos= "anillos";

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
        $stm = $this->bd->prepare("SELECT nombre, idgrupos FROM " . self::tablaGrupos . " WHERE idgrupos IN (SELECT grupos_idgrupos FROM anillos WHERE usuarios_personas_idpersonas = ?) ORDER BY idgrupos ASC");
        if (1 != ($stm->bind_param("i", $id))) {
            throw new GruposException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nombre, $idgrupos);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[$idgrupos] = $nombre;
        }
        $stm->close();
        return $groupArray;
    }

    //metodo para buscar los grupos que sean publicos en funcion de que su nombre este contenido en una cadena
    public function getGroupDataByString($name, $id) {
        $stm = $this->bd->prepare("SELECT idgrupos, nombre, descripcion, foto FROM " . self::tablaGrupos . " 
                                        WHERE nombre LIKE ? 
                                            AND privacidad LIKE 'publico' 
                                            AND idgrupos NOT IN 
                                                (SELECT grupos_idgrupos FROM  anillos WHERE usuarios_personas_idpersonas = ?) 
                                         ORDER BY idgrupos DESC limit 3");
        //concatenamos los valores
        $name=$this->bd->real_escape_string($name);
        $name = '%' . $name . '%';
        if (1 != ($stm->bind_param("si", $name, $id))) {
            throw new GruposException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($idgrupos, $nombre, $descripcion,$foto);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[$idgrupos] = array($nombre, $descripcion, $foto);
        }
        $stm->close();
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
    public function getPerteneceByIds($idUser,$idGrupo){
        $stm = $this->bd->prepare("SELECT miembroDesde FROM anillos 
                                        WHERE usuarios_personas_idpersonas = ? 
                                        AND grupos_idgrupos = ?");
        if (1 != ($stm->bind_param("ii",$idUser,$idGrupo))) {

            throw new EspiarException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($fecha);
        $stm->fetch();
        $stm->close();
        if($fecha)
            return $fecha;
        else return 0;
    }
    
    public function insertUserInAnillos($idUser,$idGroup){
        $query = "INSERT INTO " . self::tablaAnillos . " (usuarios_personas_idpersonas,grupos_idgrupos,miembroDesde)
                 VALUES ($idUser,$idGroup,NOW())";
        if ($this->bd->query($query)) {
            return true;
        }
        else
            return false;
        
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
