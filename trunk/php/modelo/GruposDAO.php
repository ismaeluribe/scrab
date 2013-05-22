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
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");

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

    public function insert_datos($id, $idUser, $name, $description,$nameFoto,$privacidad) {
       $query="INSERT INTO ".self::tablaGrupos." (idgrupos,usuarios_personas_idpersonas,nombre,fecha,descripcion,foto,privacidad)
                 VALUES ($id,$idUser,'".$name."',NOW(),'".$description."','$nameFoto','$privacidad')";
        if ($this->bd->query($query)) {
            return true;
        }
        else
            return false;
    }

    public function insert_fotos($foto) {
        $query = "INSERT INTO " . self::tablaGrupos . " (foto)
                VALUES ('$foto')";
      if($this->bd->query($query)) return true;
      else return false;
  }
  public function ultimoDato(){
      $query="SELECT MAX(idgrupos) AS \"MAYOR\" FROM ".self::tablaGrupos;
      $stm=$this->bd->query($query);
      $dato=$stm->fetch_assoc();
      $dato['MAYOR']++;
      return $dato['MAYOR'];
  }
  public function nameNoRepeat($name){
      $query="SELECT nombre FROM ".self::tablaGrupos." where nombre = '$name'";
  }

    public function getGroupDataByUserId($id) {
        $stm = $this->bd->prepare("SELECT nombre, idgrupos FROM grupos WHERE idgrupos IN (SELECT grupos_idgrupos FROM anillos WHERE usuarios_personas_idpersonas = ?) ORDER BY idgrupos ASC");
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nombre, $idgrupos);
        $groupArray=array();
        while ($stm->fetch()){
            $groupArray[$idgrupos]=$nombre;
        }
        /*
        echo '<pre>';
        print_r($groupArray);
        echo '</pre>';*/
     return $groupArray;
    }

}


  //$obj=new GruposDAO();
  //$obj->getGroupDataByUserId(1);
  //$id=$obj->ultimoDato();
  //if($obj->insert_datos($id, 1, 'j kljnk', 'kjnknjlk', 'kmlkm', 'secreto')){
  //echo 'liadaaaa';
  //}
  //else echo ' mal';
 
?>
