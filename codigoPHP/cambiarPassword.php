<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/configDBPDO.php';
require_once '../core/libreriaValidacion.php';
//reanuda la sesion
session_start();

// Si el usuario no ha iniciado la sesion, lo echamos al login
if (!isset($_SESSION['usuarioJDPDWESLoginLogoffTema5'])) {
    header("Location: ./login.php");
    exit;
}

// comprueba si el boton cancelar fue pulsado
if (isset($_REQUEST['cancelar'])) {
    // encaso de que si, vuelve al indice
    header("Location: ./editarPerfil.php");
    exit;
}
require_once '../config/configDBPDO.php';
require_once '../core/libreriaValidacion.php';
//Inicializacion de variables
$entradaOK = true; // Variable que nos indica que todo va bien
//Array donde recogemos los mensajes de error
$aError = [
    'contrasena' => ""];

// Comprueba si el boton cambiar fue pulsado
if (isset($_REQUEST["cambiarContrasena"])) {
    // Validacion de entrada Entrada
    // utilizar para la creacion de la contrasena
    $aError["contrasena"] = validacionFormularios::validarPassword($_REQUEST["contrasena"], 32, 4, 2, 1);

    // comprobar si las dos contrasena son iguales
    $aError["contrasena"]= $_REQUEST["contrasena"]===$_REQUEST["segundaContrasena"]?$aError["contrasena"]:"Error, las contraseñas no son iguales";
    
    foreach ($aError as $value) {
        if (!empty($value)) {
            $entradaOK = false;
        }
    }
}
// El formulario no se ha enviado nunca
else {
    $entradaOK = false;
}
// si los datos estan bien,
if ($entradaOK) {
    
    $miDB = new PDO(DSN, USER, PASS);
    $sqlUpdate = <<<sql
        UPDATE T01_Usuario 
        SET T01_Password = SHA2(:contrasenaNuevo,256)
        WHERE T01_CodUsuario = :usuario
        sql;


    //actualizamos la contrasena del usuario
    $actualizarContrasena = $miDB->prepare($sqlUpdate);
    $actualizarContrasena->execute([
        ':contrasenaNuevo' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario'].trim($_REQUEST["contrasena"]),
        ':usuario' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']]);

    //Nuevo select del usuario para recoger sus nuevos datos y anadirlos a la sesion
    $sqlSelect = <<<sql
       SELECT * FROM T01_Usuario
       WHERE T01_CodUsuario = :codUsuario
       sql;
    $selectUsuario = $miDB->prepare($sqlSelect);
    $selectUsuario->execute([
        ':codUsuario' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']]);

    //recogemos usuario
    $aUsuario=$selectUsuario->fetch();
    // Guardamos en la sesion los datos requeridos para la vista privada
    $_SESSION['usuarioJDPDWESLoginLogoffTema5'] = $aUsuario;

    // Cerramos conexion y redirigimos al area privada
    unset($miDB);
    header("Location: ./editarPerfil.php");
    exit;
}
//Cerramos conexion si la entrada es false
unset($miDB);

// Mostrar Login
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Tema 5 · Login Logoff · Juan Miguel Dominguez </title>
        <link rel="stylesheet" href="../webroot/css/ejercicios.css">

    </head>
    <body>
        <header>
            <div class="logo">
                <img src="../webroot/images/logoProyectoTema5.png" alt="logo"/>
            </div>
            <h2> Cambio de Contraseña</h2>

        </header>

        <main>
            <div class="container">

                <h3>Introduce la Nueve Contraseña </h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
                    <table class="formularioUsuario">
                        <tr>
                    <td><label for="contrasena" >Contraseña:</label></td>
                    <td><input type="password" name="contrasena" id="contrasena" value="<?php echo!empty($_REQUEST["contrasena"])&&empty($aError["contrasena"]) ? $_REQUEST["contrasena"] : '' ?>"/></td>
<?php echo!empty($aError["contrasena"]) ? "<td><span style='color:red;'>" . $aError['contrasena'] . "</span></td>" : '<td><td/>' ?>
                        </tr>
                        <tr>
                    <td><label for="segundaContrasena" >Reescribir Contraseña:</label></td>
                    <td><input type="password" name="segundaContrasena" id="segundaContrasena" value="<?php echo!empty($_REQUEST["segundaContrasena"])&&empty($aError["contrasena"]) ? $_REQUEST["segundaContrasena"] : '' ?>"/></td>
                        </tr>
                    </table>
                    <br/>
                    <input type="submit" name="cancelar" id="cancelar" value="Cancelar"/>
                    <input type="submit" name="cambiarContrasena" id="cambiarContrasena" value="Cambiar Contrasena"/>
                    <br/>
                </form> 


            </div>


        </main>

        <footer>
            <address>
                <p class="foo-txt">
                    © 2026 <span ><a class="miweb-link" href="../../JDPDWESProyectoDWES/indexProyectoDWES.php" >Juan Miguel Dominguez</a></span> Todos los derechos reservados.
                    <a href="https://github.com/Juan-D07/JDPDWESLoginLogoffTema5" target="_blank"> <img class="github" src="../webroot/images/github.png"  alt="Github logo"/></a>
                </p>
            </address>
        </footer>

    </body>
</html>
