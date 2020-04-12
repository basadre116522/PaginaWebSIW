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

	if ($accion == "altaraza") {
		switch ($id) {
			case '1':
				// muestra contenido de "altaraza.html" y monta el menú
				vmostraraltaraza(); 
				break;
			case '2':
				// malmacenarraza(): recoge la info de la raza, comprueba si ya existe en la base de datos, y si no está, la almacena
				// vmostrarresultadoaltaraza($resultado): devuelve el resultado obtenido (si se ha efectuado el alta correctamente o no, o si ya existía)
				vmostrarresultadoaltaraza(malmacenarraza()); 
				break;
		}
	}

	if ($accion == "bymraza") {
		switch ($id) {
			case '1': 
				// mlistadoraza(): a través de una query, consulta el listado de razas almacenadas en la base de datos
				// vmostrarlistadobymrazas(): recoge contenido de "bymrazas.html", monta el menú, trocea las filas, se realiza proceso de montaje
				vmostrarlistadobymrazas(mlistadorazas());
				break;
			case '2': 
				vmostrarmodificarraza(mdatosraza());
				break;
			case '3': 
				vresultadomodificarraza(mmodificarraza());
				break;
			case '4': 
				vmostrareliminarraza(mdatosraza());
				break;
			case '5': 
				vmostrarresultadoeliminarraza(mvalidareliminarraza());
				break;
		}
	}

	if ($accion == "altaanimal") {
		switch ($id) {
			case '1': 
				vmostraraltaanimal(mlistadorazas());
				break;
			case '2': 
				vmostrarresultadoaltaanimal(mvalidaraltaanimal());
				break;
		}
	}

	if ($accion == "listadoanimal") {
		switch ($id) {
			case '1': 
				vmostrarlistadoanimales(mlistadoanimales());
				break;
		}
	}

	if ($accion == "bymanimal") {
		switch ($id) {
			case '1': 
				vmostrarlistadoanimalesbym(mlistadoanimales());
				break;
			case '2': 
				vmostrarmodificaranimal(mdatosanimal(), mlistadorazas());
				break;
			case '3': 
				vmostrarresultadomodificaranimal(mvalidarmodificaranimal());
				break;
			case '4': 
				vmostrareliminaranimal(mdatosanimalconraza());
				break;
			case '5': 
				vmostrarresultadoeliminaranimal(meliminaranimal());
				break;
		}		
	}
	

?>
