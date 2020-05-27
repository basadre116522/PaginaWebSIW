<?php

	session_start();

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
	} elseif (isset($_POST["idraza"])) {
		$idraza = $_POST["idraza"];
	} else {
		$idraza = "";
	}

	if (strlen($accion) == 0) {
		vmostrarstartpage(mcargarrazas());
	} elseif ($accion == "login") {
		switch ($id) {
			case '1':
				vmostrarlogin();
				break;
			case '2':
				vmostrarresultadologin(mvalidarlogin());
				break;
		}
	} elseif ($accion == "logout") {
		switch ($id) {
			case '1':
				vmostrarresultadologout(mvalidarlogout());
				break;
		}
	} elseif ($accion == "signup") {
		switch ($id) {
			case '1':
				vmostrarsignup();
				break;
			case '2':
				vmostrarresultadosignup(mvalidarsignup());
				break;
		}
	} elseif ($accion == "listado") {
		switch ($id) {
			case 1 :
				vmostrardatosanimales(mcargaranimales(),mmontarmenu());
				break;
			case 2 :
				vmostrardatosbusqueda(mdatosbusqueda());
				break;
			default:
				vmostrarpaginaanimales(mcargarrazas());
				break;

		}	
	} elseif ($accion == "exportarpdf") {
		switch ($id) {
			case 1 :
				vcargarpdf(mlistadoanimales());
				break;
		}	
	} elseif ($accion == "mostraranimal") {
		switch ($id) {
			case 1 :
				vmostraranimal(mgetimagenesanimal(),mdatosanimal());
				break;
		}	
	} elseif ($accion == "redactarmensaje") {
		$valor = mcomprobarusuariosesion();
		if ($valor == 1) {
			switch ($id) {
				case 1 :
					vmostrarredactarmensaje();
					break;
				case 2:
					vmostrarresultadoredactarmensaje(malmacenarmensaje());
					break;
			}	
		} else {
			header("Location: index.php?accion=login&id=1");
		}
	} elseif ($accion == "mostrarmensaje") {
		$valor = mcomprobarusuariosesion();
		if ($valor == 1) {
			switch ($id) {
				case '1':
					vmostrarmensajerecibido(mdatosmensaje());
					break;
				case '2':
					vmostrarmensajeenviado(mdatosmensaje());
					break;
			}
		} else {
			header("Location: index.php?accion=login&id=1");
		}
	} elseif ($accion == "listadomensajesenviados") {
		$valor = mcomprobarusuariosesion();
		if ($valor == 1) {
			switch ($id) {
				case '1':
					vmostrarlistadomensajesenviados(mlistadomensajesenviados());
					break;
			}
		} else {
			header("Location: index.php?accion=login&id=1");
		}
	} elseif ($accion == "listadomensajesrecibidos") {
		$valor = mcomprobarusuariosesion();
		if ($valor == 1) {
			switch ($id) {
				case '1':
					vmostrarlistadomensajesrecibidos(mlistadomensajesrecibidos());
					break;
			}
		} else {
			header("Location: index.php?accion=login&id=1");
		}
	} elseif ($accion == "faqs"){
		switch ($id) {
			case '1':
				vmostrarfaqs();
				break;
		}
	} elseif ($accion == "blog") {
		switch ($id) {
			case 1 :
				vmostrardatosblog(mcargarposts());
				break;
			default:
				vmostrarblog();
				break;
		}
	} elseif ($accion == "mostrarpost") {
		switch ($id) {
			case 1 :
				vmostrarpost(mcargarcomentarios(), mdatospost());
				break;
		}	
	} elseif ($accion == "comentar") {
		$valor = mcomprobarusuariosesion();
		if ($valor == 1) {
			switch ($id) {
				case '1':
					vmostrarresultadocomentario(malmacenarcomentario());
					break;
			}
		} else {
			header("Location: index.php?accion=login&id=1");
		}
	}



?>
