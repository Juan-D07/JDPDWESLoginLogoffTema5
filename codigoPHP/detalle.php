<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Tema 5 · Juan Miguel Dominguez </title>
        <link rel="stylesheet" href="../webroot/css/ejercicios.css">
        <style>

            body{
                background-color: white;
            }
        </style>



    </head>
    <body>
        <header>

            <h2> UT5: DESARROLLO DE APLICACIONES WEB</h2>
        </header>

        <main>
            <?php
            echo '<h3> Mostrar Variables superglobales</h3>';

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
                echo "<h3>$nombreSuperglobal</h3>";

                if (!empty($arraySuperglobal)) {
                    echo "<ul>";

                    foreach ($arraySuperglobal as $nombre => $contenido) {
                        if (is_array($contenido)) {
                            $contenido = "<pre>" . print_r($contenido, true) . "</pre>";
                        }
                        echo "<li><strong>$nombre : </strong>" . htmlspecialchars($contenido). "</li>";

                    }
                    echo "</ul>";
                } else {
                    echo "<div class='vacio'>La variable $nombreSuperglobal esta vacia en este momento.</div>";
                }
            }

            phpinfo();
            ?>
        </main>

        <footer>
            <address>
                <p class="foo-txt">
                    © 2026 <span ><a class="miweb-link" href="../../JDPDWESProyectoDWES/indexProyectoDWES.php" >Juan Miguel Dominguez</a></span> Todos los derechos reservados.
                    <a href="https://github.com/Juan-D07/JDPDWESProyectoTema5" target="_blank"> <img class="github" src="../webroot/images/github.png"  alt="Github logo"/></a>
                    <a href="../indexProyectoTema5.php"> <img class="casa" src="../webroot/images/casa.png"  alt="Casa logo"/></a>
                </p>
            </address>
        </footer>

    </body>
</html>