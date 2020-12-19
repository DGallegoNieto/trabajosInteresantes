<?php
require_once "_variosMusica.php";

$conexion = obtenerPdoConexionBD();
session_start();
// Se recogen los datos del formulario de la request.
$id = (int)$_REQUEST["id"];
$nombre = $_REQUEST["nombre"];
$album = $_REQUEST["album"];
$duracion = $_REQUEST["duracion"];
$artistaId = (int)$_REQUEST["artistaId"];
$favoritos = isset($_REQUEST["favoritos"]);

// Si id es -1 quieren INSERTAR una nueva entrada ($nuevaEntrada tomará true).
// Sin embargo, si id NO es -1 quieren ACTUALIZAR la ficha de una canción existente
// (y $nuevaEntrada tomar false).
$nuevaEntrada = ($id == -1);

if ($nuevaEntrada) {
    // Quieren CREAR una nueva entrada, así que es un INSERT.
    $sql = "INSERT INTO cancion (nombre, album, duracion, favoritos, artistaId) VALUES (?, ?, ?, ?, ?)";
    $parametros = [$nombre, $album, $duracion, $favoritos?1:0, $artistaId];
} else {
    // Quieren MODIFICAR una canción existente y es un UPDATE.
    $sql = "UPDATE cancion SET nombre=?, album=?, duracion=?, favoritos=?, artistaId=? WHERE id=?";
    $parametros = [$nombre, $album, $duracion, $favoritos?1:0, $artistaId, $id];
}

$sentencia = $conexion->prepare($sql);
// Esta llamada devuelve true o false según si la ejecución de la sentencia ha ido bien o mal.
$sqlConExito = $sentencia->execute($parametros); // Se añaden los parámetros a la consulta preparada.

//Se consulta la cantidad de filas afectadas por la ultima sentencia SQL.
$numFilasAfectadas = $sentencia->rowCount();
$unaFilaAfectada = ($numFilasAfectadas == 1);
$ningunaFilaAfectada = ($numFilasAfectadas == 0);

// Está todo correcto de forma NORMAL si NO ha habido errores y se ha visto afectada UNA fila.
$correcto = ($sqlConExito && $unaFilaAfectada);

// Si los datos no se habían modificado, también está correcto, pero de otra manera.
$datosNoModificados = ($sqlConExito && $ningunaFilaAfectada);
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

<?php
// Todo bien tanto si se han guardado los datos nuevos como si no se habían modificado.
if ($correcto || $datosNoModificados) { ?>

    <?php if ($id == -1) { ?>
        <h1>Inserción completada</h1>
        <p>Se ha insertado correctamente la nueva entrada de <?php echo $nombre; ?>.</p>
    <?php } else { ?>
        <h1>Guardado completado</h1>
        <p>Se han guardado correctamente los datos de <?php echo $nombre; ?>.</p>

        <?php if ($datosNoModificados) { ?>
            <p>En realidad, no había modificado nada, pero no está de más que se haya asegurado pulsando el botón de guardar :)</p>
        <?php } ?>
    <?php }
    ?>

    <?php
} else {
    ?>

    <h1>Error en la modificación.</h1>
    <p>No se han podido guardar los datos de la cancion.</p>

    <?php
}
?>

<a href='cancionListado.php'>Volver al listado de canciones.</a>

</body>

</html>