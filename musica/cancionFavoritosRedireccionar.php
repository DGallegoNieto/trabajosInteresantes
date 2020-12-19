<?php
require_once "_variosMusica.php";

$conexion = obtenerPdoConexionBD();

$id = (int)$_REQUEST["id"];

$sql = "UPDATE cancion SET favoritos = (NOT (SELECT favoritos FROM cancion WHERE id=?)) WHERE id=?";

$select = $conexion->prepare($sql);
$select->execute([$id, $id]);
$rs = $select->fetchAll();

redireccionar("cancionListado.php");







?>