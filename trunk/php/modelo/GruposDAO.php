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
require_once("{$base_dir}/clasesImportantes/bd.php");

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

    public function insert_datos($id, $idUser, $name,  $description,$nameFoto) {
       $query="INSERT INTO ".self::tablaGrupos." (idgrupos,usuarios_personas_idpersonas,nombre,fecha,descripcion,foto)
                 VALUES ($id,$idUser,'$name',NOW(),'$description','$nameFoto')";
        if($this->bd->query($query)){
            return true;
        }else return false;
       
    }
  public function insert_fotos($foto){
      $query="INSERT INTO ".self::tablaGrupos." (foto)
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
      $query="SELECT nombre  FROM ".self::tablaGrupos." where nombre = '$name'";
  }

}

?>
