<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
//Array donde recogemos los mensajes de error
$aError = [
    'usuario' => "",
    'contrasena' => "",
    'descripcion' => ""];

// Comprueba si el boton crear fue pulsado
if (isset($_REQUEST["crearUsuario"])) {
    // Validacion de entrada Entrada
    $aError["usuario"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["usuario"], 100, 1, 1);
    // utilizar para la creacion de la contrasena, no la validacion
    $aError["contrasena"] = validacionFormularios::validarPassword($_REQUEST["contrasena"], 32, 4, 2, 1);
    $aError["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["descripcion"], 100, 1,1);

    // comprobar si las dos contrasena son iguales
    $aError["contrasena"]= $_REQUEST["contrasena"]===$_REQUEST["segundaContrasena"]?$aError["contrasena"]:"Error al crear la cuenta, las contrasenas no son iguales";
    
    foreach ($aError as $value) {
        if (!empty($value)) {
            $entradaOK = false;
        }
    }
    
    // comprobar que el nombre del usuario no exista
    try {

        $miDB = new PDO(DSN, USER, PASS);

        $sqlSelect = <<<sql
        SELECT * FROM T01_Usuario
        WHERE T01_CodUsuario = :codUsuario
        sql;
        $selectUsuario = $miDB->prepare($sqlSelect);
        // trim elimina espacios antes y despues del texto
        $selectUsuario->execute([
            ':codUsuario' => trim($_REQUEST["usuario"])
        ]);

        if ($selectUsuario->rowCount() != 0) {
            $entradaOK = false;
            $aError['usuario'] = "Error al crear la cuenta, ya existe un usuario con el mismo nombre";
        }
    } catch (PDOException $miExceptionPDO) {
        echo "<h3 style='margin-top:0;'>Error en la base de datos.</h3>";
        echo "<p><b>Mensaje:</b> " . $miExceptionPDO->getMessage() . "</p>";
        echo "<p><b>Código:</b> " . $miExceptionPDO->getCode() . "</p>";
        unset($miDB);
    }
}
// El formulario no se ha enviado nunca
else {
    $entradaOK = false;
}
// si los datos estan bien,
if ($entradaOK) {
    //Inicia la sesion
    session_start();
    
    //insertamos los datos del usuario
    $insertarUsuario = $miDB->prepare(
            "INSERT INTO T01_Usuario VALUES (:usuario, SHA2(:contrasena, 256), :descripcion, 1, now(), 'usuario')"
    );
    $insertarUsuario->execute([':usuario' => trim($_REQUEST["usuario"]),
        ':contrasena' => trim($_REQUEST["usuario"]).trim($_REQUEST["contrasena"]),
        ':descripcion' => trim($_REQUEST["descripcion"])
        ]);
    
    //volvemos a ejecutar el select del usuario para recoger sus datos y anadirlos a la sesion
    $selectUsuario->execute([
        ':codUsuario' => trim($_REQUEST["usuario"])
    ]);

    //recogemos usuario
    $aUsuario=$selectUsuario->fetch();
    // Guardamos en la sesion los datos requeridos para la vista privada
    $_SESSION['usuarioJDPDWESLoginLogoffTema5'] = $aUsuario;

    // Cerramos conexion y redirigimos al area privada
    unset($miDB);
    header("Location: ./inicioPrivado.php");
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
            <h2> Creacion de Usuario</h2>

        </header>

        <main>
            <div class="container">

                <h3>Registrate </h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
                    <table class="formularioUsuario">
                        <tr>
                    <td><label for="usuario" >Usuario:</label></td>
                    <td><input type="text" name="usuario" id="usuario" value="<?php echo!empty($_REQUEST["usuario"])&&empty($aError["usuario"]) ? $_REQUEST["usuario"] : '' ?>"/></td>
<?php echo!empty($aError["usuario"]) ? "<td><span style='color:red;'>" . $aError['usuario'] . "</span></td>" : '<td></td>' ?>
                        </tr>
                        <tr>
                    <td><label for="contrasena" >Contraseña:</label></td>
                    <td><input type="password" name="contrasena" id="contrasena" value="<?php echo!empty($_REQUEST["contrasena"])&&empty($aError["contrasena"]) ? $_REQUEST["contrasena"] : '' ?>"/></td>
<?php echo!empty($aError["contrasena"]) ? "<td><span style='color:red;'>" . $aError['contrasena'] . "</span></td>" : '' ?>
                        </tr>
                        <tr>
                    <td><label for="segundaContrasena" >Reescribir Contraseña:</label></td>
                    <td><input type="password" name="segundaContrasena" id="segundaContrasena" value="<?php echo!empty($_REQUEST["segundaContrasena"])&&empty($aError["contrasena"]) ? $_REQUEST["segundaContrasena"] : '' ?>"/></td>
                        </tr>
                        <tr>
                    <td><label for="contrasena" >Descripcion:</label></td>
                    <td><input type="text" name="descripcion" id="descripcion" value="<?php echo!empty($_REQUEST["descripcion"]) ? $_REQUEST["descripcion"] : '' ?>"/></td>
<?php echo!empty($aError["descripcion"]) ? "<td><span style='color:red;'>" . $aError['descripcion'] . "</span></td>" : '' ?>
                        </tr>
                    </table>
                    <br/>
                    <input type="submit" name="cancelar" id="cancelar" value="Cancelar"/>
                    <input type="submit" name="crearUsuario" id="crearUsuario" value="Crear Usuario"/>
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
