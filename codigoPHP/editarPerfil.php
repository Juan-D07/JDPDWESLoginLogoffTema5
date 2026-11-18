<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/configDBPDO.php';
require_once '../core/libreriaValidacion.php';
//reanuda la sesion
session_start();

// Si el usuario no ha iniciado la sesion, lo redirigimos al login
if (!isset($_SESSION['usuarioJDPDWESLoginLogoffTema5'])) {
    header("Location: ./login.php");
    exit;
}
// comprueba si el boton cancelar fue pulsado
if (isset($_REQUEST['cancelar'])) {
    // encaso de que si, vuelve al indice
    header("Location: ./inicioPrivado.php");
    exit;
}


//Inicializacion de variables
//Array donde recogemos los mensajes de error
$aError = [
    'descripcion' => ""];

// declaramos los valores que se veran en la pantalla   
$sDescripcionMostrar= empty($_REQUEST["descripcion"])?$_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_DescUsuario']:$_REQUEST["descripcion"];    

// comprueba si el boton eliminar fue pulsado
if (isset($_REQUEST['eliminarCuenta'])) {
    //mover usuario a otra pantalla
    header("Location: ./eliminarPerfil.php");
    exit;

}

// comprueba si el boton eliminar fue pulsado
if (isset($_REQUEST['cambiarPassword'])) {
    //mover usuario a otra pantalla
    header("Location: ./cambiarPassword.php");
    exit;
}

// Comprueba si el boton aplicar cambios fue pulsado
if (isset($_REQUEST["aplicarCambios"])) {
    $entradaOK = true; // Variable que nos indica que todo va bien
    // Validacion de entrada Entrada
    $aError["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["descripcion"], 100, 1,1);

    foreach ($aError as $value) {
        if (!empty($value)) {
            $entradaOK = false;
        }
    }
    
    // si los datos estan bien,
    if ($entradaOK) {
        
        try {
            $miDB = new PDO(DSN, USER, PASS);

            $sqlUpdate = <<<sql
                UPDATE T01_Usuario 
                SET T01_DescUsuario = :descripcionNuevo
                WHERE T01_CodUsuario = :usuario
                sql;


            //actualizamos los datos del usuario
            $actualizarUsuario = $miDB->prepare($sqlUpdate);
            $actualizarUsuario->execute([
                ':descripcionNuevo' => $_REQUEST["descripcion"],
                ':usuario' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']]);

            //ejecutamos un select del usuario para recoger sus datos y anadirlos a la sesion
            $sqlSelect = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :codUsuario";
            $selectUsuario = $miDB->prepare($sqlSelect);
            
            $selectUsuario->execute([
                ':codUsuario' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']
            ]);

            //recogemos usuario
            $aUsuario=$selectUsuario->fetch(PDO::FETCH_ASSOC);
            // Guardamos en la sesion los datos requeridos para la vista privada
            $_SESSION['usuarioJDPDWESLoginLogoffTema5'] = $aUsuario;
        } catch (PDOException $miExceptionPDO) {
            echo "<h3 style='margin-top:0;'>Error en la base de datos.</h3>";
            echo "<p><b>Mensaje:</b> " . $miExceptionPDO->getMessage() . "</p>";
            echo "<p><b>Código:</b> " . $miExceptionPDO->getCode() . "</p>";
            
        } finally {
            //cerramos la conexion
            unset($miDB);
        }

        //redirigimos al area privada
        header("Location: ./inicioPrivado.php");
        exit;
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
            <h2> Editar Usuario</h2>

        </header>

        <main>
            <div class="container">

                <h3>Datos del Usuario </h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
                    <table class="formularioUsuario">
                        <tr>
                    <td><label for="usuario" >Usuario:</label></td>
                    <td><input type="text" name="usuario" id="usuario" value="<?php echo $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']?>" readonly/></td>
                        </tr>
                        <tr>
                    <td><label for="usuario" >Perfil:</label></td>
                    <td><input type="text" name="perfil" id="perfil" value="<?php echo $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_Perfil']?>" readonly/></td>
                        </tr>
                        <tr>
                    <td><label for="contrasena" >Descripcion:</label></td>
                    <td><input type="text" name="descripcion" id="descripcion" value="<?php echo empty($aError["descripcion"]) ? "$sDescripcionMostrar" : '' ?>"/></td>
<?php echo!empty($aError["descripcion"]) ? "<td><span style='color:red;'>" . $aError['descripcion'] . "</span></td>" : '<td></td>' ?>
                        </tr>
                    </table>
                    <br/>
                    <input type="submit" name="cancelar" id="cancelar" value="Cancelar"/>
                    <input type="submit" name="eliminarCuenta" id="eliminarCuenta" class="botonLargo" value="Eliminar Cuenta"/>
                    <input type="submit" name="cambiarPassword" id="cambiarPassword" class="botonLargo" value="Cambiar Contraseña"/>
                    <input type="submit" name="aplicarCambios" id="aplicarCambios" class="botonLargo" value="Aplicar Cambios"/>
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
