<?php

	session_start();

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

	//Comprobamos que acción está definida
	if (!isset($accion)) {
		$accion = "login";
		$id = 1;
	}	

	if ($accion == "login") {
		switch ($id) {
			case '1':
				vmostrarlogin();
				break;
			case '2':
				vmostrarresultadologin(mvalidarlogin());
				break;
		}
	} else {
		$valor = mcomprobarusuariosesion();
		if ($valor == 1) {
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
					case '6':
						vmostrarresultadoexportjsonraza(mexportjsonraza());
					break;
				}
			}

			if ($accion == "csvraza") {
				switch ($id) {
					case '1': 
						vmostrarcsvraza();
						break;
					case '2': 
						vmostrarresultadocsvraza(mleercsvraza());
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
					case '6':
						vmostrarresultadoexportjsonanimal(mexportjsonanimal());
					break;
					case '7':
						vmostrarsubirimagenesdropzone();
					break;
				}		
			}

			if ($accion == "csvanimal") {
				switch ($id) {
					case '1': 
						vmostrarcsvanimal();
						break;
					case '2': 
						vmostrarresultadocsvanimal(mleercsvanimal());
						break;
				}
			}

			if ($accion == "altapost") {
				switch ($id) {
					case '1': 
						vmostraraltapost();
						break;
					case '2': 
						vmostrarresultadoaltapost(mvalidaraltapost());
						break;
				}
			}

			if ($accion == "bympost") {
				switch ($id) {
					case '1': 
						vmostrarlistadopostsbym(mlistadoposts());
						break;
					case '2': 
						vmostrarmodificarpost(mdatospost());
						break;
					case '3': 
						vmostrarresultadomodificarpost(mvalidarmodificarpost());
						break;
					case '4': 
						vmostrareliminarpost(mdatospost());
						break;
					case '5': 
						vmostrarresultadoeliminarpost(meliminarpost());
						break;
					case '6':
						vmostrarresultadoexportjsonpost(mexportjsonpost());
					break;
				}
			}

			if ($accion == "bajacomentario") {
				switch ($id) {
					case '1': 
						vmostrarlistadocomentariosbaja(mlistadocomentarios());
						break;
					case '2': 
						vmostrareliminarcomentario(mdatoscomentario());
						break;
					case '3': 
						vmostrarresultadoeliminarcomentario(meliminarcomentario());
						break;
					case '4':
						vmostrarresultadoexportjsoncomentario(mexportjsoncomentario());
					break;
				}
			}

			if ($accion == "listadomensajesrecibidos") {
				switch ($id) {
					case '1':
						vmostrarlistadomensajesrecibidos(mlistadomensajesrecibidos());
						break;
				}
			}

			if ($accion == "listadomensajesenviados") {
				switch ($id) {
					case '1':
						vmostrarlistadomensajesenviados(mlistadomensajesenviados());
						break;
				}
			}
			if ($accion == "redactarmensaje") {
				switch ($id) {
					case '1':
						vmostrarredactarmensaje();
						break;
					case '2':
						vmostrarresultadoredactarmensaje(malmacenarmensaje()); 
						break;
				}
			}
			if ($accion == "mostrarmensaje") {
				switch ($id) {
					case '1':
						vmostrarmensajerecibido(mdatosmensaje());
						break;
					case '2':
						vmostrarmensajeenviado(mdatosmensaje());
					break;
				}
			}
		} else {
			header("Location: index.php?accion=login&id=1");
		}
	}
	

?>
