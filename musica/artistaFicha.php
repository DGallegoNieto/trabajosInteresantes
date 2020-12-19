<?php
require_once "_variosMusica.php";

$pdo = obtenerPdoConexionBD();

session_start();

// Se recoge el parámetro "id" de la request.
$id = (int)$_REQUEST["id"];

// Si id es -1 quieren CREAR una nueva entrada ($nuevaEntrada tomará true).
// Sin embargo, si id NO es -1 quieren VER la ficha de un artista existente
// (y $nuevaEntrada tomará false).
$nuevaEntrada = ($id == -1);

if ($nuevaEntrada) { // Quieren CREAR una nueva entrada, así que no se cargan datos.
    $artistaNombre = "<introduzca nombre>";
} else { // Quieren VER la ficha de un artista existente, cuyos datos se cargan.
    $sql = "SELECT nombre FROM artista WHERE id=?";

    $select = $pdo->prepare($sql);
    $select->execute([$id]); // Se añade el parámetro a la consulta preparada.
    $rs = $select->fetchAll();

    // Con esto, accedemos a los datos de la primera (y esperemos que única) fila que haya venido.
    $artistaNombre = $rs[0]["nombre"];
}


$sqlAlbums = "
SELECT c.album AS cAlbum, a.id AS aId
FROM cancion AS c INNER JOIN artista AS a
ON c.artistaId = a.id
WHERE a.id = ?
GROUP BY c.album";

$select = $pdo->prepare($sqlAlbums);
$select->execute([$id]); // Se añade el parámetro a la consulta preparada.
$rsAlbum = $select->fetchAll();

?>

<html>

<head>
    <meta charset="UTF-8">
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
    <h1>Nueva ficha de artista</h1>
<?php } else { ?>
    <h1>Ficha de artista</h1>
<?php } ?>

<form method="post" action="artistaGuardar.php">

    <input type="hidden" name="id" value="<?=$id?>" />

    <ul>
        <li>
            <strong>Nombre: </strong>
            <input type="text" name="nombre" value="<?=$artistaNombre?>" />
        </li>
        <li>
            <strong>Albums: </strong>
            <select name='artistaId'>
                <?php
                foreach ($rsAlbum as $filaAlbum) {
                    $artistaId = (int) $filaAlbum["aId"];
                    $albumNombre = $filaAlbum["cAlbum"];

                    echo "<option value='$artistaId'>$albumNombre</option>";

                }
                ?>
            </select>
        </li>
        <li>
            <a href=" https://www.youtube.com/results?search_query=<?=$artistaNombre?>">Búsqueda</a>
        </li>
    </ul>

    <?php if ($nuevaEntrada) { ?>
        <input type="submit" name="crear" value="Añadir artista" />
    <?php } else { ?>
        <input type="submit" name="guardar" value="Guardar cambios" />
    <?php } ?>

</form>

<br />

<a href="artistaEliminar.php?id=<?=$id ?>">Eliminar artista</a>

<br />
<br />

<a href="artistaListado.php">Volver al listado de artistas.</a>

</body>

</html>