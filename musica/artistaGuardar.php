<?php
require_once "_variosMusica.php";

$pdo = obtenerPdoConexionBD();

session_start();

// Se recogen los datos del formulario de la request.
$id = (int)$_REQUEST["id"];
$nombre = $_REQUEST["nombre"];

// Si id es -1 quieren CREAR una nueva entrada ($nuevaEntrada tomará true).
// Sin embargo, si id NO es -1 quieren VER la ficha de un artista existente
// (y $nuevaEntrada tomará false).
$nuevaEntrada = ($id == -1);

if ($nuevaEntrada) {
    // Quieren CREAR una nueva entrada, así que es un INSERT.
    $sql = "INSERT INTO artista (nombre) VALUES (?)";
    $parametros = [$nombre];
} else {
    // Quieren MODIFICAR un artista existente y es un UPDATE.
    $sql = "UPDATE artista SET nombre=? WHERE id=?";
    $parametros = [$nombre, $id];
}

$sentencia = $pdo->prepare($sql);
//Esta llamada devuelve true o false según si la ejecución de la sentencia ha ido bien o mal.
$sqlConExito = $sentencia->execute($parametros); // Se añaden los parámetros a la consulta preparada.

//Se consulta la cantidad de filas afectadas por la ultima sentencia sql.
$unaFilaAfectada = ($sentencia->rowCount() == 1);
$ningunaFilaAfectada = ($sentencia->rowCount() == 0);

// Está todo correcto de forma normal si NO ha habido errores y se ha visto afectada UNA fila.
$correcto = ($sqlConExito && $unaFilaAfectada);

// Si los datos no se habían modificado, también está correcto.
$datosNoModificados = ($sqlConExito && $ningunaFilaAfectada);
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

<?php
//Todo bien tanto si se han guardado los datos nuevos como si no se habían modificado.
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
    <p>No se han podido guardar los datos del artista.</p>

    <?php
}
?>

<a href="artistaListado.php">Volver al listado de artistas.</a>

</body>

</html>
