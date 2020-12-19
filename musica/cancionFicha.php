    <?php
    require_once "_variosMusica.php";

    $conexion = obtenerPdoConexionBD();
    session_start();
    // Se recoge el parámetro "id" de la request.
    $id = (int)$_REQUEST["id"];

    // Si id es -1 quieren CREAR una nueva entrada ($nuevaEntrada tomará true).
    // Sin embargo, si id NO es -1 quieren VER la ficha de una canción existente
    // (y $nuevaEntrada tomará false).
    $nuevaEntrada = ($id == -1);

    if ($nuevaEntrada) { // Quieren CREAR una nueva entrada, así que no se cargan datos.
    $cancionNombre = "<introduzca nombre>";
    $cancionAlbum = "<introduzca album>";
    $cancionDuracion = "<introduzca duración>";
    $cancionFavoritos = false;
    $cancionArtistaId = 0;
    } else { // Quieren VER la ficha de una canción existente, cuyos datos se cargan.
    $sqlCancion = "SELECT * FROM cancion WHERE id=?";

    $select = $conexion->prepare($sqlCancion);
    $select->execute([$id]); // Se añade el parámetro a la consulta preparada.
    $rsCancion = $select->fetchAll();

    // Con esto, accedemos a los datos de la primera (y esperemos que única) fila que haya venido.
    $cancionNombre = $rsCancion[0]["nombre"];
    $cancionAlbum = $rsCancion[0]["album"];
    $cancionDuracion = $rsCancion[0]["duracion"];
    $cancionFavoritos = ($rsCancion[0]["favoritos"] == 1); // En BD está como TINYINT. 0=false, 1=true. Con esto convertimos a booolean.
    $cancionArtistaId = $rsCancion[0]["artistaId"];
    }



    // Con lo siguiente se deja preparado un recordset con todos los artistas.

    $sqlArtistas = "SELECT * FROM artista ORDER BY nombre";

    $select = $conexion->prepare($sqlArtistas);
    $select->execute([]); // Array vacío porque la consulta preparada no requiere parámetros.
    $rsArtistas = $select->fetchAll();

    $nombreArtista = "";

    // INTERFAZ:
    // $_SESSION["tema"]
    // $cancionNombre
    // $cancionAlbum
    // $cancionDuracion
    // $cancionFavoritos
    // $cancionArtistaId
    // $rsArtistas
    ?>




    <html>

    <head>
    <meta charset='UTF-8'>
        <?php
        if($_SESSION["tema"] == "claro"){ //Cambia el tema dependiendo de lo que venga en la sesión.
            echo "<link href='temaClaro.css' type='text/css' rel='stylesheet'>";
        } else if($_SESSION["tema"] == "oscuro"){
            echo "<link href='temaOscuro.css' type='text/css' rel='stylesheet'>";
        } else{
            echo "<link href='temaClaro.css' type='text/css' rel='stylesheet'>";
        }

        ?>
    </head>



    <body>

    <?php if ($nuevaEntrada) { ?>
    <h1>Nueva ficha de canción</h1>
    <?php } else { ?>
    <h1>Ficha de canción</h1>
    <?php } ?>

    <form method='post' action='cancionGuardar.php'>
    <ul>

    <input type='hidden' name='id' value='<?= $id ?>' />
        <li>
            <label for='nombre'>Nombre: </label>
            <input type='text' name='nombre' value='<?=$cancionNombre ?>' />
        </li>

        <li>
            <label for='album'> Album: </label>
            <input type='text' name='album' value='<?=$cancionAlbum ?>' />
        </li>

        <li>
            <label for='duracion'> Duración: </label>
            <input type='text' name='duracion' value='<?=$cancionDuracion ?>' />
        </li>

        <li>
            <label for='artistaId'>Artista: </label>
            <select name='artistaId'>
                <?php
                foreach ($rsArtistas as $filaArtista) {
                    $artistaId = (int) $filaArtista["id"];
                    $artistaNombre = $filaArtista["nombre"];

                    if ($artistaId == $cancionArtistaId){
                        $seleccion = "selected='true'";
                        $nombreArtista = $artistaNombre;

                    }
                    else
                        $seleccion = "";

                    echo "<option value='$artistaId' $seleccion>$artistaNombre</option>";

                }
                ?>
            </select>
        </li>

        <li>
            <label for='favoritos'>Favoritos</label>
            <input type='checkbox' name='favoritos' <?= $cancionFavoritos ? "checked" : "" ?> />
        </li>

        <li>
            <a href=" https://www.youtube.com/results?search_query=<?=$cancionNombre?>+<?=$nombreArtista?>">Búsqueda</a>
        </li>

    </ul>


    <?php if ($nuevaEntrada) { ?>
        <input type='submit' name='crear' value='Crear canción' />
    <?php } else { ?>
        <input type='submit' name='guardar' value='Guardar cambios' />
    <?php } ?>

    </form>

    <?php if (!$nuevaEntrada) { ?>
    <br />
    <a href='cancionEliminar.php?id=<?=$id ?>'>Eliminar canción</a>
    <?php } ?>

    <br />
    <br />

    <a href='cancionListado.php'>Volver al listado de canciones.</a>

    </body>

    </html>