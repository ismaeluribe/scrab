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
$classPHP='index.php';
if($_GET['id'] == session_id() && isset($_POST['nom'])){
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
            $classPHP='registro.php';
        }
    } catch (PersonasException $ep) {
        echo $ep;
    } catch (UserException $eu){
        echo $eu;
    }  catch (ModeloException $em){
        echo $em;
    }catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }

}else {

    $classPHP='inicio.php';
}
session_regenerate_id(true);
header("location:../../$classPHP")
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
//echo "he llegado";*/
 /*[nom] => asad
    [ape1] => asad
    [ape2] => as
    [nac] => 1990-12-19
    [sexo] => h
    [user] => nico1
    [email] => aurapato2000@hotmail.com
    [pass] => 123456
    [pass2] => 123456
    [submit] => */
?>
