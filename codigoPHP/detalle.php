<?php
//Iniciamos sesion
session_start();

// Si el usuario no ha iniciado la sesion, lo echamos al login
if (!isset($_SESSION['usuarioJDPDWESLoginLogoffTema5'])) {
    header("Location: login.php");
    exit;
}

// comprueba si el boton volver fue pulsado
if (isset($_REQUEST['volver'])) {
    header("Location: ./inicioPrivado.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Tema 5 · Juan Miguel Dominguez </title>
        <link rel="stylesheet" href="../webroot/css/ejercicios.css">
        <style>

            .container{
                text-align: left;
            }
        </style>



    </head>
    <body>
        <header>
            <div class="logo">
                <img src="../webroot/images/logoProyectoTema5.png" alt="logo"/>
            </div>
            <h2> Detalles</h2>
        </header>

        <main>
            <div class="container">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="submit" name="volver" id="volver" value="Volver"/>
                </form> 
                <h3> Mostrar Variables superglobales</h3>

                <?php
                $aSuperglobales = [
                    '$_SESSION' => $_SESSION,
                    '$_COOKIE' => $_COOKIE,
                    '$_SERVER' => $_SERVER,
                    '$_REQUEST' => $_REQUEST,
                    '$_GET' => $_GET,
                    '$_POST' => $_POST,
                    '$_FILES' => $_FILES,
                    '$_ENV' => $_ENV
                ];

                // Recorrido del array
                foreach ($aSuperglobales as $nombreSuperglobal => $arraySuperglobal) {
                    echo "<h3 class='superGlobal'>$nombreSuperglobal</h3>";

                    if (!empty($arraySuperglobal)) {
                        echo "<ul>";

                        foreach ($arraySuperglobal as $nombre => $contenido) {
                            if (is_array($contenido)) {
                                $contenido = "<pre>" . print_r($contenido, true) . "</pre>";
                            }
                            echo "<li><span style='color:blue;font-weight:bold'>$nombre : </span> $contenido</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<div class='vacio'>La variable $nombreSuperglobal esta vacia en este momento.</div>";
                    }
                }

                phpinfo();
                ?>
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