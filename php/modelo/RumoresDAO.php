<?php

$base_dir_Rumores = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir_Rumores}/commons/bd.php");

class rumoresDAO{

	// Alta de modificacion de rumores, sin comprobaciones ninguna

	const tablaRumores='rumores';
	private $db;

	public function __construct(){
		$obj = new bd();
		$this->db = $obj->getDB();
		$this->registroRumor = $this->db->prepare("INSERT INTO rumores (anillos_grupos_idgrupos, anillos_usuarios_idpersonas, contenido, lugar, enlace, personas_idpersonas, foto) VALUES (?,?,?,?,?,?,?)");
	}

	public function registroRumor($anillosIDgrupo,$idpersona,$contenido,$lugar,$enlace,$trataDe,$foto){
		$this->registroRumor->bind_param("iisssis", $anillosIDgrupo, $idpersona, $contenido, $lugar, $enlace, $trataDe,$foto);
		$this->registroRumor->execute();
	}
	public function getLastId(){
		$query = "SELECT MAX(idrumores) AS \"MAYOR\" FROM " . self::tablaRumores;
		$stm = $this->db->query($query);
		$dato = $stm->fetch_assoc();
		$dato['MAYOR']++;
		return $dato['MAYOR'];
	}

	public function getRumoresFromGrupos($idUser, $idGrupo){
		$query = "SELECT idrumores, anillos_grupos_idgrupos, anillos_usuarios_idpersonas, contenido, rumores.foto
		                AS foto, lugar, enlace, rumores.personas_idpersonas AS persona, nombre
		                        FROM rumores, personas
		                        WHERE personas.idpersonas = rumores.anillos_usuarios_idpersonas
		                        AND anillos_usuarios_idpersonas != $idUser
		                        AND anillos_grupos_idgrupos = $idGrupo";
		$result = $this->db->query($query);
		return $result;
	}

    private function getNumRumByUserId($id){
        $var = null;
        $query1 =  "SELECT COUNT(idrumores)as 'lanzados' FROM rumores WHERE anillos_usuarios_idpersonas=? GROUP BY anillos_usuarios_idpersonas";
        $stm = $this->db->prepare($query1);
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom);
        if ($stm->fetch()) {
            $var = $nom;
        } else {
            throw new ModeloException("errores en el formato de los parametros");
        }
        $stm->close();
        return $var;
    }

    private function getNumRumoresLanzadosByUserId($id){
        $var = null;
        $query1 =  "SELECT COUNT(idrumores) FROM rumores WHERE personas_idpersonas=? GROUP BY personas_idpersonas";
        $stm = $this->db->prepare($query1);
        if (1 != ($stm->bind_param("i", $id))) {
            throw new PersonasException("errores en el formato de los parametros");
        }
        $stm->execute();
        $stm->bind_result($nom);
        if ($stm->fetch()) {
            $var = $nom;
        } else {
            throw new ModeloException("errores en el formato de los parametros");
        }
        $stm->close();
        return $var;
    }
    public function  getNumRumoresAllByUserId($id){
        $lanzados=$this->getNumRumByUserId($id);
        $sobreMi=$this->getNumRumoresLanzadosByUserId($id);
        return array('lanzados'=>$lanzados,'sobreMi'=>$sobreMi);
    }

    /*****************************/

    public function deleteRumorByGroupId($id){
        $ids=$this->getRumorIdByGroup($id);
        $stm = $this->db->prepare("DELETE FROM apoyos WHERE rumores_idrumores = ?");
        if($ids){
            foreach($ids as $value){
                $stm->bind_param('i',$value);
                $stm->execute();
            }
            $stm->close();
            $stm2 = $this->db->prepare("DELETE FROM rumores WHERE anillos_grupos_idgrupos = ?");
            $stm2->bind_param('i',$id);
            $stm2->execute();
            $stm2->close();
        }


    }
    private  function getRumorIdByGroup($idg){
        //metodo paara la obtencion de todos los datos que tiene el usuario
        $query= "SELECT idrumores FROM rumores WHERE anillos_grupos_idgrupos = ?";
        $stm=$this->db->prepare($query);
        $stm->bind_param('i',$idg);
        $stm->execute();
        $stm->bind_result($idrumores);
        $groupArray = array();
        while ($stm->fetch()) {
            $groupArray[] =$idrumores;
        }
        $stm->close();
        if (count($groupArray)) {
            return $groupArray;
        } else
            return FALSE;
    }

    public function getRumoresByUserId($id){
        $query = "SELECT idrumores, anillos_grupos_idgrupos, anillos_usuarios_idpersonas, contenido, rumores.foto
		                AS foto, lugar, enlace, rumores.personas_idpersonas AS persona, nombre
		                        FROM rumores, personas
		                        WHERE personas.idpersonas = rumores.anillos_usuarios_idpersonas
		                        AND anillos_usuarios_idpersonas = $id";
        $result = $this->db->query($query);
        return $result;
    }

}

?>