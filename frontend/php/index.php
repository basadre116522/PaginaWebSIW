<?php

	include "modelo.php";
	include "vista.php";

	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
	} elseif (isset($_POST["accion"])) {
		$accion = $_POST["accion"];
	} else {
		$accion = "";
	}

	if (isset($_GET["id"])) {
		$id = $_GET["id"];
	} elseif (isset($_POST["id"])) {
		$id = $_POST["id"];
	} else {
		$id = "";
	}

	if (isset($_GET["idraza"])) {
		$idraza = $_GET["idraza"];
	} elseif (isset($_POST["id"])) {
		$idraza = $_POST["idraza"];
	} else {
		$idraza = "";
	}

	if (strlen($accion) == 0) {
		vmostrarstartpage();
	} elseif ($accion == "listado") {
		switch ($id) {
			case 1 :
				vmostrardatos(mcargaranimales(),mmontarmenu());
				break;
			default:
				vmostrarpaginaanimales();
				break;

		}	
	}



?>