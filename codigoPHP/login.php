<?php
//Inicia la sesion
session_start();

// Si el usuario ya ha iniciado la sesion, lo redireccionamos al accesso privado
if (isset($_SESSION['usuarioJDPDWESLoginLogoffTema5'])) {
    header("Location: inicioPrivado.php");
    exit;
}

// comprueba si el boton cancelar fue pulsado
if (isset($_REQUEST['cancelar'])) {
    // encaso de que si, vuelve al indice
    header("Location: ../indexLoginLogoffTema5.php");
    exit;
}
require_once '../config/configDBPDO.php';
require_once '../core/libreriaValidacion.php';
//Inicializacion de variables
$entradaOK = true; // Variable que nos indica que todo va bien
$usuarioEncontrado = true; //Variable que nos indica si el usuario del login existe
//Array donde recogemos los mensajes de error
$aError = [
    'usuario' => "",
    'contrasena' => ""];

// Comprueba si el boton login fue pulsado
if (isset($_REQUEST["login"])) {
    // Validar Entrada
    $aError["usuario"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["usuario"], 1000, 1, 1);
    // utilizar para la creacion de la contrasena, no la validacion
    //$aError["contrasena"] = validacionFormularios::validarPassword($passwd, $maximo, $minimo, $tipo, $obligatorio);
    $aError["contrasena"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["contrasena"], 1000, 1, 1);

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
// comprueba si el usuario existe en la base de datos
if ($entradaOK) {
    try {

        $miDB = new PDO(DSN, USER, PASS);

        $sqlSelect = <<<sql
        SELECT * FROM T01_Usuario
        WHERE T01_CodUsuario = :codUsuario and T01_Password = SHA2(:password,256)
        sql;
        $selectUsuarioContrasena = $miDB->prepare($sqlSelect);
        // trim elimina espacios antes y despues del texto
        $selectUsuarioContrasena->execute([
            ':codUsuario' => trim($_REQUEST["usuario"]),
            ':password' => trim($_REQUEST["usuario"].$_REQUEST["contrasena"])
        ]);

        if ($selectUsuarioContrasena->rowCount() != 1) {
            $usuarioEncontrado = false;
        } else {
            //actualizamos los datos del usuario
            $actualizarUsuario = $miDB->prepare(
                    "UPDATE T01_Usuario 
                     SET T01_NumConexiones = T01_NumConexiones + 1, 
                         T01_FechaHoraUltimaConexion = NOW() 
                     WHERE T01_CodUsuario = :usuarioUpdate"
            );
            $actualizarUsuario->execute([':usuarioUpdate' => $_REQUEST["usuario"]]);
            
            //creamos el objeto usuario
            $oUsuario=$selectUsuarioContrasena->fetch();
            // Guardamos en la sesion los datos requeridos para la vista privada
            $_SESSION['usuarioJDPDWESLoginLogoffTema5'] = $oUsuario;
            //$_SESSION['descUsuario'] = $oUsuario->T01_DescUsuario;
            // Sumamos 1 al numero de conexiones para reflejar el acceso actual
            //$_SESSION['numConexiones'] = $oUsuario->T01_NumConexiones + 1;
            //$_SESSION['fechaHoraUltimaConexionAnterior'] = $oUsuario->T01_FechaHoraUltimaConexion;

            // Cerramos conexion y redirigimos al area privada
            unset($miDB);
            header("Location: ./inicioPrivado.php");
            exit;
        }
    } catch (PDOException $miExceptionPDO) {


        echo "<h3 style='margin-top:0;'>Error en la base de datos.</h3>";
        echo "<p><b>Mensaje:</b> " . $miExceptionPDO->getMessage() . "</p>";
        echo "<p><b>Código:</b> " . $miExceptionPDO->getCode() . "</p>";
    } finally {
        unset($miDB);
    }
}
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
            <h2> Inicio de Sesion</h2>

        </header>

        <main>
            <div class="container">

                <h3>Inicia session </h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>

                    <label for="usuario" >Usuario:</label>
                    <input type="text" name="usuario" id="usuario" value="<?php echo!empty($_REQUEST["usuario"]) ? $_REQUEST["usuario"] : '' ?>"/>
<?php echo!empty($aError["usuario"]) ? "<span style='color:red;'>" . $aError['usuario'] . "</span>" : '' ?>
                    <br/><br/>

                    <label for="contrasena" >Contrasena:</label>
                    <input type="password" name="contrasena" id="contrasena" value="<?php echo!empty($_REQUEST["contrasena"]) ? $_REQUEST["contrasena"] : '' ?>"/>
<?php echo!empty($aError["contrasena"]) ? "<span style='color:red;'>" . $aError['contrasena'] . "</span>" : '' ?>
                    <br/>
                    <input type="submit" name="cancelar" id="cancelar" value="Cancelar"/>
                    <input type="submit" name="login" id="login" value="Log in"/>
                    <br/>
<?php echo!$usuarioEncontrado ? "<span style='color:red;'>Usuario no encontrado, nombre de usuario o contrasena incorrecta</span>" : '' ?>
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
