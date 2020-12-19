<?php

require_once "_variosMusica.php";
session_start();

if(!isset($_REQUEST["tema"]) && !isset($_SESSION["tema"])){
    $_SESSION["tema"] = "claro";
} else{
    $_SESSION["tema"] = $_REQUEST["tema"];
}

redireccionar("cancionListado.php");

