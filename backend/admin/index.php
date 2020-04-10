<?php

	include "vista.php";
	include "modelo.php";

	// Llega la información de la acción que se quiere realizar, se comprueba por métodos get y post
	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
	} else if (isset ($_POST["accion"])) {
		$accion = $_POST["accion"];
	}

	// Llega identificador de la acción
	if (isset($_GET["id"])) {
		$id = $_GET["id"];
	} else if (isset ($_POST["id"])) {
		$id = $_POST["id"];
	}

	

?>
