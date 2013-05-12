<?php

/**
 * clase que comprueba las distintas variables de entorno  y carga los datos para mostrar
 * en la pagina de inicio de la palicacion
 *
 * @author nico
 */
$base_dir = realpath(dirname(__FILE__) . '/../../');
require_once $base_dir."/php/modelo/UserDAO.php";

session_start();

//si hemos entrado por post y venimos de la pagina de inicio de session
$error=null;
if (isset($_POST['user'], $_POST['pass']) && $_GET['id'] == session_id()) {

    try {
        $objUser = new UserDAO();
        $pass = hash("sha512", $_POST['pass']);
        //comprobamos que el usuario coincide con la contraseña
        $arrayUser = $objUser->userpass($_POST['user'], $pass);
        //si esto esta vacio es por que ha devuleto null
        //es decir el usuario con esa contraseña no  existe en la bd

        if (!$arrayUser) {
            throw new ModeloException();
        } 
        
        if (!$objUser->conexion($arrayUser[0])){
            throw new ModeloException();
        }
            
            $_SESSION['user'] = $_POST['user'];
            $_SESSION['email'] = $arrayUser[1];
            $_SESSION['pass'] = $pass;
            $_SESSION['id'] = $arrayUser[0];

    } catch (UserException $eu) {
        //el error es 1 si  ha habido un problema en laa bd
        $error=1;
    } catch (ModeloException $em) {
        //si el usuario no existe
        $error=2;
    } catch (RuntimeException $e) {
        //error desconocido
        $error=3;
    }
}
//todo ha salido ok
if($error===null) {
    header("location: ../../inicio.php");
}
//se ha controlado una exepcion
elseif (is_numeric($error)) {
    header("location: ../../index.php?errno=$error");
}
//alguien se ha colado y le devolvemos a el index
else {
    header("location: ../../index.php");
}
?>
