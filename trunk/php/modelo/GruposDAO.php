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

    public function paraMostrar($id){
        $query = "SELECT nombre, idgrupos, descripcion, foto FROM grupos WHERE idgrupos IN (SELECT grupos_idgrupos FROM anillos WHERE usuarios_personas_idpersonas = $id) ORDER BY idgrupos ASC";
        $result = $this->bd->query($query);
        return $result;
    }

    public function getInfoGrupo($id){
        $query = "SELECT nombre, descripcion, foto FROM grupos where idgrupos = $id";
        $result = $this->bd->query($query);
        return $result;
    }

    public function getNombre($id){
        $query = "SELECT nombre FROM grupos WHERE idgrupos = $id";
        $result = $this->db->query($query);
        return $result->fetch_object();
    }

    //metodo para buscar los grupos que sean publicos en funcion de que su nombre este contenido en una cadena
    public function getGroupDataByString($name, $id) {
        $stm = $this->bd->prepare("SELECT idgrupos, nombre, descripcion, foto FROM grupos WHERE nombre LIKE ? AND privacidad LIKE 'publico' AND idgrupos NOT IN (SELECT grupos_idgrupos FROM anillos WHERE usuarios_personas_idpersonas = ?) ORDER BY idgrupos DESC limit 3");
        //concatenamos los valores
        $name=$this->bd->real_escape_string($name);
        $name = '%' . $name . '%';
        if (1 != ($stm->bind_param("si", $name, $id))) {
            throw new GruposException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($idgrupos, $nombre, $descripcion ,$foto);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[$idgrupos] = array($nombre, $descripcion, $foto);
        }
        $stm->close();
        if (count($groupArray)) {
            return $groupArray;
        } else
            return FALSE;
        /*
          echo '<meta charset="UTF-8">';
          echo '<pre>';
          //var_dump($groupArray);
          print_r($groupArray);
          echo '</pre>'; */
    }

    public function getGruposByUser($id){
        $query = "SELECT idgrupos,nombre FROM grupos WHERE idgrupos IN (SELECT grupos_idgrupos FROM anillos WHERE usuarios_personas_idpersonas = $id) ORDER BY idgrupos ASC";
        $result = $this->bd->query($query);
        return $result;
    }

    public function getPerteneceByIds($idUser,$idGrupo){
        $stm = $this->bd->prepare("SELECT miembroDesde FROM anillos WHERE usuarios_personas_idpersonas = ? AND grupos_idgrupos = ?");
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
    public function getNumGroupsByUserId($id){
        //metodo para la obtencion del numero de grupos que ha creado el usuario
        $var = null;
        $query1 =  "SELECT COUNT(*) FROM grupos WHERE usuarios_personas_idpersonas = ? AND eliminado = 0";
        $stm = $this->bd->prepare($query1);
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom);
        if ($stm->fetch()) {
            $var = $nom;
        } else {
            throw new GruposException("errores en el formato de los parametros");
        }
        $stm->close();
        return $var;
    }

    public function getGroupAllDataByUserId($id){
        //metodo paara la obtencion de todos los datos que tiene el usuario
        $query="SELECT idgrupos,nombre,fecha, descripcion, privacidad,foto
                    FROM grupos
                        WHERE usuarios_personas_idpersonas=?
                        AND eliminado=0";
        $stm=$this->bd->prepare($query);
        $stm->bind_param('i',$id);
        $stm->execute();
        $stm->bind_result($idgrupos, $nombre, $fecha, $descripcion, $privacidad, $foto);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[$idgrupos] = array('nombre'=>$nombre,
                'foto'=> $foto,
                'privacidad'=>$privacidad ,
                'fecha'=>$fecha,
                'description'=> $descripcion);
        }
        $stm->close();
        if (count($groupArray)) {
            return $groupArray;
        } else
            return FALSE;
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
