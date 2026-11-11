<?php
// Check if the button with name="my_button" was clicked
//session_start
if (isset($_REQUEST['login'])) {
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
        <link rel="stylesheet" href="webroot/font/Oswald-VariableFont_wght.ttf">
        <link rel="stylesheet" href="webroot/css/styles.css">
    </head>
    <body>
        <header>

            <h2> UT5: Login/Logoff</h2>
            <h2> Inicio Publico</h2>

        </header>

        <main>
            
            
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="submit" name="login" id="login" value="Iniciar Sesion"/>
            </form> 

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

