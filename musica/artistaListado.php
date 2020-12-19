<?php
	require_once "_variosMusica.php";

	$pdo = obtenerPdoConexionBD();

	session_start();

    if(!isset($_SESSION["tema"])){  //Establece el tema claro si es la primera vez que se accede a la lista
        $_SESSION["tema"] = "claro";
    }

	$sql = "SELECT id, nombre FROM artista ORDER BY nombre";

    $select = $pdo->prepare($sql);
    $select->execute([]); // Array vacío porque la consulta preparada no requiere parámetros.
    $rs = $select->fetchAll();
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

<h1>Listado de Artistas</h1>


<table border="1" id="tabla">

	<tr>
		<th>Nombre</th>
	</tr>

	<?php
        foreach ($rs as $fila) { ?>
			<tr>
				<td><a href="artistaFicha.php?id=<?=$fila["id"]?>"> <?=$fila["nombre"] ?> </a></td>
				<td><a href="artistaEliminar.php?id=<?=$fila["id"]?>"> (X) </a></td>
			</tr>
	<?php } ?>

</table>

<br />

<a href="artistaFicha.php?id=-1">Crear entrada</a>

<br />
<br />

<a href="cancionListado.php">Gestionar listado de Canciones</a>

</body>

</html>