<?php
// comprueba si el boton iniciar sesion fue pulsado
//session_start
if (isset($_REQUEST['iniciarSesion'])) {
    header("Location: codigoPHP/login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Tema 5 · Login Logoff · Juan Miguel Dominguez </title>
        <link rel="stylesheet" href="webroot/css/ejercicios.css">
    </head>
    <body>
        <header>
            <div class="logo">
                <img src="webroot/images/logoProyectoTema5.png" alt="logo"/>
            </div>
            <h2> Inicio Publico</h2>

        </header>

        <main>
            <div class="container">
            
            <h3>Bienvenido a Proyecto Login Logoff </h3>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="submit" name="iniciarSesion" id="iniciarSesion" value="Iniciar Sesion"/>
            </form> 
            
            <img src="webroot/images/arbolNavegacion.png" alt="alt"/>
            </div>
            

        </main>

        <footer>
            <address>
                <p class="foo-txt">
                    © 2026 <span ><a class="miweb-link" href="../JDPDWESProyectoDWES/indexProyectoDWES.php" >Juan Miguel Dominguez</a></span> Todos los derechos reservados.
                    <a href="https://github.com/Juan-D07/JDPDWESLoginLogoffTema5" target="_blank"> <img class="github" src="webroot/images/github.png"  alt="Github logo"/></a>
                </p>
            </address>
        </footer>

    </body>
</html>

