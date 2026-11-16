<?php

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
//funcion que comprueba que la contrasena escrita sea la correcta
function confirmarContrasena(){
    try {
        $miDB = new PDO(DSN, USER, PASS);
        $sqlSelectContrasena = <<<sql
        SELECT * FROM T01_Usuario
        WHERE T01_Password = SHA2(:contrasena,256)
        sql;
        $selectUsuarioContrasena = $miDB->prepare($sqlSelectContrasena);
        // trim elimina espacios antes y despues del texto, saneamiento de datos
        $selectUsuarioContrasena->execute([
            ':contrasena' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario'].trim($_REQUEST["contrasena"])
        ]);
        return $selectUsuarioContrasena->rowCount() > 0;
    } catch (PDOException $miExceptionPDO) {
        echo "<h3 style='margin-top:0;'>Error en la base de datos.</h3>";
        echo "<p><b>Mensaje:</b> " . $miExceptionPDO->getMessage() . "</p>";
        echo "<p><b>Código:</b> " . $miExceptionPDO->getCode() . "</p>";
        unset($miDB);
    }
}

//Inicializacion de variables
//Array donde recogemos los mensajes de error
$aError = [
    'usuario' => "",
    'descripcion' => "",
    'contrasena' => ""];

// declaramos los valores que se veran en la pantalla   
$sUsuarioMostrar= empty($_REQUEST["usuario"])? $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']:$_REQUEST["usuario"];
$sDescripcionMostrar= empty($_REQUEST["descripcion"])?$_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_DescUsuario']:$_REQUEST["descripcion"];    

// comprueba si el boton eliminar fue pulsado
if (isset($_REQUEST['eliminarCuenta'])) {
    //comprobacion de la contraseña
    if(!confirmarContrasena()){
        $aError['contrasena'] = "Contraseña incorrecta, vuelva a intentar";
    }
    else{
    // elimina el usuario de la bases de datos
        try {
            $miDB = new PDO(DSN, USER, PASS);

            $sqlSelect = <<<sql
            delete FROM T01_Usuario
            WHERE T01_CodUsuario = :codUsuario
            sql;
            $selectUsuario = $miDB->prepare($sqlSelect);
            $selectUsuario->execute([
                ':codUsuario' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']
            ]);

        } catch (PDOException $miExceptionPDO) {
            echo "<h3 style='margin-top:0;'>Error en la base de datos.</h3>";
            echo "<p><b>Mensaje:</b> " . $miExceptionPDO->getMessage() . "</p>";
            echo "<p><b>Código:</b> " . $miExceptionPDO->getCode() . "</p>";
            unset($miDB);
        }
        finally {
            unset($miDB);
        }
        session_destroy();
        header("Location: ../indexLoginLogoffTema5.php");
        exit;
    }
}

// comprueba si el boton eliminar fue pulsado
if (isset($_REQUEST['cambiarPassword'])) {
    //comprobacion de la contraseña
    if(!confirmarContrasena()){
        $aError['contrasena'] = "Contraseña incorrecta, vuelva a intentar";
    }
    //mover usuario a otra pantalla
    else{
        header("Location: ./cambiarPassword.php");
        exit;
    }
}

// Comprueba si el boton aplicar cambios fue pulsado
if (isset($_REQUEST["aplicarCambios"])) {
    $entradaOK = true; // Variable que nos indica que todo va bien
    // Validacion de entrada Entrada
    $aError["usuario"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["usuario"], 100, 1, 1);
    $aError["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["descripcion"], 100, 1,1);
    $aError["contrasena"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST["contrasena"], 100, 1,1);

    foreach ($aError as $value) {
        if (!empty($value)) {
            $entradaOK = false;
        }
    }
    //comprobacion de la contraseña
    if(!confirmarContrasena()){
        $entradaOK = false;
        $aError['contrasena'] = "Contraseña incorrecta, vuelva a intentar";
    }
    
    // comprobar que el nombre del usuario no exista en la bases de datos
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
        //al buscar por el nombre
        //mientras que no haiga mas de una fila con el mismo nombre de usuario (cambio de nombre),
        //o que haiga una fila pero tenga el mismo nombre de usuario que el 
        if (!($selectUsuario->rowCount() == 0 ||($selectUsuario->rowCount() == 1 && $selectUsuario->fetchObject()->T01_CodUsuario == $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']))) {
            $entradaOK = false;
            $aError['usuario'] = "Error al editar la cuenta, ya existe un usuario con el mismo nombre";
        }
        
        
    } catch (PDOException $miExceptionPDO) {
        echo "<h3 style='margin-top:0;'>Error en la base de datos.</h3>";
        echo "<p><b>Mensaje:</b> " . $miExceptionPDO->getMessage() . "</p>";
        echo "<p><b>Código:</b> " . $miExceptionPDO->getCode() . "</p>";
        unset($miDB);
    }
    // si los datos estan bien,
    if ($entradaOK) {
        $sqlUpdate = <<<sql
            UPDATE T01_Usuario 
            SET T01_CodUsuario = :usuarioNuevo,
                T01_Password = SHA2(:contrasenaNuevo,256),
                T01_DescUsuario = :descripcionNuevo
            WHERE T01_CodUsuario = :usuarioViejo
            sql;


        //actualizamos los datos del usuario
        $actualizarUsuario = $miDB->prepare($sqlUpdate);
        $actualizarUsuario->execute([':usuarioNuevo' => $_REQUEST["usuario"],
            ':contrasenaNuevo' => trim($_REQUEST["usuario"]).trim($_REQUEST["contrasena"]),
            ':descripcionNuevo' => $_REQUEST["descripcion"],
            ':usuarioViejo' => $_SESSION['usuarioJDPDWESLoginLogoffTema5']['T01_CodUsuario']]);

        //volvemos a ejecutar el select del usuario para recoger sus datos y anadirlos a la sesion
        $selectUsuario->execute([
            ':codUsuario' => trim($_REQUEST["usuario"])
        ]);

        //recogemos usuario
        $aUsuario=$selectUsuario->fetch(PDO::FETCH_ASSOC);
        // Guardamos en la sesion los datos requeridos para la vista privada
        $_SESSION['usuarioJDPDWESLoginLogoffTema5'] = $aUsuario;

        // Cerramos conexion y redirigimos al area privada
        unset($miDB);
        header("Location: ./inicioPrivado.php");
        exit;
    }
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
            <h2> Editar Usuario</h2>

        </header>

        <main>
            <div class="container">

                <h3>Datos del Usuario </h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
                    <table class="formularioUsuario">
                        <tr>
                    <td><label for="usuario" >Usuario:</label></td>
                    <td><input type="text" name="usuario" id="usuario" value="<?php echo empty($aError["usuario"]) ? "$sUsuarioMostrar" : '' ?>"/></td>
<?php echo!empty($aError["usuario"]) ? "<td><span style='color:red;'>" . $aError['usuario'] . "</span></td>" : '<td></td>' ?>
                        </tr>
                        <tr>
                    <td><label for="contrasena" >Descripcion:</label></td>
                    <td><input type="text" name="descripcion" id="descripcion" value="<?php echo empty($aError["descripcion"]) ? "$sDescripcionMostrar" : '' ?>"/></td>
<?php echo!empty($aError["descripcion"]) ? "<td><span style='color:red;'>" . $aError['descripcion'] . "</span></td>" : '' ?>
                        </tr>
                        <tr>
                    <td><label for="contrasena" >Confirmar Contraseña:</label></td>
                    <td><input type="password" name="contrasena" id="contrasena" value="<?php echo!empty($_REQUEST["contrasena"])&&empty($aError["contrasena"]) ? $_REQUEST["contrasena"] : '' ?>"/></td>
<?php echo!empty($aError["contrasena"]) ? "<td><span style='color:red;'>" . $aError['contrasena'] . "</span></td>" : '' ?>
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
