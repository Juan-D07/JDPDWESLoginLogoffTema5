<?php
//Iniciamos la sesion
session_start();

// Si el usuario no ha iniciado la sesion, lo echamos al login
if (!isset($_SESSION['codUsuario'])) {
    header("Location: login.php");
    exit;
}

// comprueba si el boton cerrar sesion fue pulsado
if (isset($_REQUEST['cerrarSesion'])) {
    session_destroy();
    header("Location: ../indexLoginLogoffTema5.php");
    exit();
}
// comprueba si el boton entrar detalle fue pulsado
if (isset($_REQUEST['entrarDetalle'])) {
    header("Location: ./detalle.php");
    exit();
}
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
            <h2> Inicio Privado</h2>

        </header>

        <main>
            <div class="container">
            
            <h3>Bienvenido <?php echo $_SESSION['descUsuario']; ?> al Accesso Privado </h3>
            
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="submit" name="cerrarSesion" id="cerrarSesion" value="Cerrar Sesion"/>
                <input type="submit" name="entrarDetalle" id="entrarDetalle" value="Entrar Detalle"/>
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