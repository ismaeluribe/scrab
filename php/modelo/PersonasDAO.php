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

    public function __construct() {
        $obj = new bd();
        $this->db = $obj->getDB();
        ;
    }

    public function registroPersonas($idpersonas, $tipo, $nombre, $apellido, $apellido2, $fechaNac, $sexo) {
        $stm = $this->db->prepare("INSERT INTO personas (idpersonas,tipo,nombre,apellido,apellido2,fechaNac,sexo) VALUES(?,?,?,?,?,?,?)");
        if (1!=($stm->bind_param("issssss", $idpersonas, $tipo, utf8_decode($nombre), utf8_decode($apellido), utf8_decode($apellido2), $fechaNac, $sexo))) {
            
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1!=$stm->affected_rows) {

            throw new PersonasException("errores en la inseccion de los datos");
        }
        $stm->close();
    }

    public function getUltimoId() {
        $query1 = "SELECT MAX(idpersonas) AS \"mayor\" FROM personas ";
        $result1 = $this->db->query($query1);
        $mayor = $result1->fetch_assoc();
        $id = $mayor['mayor'] + 1;
        $result1->close();
        return $id;
    }

    public function getDataById($id){
        $var=null;
        $query1 = "SELECT nombre,apellido,apellido2,IFNULL(foto,'noimage.jpg') FROM personas WHERE idpersonas = ?";
        $stm = $this->db->prepare($query1);
        if (1!=($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom,$ape1,$ape2,$image);
        if($stm->fetch()){
            $var=array('nombre'=>$nom,'apellido'=>$ape1,'apellido2'=>$ape2,'imagen'=>$image);
        }
        else{
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->close();
        return $var;
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
