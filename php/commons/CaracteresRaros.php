<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CaracteresRaros
 *
 * @author nico
 */
class CaracteresRaros {

    //put your code here
    private $cadena;

    public function __construct($cadena) {
        $this->cadena = $cadena;
    }

    public function remplaceCaracteres() {
        $this->cadena = ereg_replace("[äáàâãª]", "a", $cadena);
        $this->cadena = ereg_replace("[ÁÀÂÃÄ]", "A", $cadena);
        $this->cadena = ereg_replace("[ÍÌÎÏ]", "I", $cadena);
        $this->cadena = ereg_replace("[íìîï]", "i", $cadena);
        $this->cadena = ereg_replace("[éèêë]", "e", $cadena);
        $this->cadena = ereg_replace("[ÉÈÊË]", "E", $cadena);
        $this->cadena = ereg_replace("[óòôõöº]", "o", $cadena);
        $this->cadena = ereg_replace("[ÓÒÔÕÖ]", "O", $cadena);
        $this->cadena = ereg_replace("[úùûü]", "u", $cadena);
        $this->cadena = ereg_replace("[ÚÙÛÜ]", "U", $cadena);
        $this->cadena = ereg_replace("[^´`¨~]", "", $cadena);
        $this->cadena = str_replace("ç", "c", $cadena);
        $this->cadena = str_replace("Ç", "C", $cadena);
        $this->cadena = str_replace("ñ", "n", $cadena);
        $this->cadena = str_replace("Ñ", "N", $cadena);
        $this->cadena = str_replace("Ý", "Y", $cadena);
        $this->cadena = str_replace("ý", "y", $cadena);
        // return $cadena;
    }
    public function getCadena(){
        return $this->cadena;
    }

}

?>
