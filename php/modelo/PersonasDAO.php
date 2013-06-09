<?php

/**
 * Description of PersonasDAO
 *
 * @author nico
 */
$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/commons/bd.php");
require_once ('modeloException/PersonasException.php');

class PersonasDAO {

    //put your code here

    private $db;

    const tablaPersonas = 'personas';

    public function __construct() {
        $obj = new bd();
        $this->db = $obj->getDB();
        ;
    }

    public function registroPersonas($idpersonas, $tipo, $nombre, $apellido, $apellido2, $fechaNac, $sexo) {
        $stm = $this->db->prepare("INSERT INTO " . self::tablaPersonas . " (idpersonas,tipo,nombre,apellido,apellido2,fechaNac,sexo) VALUES(?,?,?,?,?,?,?)");
        if (1 != ($stm->bind_param("issssss", $idpersonas, $tipo, $nombre, $apellido, $apellido2, $fechaNac, $sexo))) {

            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1 != $stm->affected_rows) {

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

    public function getDataById($id) {
        $var = null;
        $query1 = "SELECT nombre,apellido,apellido2,IFNULL(foto,'noimage.jpg') FROM " . self::tablaPersonas . " WHERE idpersonas = ?";
        $stm = $this->db->prepare($query1);
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom, $ape1, $ape2, $image);
        if ($stm->fetch()) {
            $var = array('nombre' => $nom, 'apellido' => $ape1, 'apellido2' => $ape2, 'imagen' => $image);
        } else {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->close();
        return $var;
    }

    public function insertImage($nameImage, $id) {
        $query = "UPDATE " . self::tablaPersonas . " SET foto=? WHERE idpersonas=?";
        $stm = $this->db->prepare($query);
        if (1 != ($stm->bind_param("si", $nameImage, $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1 != $stm->affected_rows) {
            throw new PersonasException("errores en la inseccion de los datos");
        }
        $stm->close();
    }

    public function modificaPersona($nombre,$ape1,$ape2,$id){
        if($nombre != ""){
            $queryNombre = "UPDATE personas SET nombre = \"$nombre\" WHERE idpersonas = $id";
            $this->db->query($queryNombre);
        }
        if($ape1 != ""){
            $queryApe1 = "UPDATE personas SET apellido = \"$ape1\" WHERE idpersonas = $id";
            $this->db->query($queryApe1);
        }
        if($ape2 != ""){
            $queryApe2 = "UPDATE personas SET apellido2 = \"$ape2\" WHERE idpersonas = $id";
            $this->db->query($queryApe2);
        }
        
    }
    public function setImageByUserId($id,$foto){

        $query="UPDATE personas SET foto = ?
                  WHERE idpersonas=?";

        $stm=$this->db->prepare($query);
        if (1 != $stm->bind_param("si", $foto, $id)) {
            throw new \PersonasException('error en la asignacion de parametros');
        }
        $stm->execute();
        if (1 != $stm->affected_rows) {
            throw new \PersonasException('error en la en la inserccion en la bd');
        }
        //$this->registroUsuario->close()
    }

    function getForCaja(){
        
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
  } */
?>
