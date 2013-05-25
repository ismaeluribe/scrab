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
define('base_dirGD', realpath(dirname(__FILE__) . '/..'));
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once base_dirGD . "/commons/bd.php";
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
    public function espiarPeople($id_user,$id_espiado){
        $stm = $this->db->prepare("INSERT INTO " . self::tablaEspiar . " (usuarios_personas_idpersonas, personas_idpersonas, fecha) 
                    VALUES(?,?,NOW())");
        if (1 != ($stm->bind_param("ii",$id_user,$id_espiado ))) {

            throw new EspiarException("errores en el formato de los parametros");
        }
        $stm->execute();
        if (1 != $stm->affected_rows) {

            throw new EspiarException("errores en la inseccion de los datos");
        }
        $stm->close();
    }
    

}
//$obj=new EspiarDAO();
//$obj->espiarPeople(1, 2);

?>
