<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EspiarDAO
 *
 * @author nico
 */
define('base_dirEs', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirEs . "/commons/bd.php";
require_once 'modeloException/EspiarException.php';
class EspiarDAO {
    /*
     * definimos como constantes el nombre de las tablas que vemos a utilizar
     */
    //const tablaPersonas = "personas";

    const tablaEspiar = "usuarios_has_personas";

    private $bd;

    public function __construct() {
        $obj = new bd();
        $this->db = $obj->getDB();
    }
    public function espiarPeople($id_user,$id_espiado,$espiar){
        $stm = $this->db->prepare("INSERT INTO " . self::tablaEspiar . " (usuarios_personas_idpersonas, personas_idpersonas, fecha,espiar) 
                    VALUES(?,?,NOW(),?)");
        if (1 != ($stm->bind_param("iii",$id_user,$id_espiado,$espiar))) {

            throw new EspiarException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1 != $stm->affected_rows) {

            throw new EspiarException("errores en la inseccion de los datos");
        }
        $stm->close();
    }
    
    public function getSpyByids($id_user,$id_espiado){
        $stm = $this->db->prepare("SELECT espiar FROM usuarios_has_personas 
                                            WHERE usuarios_personas_idpersonas = ?
                                                AND personas_idpersonas = ?
                                            ORDER BY fecha DESC LIMIT 1");
        if (1 != ($stm->bind_param("ii",$id_user,$id_espiado))) {

            throw new EspiarException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($espiar);
        $stm->fetch();
        $stm->close();
        if($espiar)
            return $espiar;
        else return 0;
    }


    private function getNumMeEspianById($id){
        $var = null;
        $query1 = "SELECT COUNT(*) FROM usuarios_has_personas
	            WHERE espiar=1
                AND personas_idpersonas= ?
                AND fecha IN (SELECT MAX(fecha)
                            FROM usuarios_has_personas
                                GROUP BY personas_idpersonas,usuarios_personas_idpersonas)";
        $stm = $this->db->prepare($query1);
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom);
        if ($stm->fetch()) {
            $var = $nom;
        } else {
            //throw new ModeloException("errores en el formato de los parametros");
            $var=0;
        }
        $stm->close();
        return $var;
    }
    private function getNumEspioById($id){

        $var = null;
        $query1 =  "SELECT COUNT(*) FROM usuarios_has_personas
                            WHERE espiar=1
                                AND usuarios_personas_idpersonas= ?
                                AND fecha IN (SELECT MAX(fecha)
                                            FROM usuarios_has_personas
                                            GROUP BY personas_idpersonas,usuarios_personas_idpersonas)";
        $stm = $this->db->prepare($query1);
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom);
        if ($stm->fetch()) {
            $var = $nom;
        } else {
            //throw new ModeloException("errores en el formato de los parametros");
            $var=0;
        }
        $stm->close();
        return $var;
    }

    public function getNumEspiarById($id){

        $espio=$this->getNumEspioById($id);
        $meEspian=$this->getNumMeEspianById($id);
        return array('espio'=> $espio, 'meEspian'=>$meEspian);
    }


}
//$obj=new EspiarDAO();
//$obj->getSpyByids(1, 80);
//$obj->espiarPeople(1, 2);

?>
