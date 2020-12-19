<?php
require_once "_variosMusica.php";

$conexion = obtenerPdoConexionBD();

session_start();

if(!isset($_SESSION["tema"])){  //Establece el tema claro si es la primera vez que se accede a la lista y por ello no existe sesión.
    $_SESSION["tema"] = "claro";
}

//Variables para cada parametro en caso de que lleguen y usarlas en otros apartados.
$mostrarFavoritos = isset($_REQUEST["favoritos"]);

$mostrarAlbum = isset($_REQUEST["album"]);

$ordenColumnas = isset($_REQUEST["orden"]);

//Clausulas para sql
$clausulaWhereFavoritos = $mostrarFavoritos ? "WHERE c.favoritos=1" : ""; //Clausula where de sql para ver solo favoritos.

$clausulaWhereAlbum = $mostrarAlbum ? "WHERE c.album='$_REQUEST[album]'" : ""; //Clausula where de sql para ver solo canciones del mismo album.

$clausulaOrder = $ordenColumnas ? $_REQUEST["orden"] : "a.nombre"; //Clausula where de sql para ordenar las canciones según la request.


$sql = "
               SELECT
                    c.id     AS cId,
                    c.nombre AS cNombre,
                    c.album AS cAlbum,
                    c.duracion AS cDuracion,
                    c.favoritos AS cFavoritos,
                    a.id     AS aId,
                    a.nombre AS aNombre
                FROM
                   cancion AS c INNER JOIN artista AS a
                   ON c.artistaId = a.id
                $clausulaWhereAlbum 
                $clausulaWhereFavoritos
                ORDER BY $clausulaOrder
            ";

$select = $conexion->prepare($sql);
$select->execute([]); // Array vacío porque la consulta preparada no requiere parámetros.
$rs = $select->fetchAll();

if(isset($_REQUEST["favoritos"])){  //Añade a la URL en caso de que sea favoritos para poder ordenarlos posteriormente.
    $favoritosUrl = "favoritos&";
} else {
    $favoritosUrl = "";
}

if(isset($_REQUEST["album"])){  //Añade a la URL en caso de que sea album para poder ordenarlos posteriormente.
    $albumUrl = "album=$_REQUEST[album]&";
} else {
    $albumUrl = "";
}

// INTERFAZ:
// $_SESSION["tema"]
// $rs
// $mostrarFavoritos
// $mostrarAlbum
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

<h1>Listado de Canciones</h1>
<?php

if($_SESSION["tema"] == "oscuro") {
    $tema = "claro";
} else if($_SESSION["tema"] == "claro") {
    $tema = "oscuro";
} else{
    $tema = "oscuro";
}

?>

<a href="temaEstablecer.php?tema=<?=$tema?>">Cambiar a tema <?=$tema?></a>

<br />
<br />

<table border='1' id='tabla'>

    <tr>
        <th><a href="cancionListado.php?<?=$favoritosUrl?><?=$albumUrl?>orden=c.nombre">Nombre</a></th>
        <th><a href="cancionListado.php?<?=$favoritosUrl?><?=$albumUrl?>orden=c.album">Album</a></th>
        <th><a href="cancionListado.php?<?=$favoritosUrl?><?=$albumUrl?>orden=c.duracion">Duración</a></th>
        <th><a href="cancionListado.php?<?=$favoritosUrl?><?=$albumUrl?>orden=a.nombre">Artista</a></th>
    </tr>

    <?php
    foreach ($rs as $fila) { ?>
        <tr>
            <td>
                <?php
                echo "<a href='cancionFicha.php?id=$fila[cId]'>";
                echo "$fila[cNombre]";
                echo "</a>";

                if ($fila["cFavoritos"]) {
                    $urlImagen = "img/estrellaRellena.png";
                    $parametroFavoritos = "estrella";
                } else {
                    $urlImagen = "img/estrellaVacia.png";
                    $parametroFavoritos= "";
                }
                echo " <a href='cancionFavoritosRedireccionar.php?id=$fila[cId]'><img src='$urlImagen' width='16' height='16'></a>";
                ?>
            </td>
            <td><a href='cancionListado.php?album=<?=$fila["cAlbum"]?>'> <?= $fila["cAlbum"] ?> </a></td>
            <td><p>                                                    <?= $fila["cDuracion"]?> </p></td>
            <td><a href='artistaFicha.php?id=<?=$fila["aId"]?>'>        <?= $fila["aNombre"] ?> </a></td>
            <td><a href='cancionEliminar.php?id=<?=$fila["cId"]?>'>                (X)          </a></td>
        </tr>
    <?php } ?>

</table>

<br />

<?php if (!$mostrarFavoritos && !$mostrarAlbum) {?>
    <a href='cancionListado.php?favoritos'>Mostrar solo canciones en favoritos</a>
<?php } else { ?>
    <a href='cancionListado.php'>Mostrar todas las canciones</a>
<?php } ?>

<br />
<br />

<a href='cancionFicha.php?id=-1'>Crear entrada</a>

<br />
<br />

<a href='artistaListado.php'>Gestionar listado de Artistas</a>

</body>

</html>