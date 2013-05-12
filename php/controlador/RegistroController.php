<?php
session_start();
/**
 * Description of RegistroController
 *
 * @author nico
 * 
 * El objetivo de esta archivo es hacer de capa intermedia en el registro de usuario
 * esto es debido a que tenmos que atacar a dos clases dao diferentes y establcer 
 * algo de logica en medio
 */

$base_dir = realpath(dirname(__FILE__) . '/..');
//definimos como directorio base el directorio en el que estamos en este caso es /php
require_once("{$base_dir}/modelo/PersonasDAO.php");
require_once("{$base_dir}/modelo/UserDAO.php");
require_once '../modelo/modeloException/ModeloException.php';

//comprovamos que el id de sesion es el mismo que ha obtenido el usuario en la pagina de registro
//y que venga de ella con la variable $_post

if(isset($_POST['nom'],$_GET['id']) && $_GET['id'] == session_id()){
    //$classPHP='index.php';
    //obtenemos las variables del $post
    $tipo='user';
    $name=$_POST['nom'];
    $ape1=$_POST['ape1'];
    $ape2=$_POST['ape2'];
    $nac=$_POST['nac'];
    $sex=$_POST['sexo'];
    $user = $_POST['user'];
    $email = $_POST['email'];
    $pass = hash("sha512", $_POST['pass']);//ciframos la contraseña
     //iniciamos el manejo de exepciones de las llamadas que hacemos
    $errno=null;
    try {
        $objPersonas = new PersonasDAO();
        $objUser = new UserDAO();
        if ($objUser->emailUser($email) && $objUser->nombreUser($user)) {
            $id = $objPersonas->getUltimoId();
            $objPersonas->registroPersonas($id,$tipo, $name, $ape1, $ape2, $nac, $sex);
            $objUser->registroUsuario($id, $user, $email, $pass);
            //iniciamos las variables de sesion necesarias para la pagina
            $_SESSION['user']=$user;
            $_SESSION['email']=$email;
            $_SESSION['pass']=$pass;
            $_SESSION['id']=$id;
           // $_SESSION['log']=true;
        }else{
            //como no se ha cumplido los valores para la inserccion volvemos a la pagina de registro
            throw new ModeloException();
        }
    } catch (PersonasException $ep) {
        //error en las personas
        $errno=1;
    } catch (UserException $eu){
        //error en los usuarios
        $errno=2;
    }  catch (ModeloException $em){
        //el usuario o el email ya existe
        $errno=3;
    }catch (Exception $exc) {
        //error desconocido
        $errno=4;
    }
    if(is_numeric($errno)){
        $classPHP="registro.php?errno=$errno";
    }else{
        $classPHP='inicio.php';
    }
}
elseif (isset($_SESSION['user'],$_SESSION['id'],$_SESSION['pass'],$_SESSION['email'])) {
    $classPHP='inicio.php';
}
else {
    $classPHP='index.php';
}
//regeneramos el id para que el usuario no tenga conocimiento del mismo
session_regenerate_id(true);
header("location: ../../$classPHP")
?>
