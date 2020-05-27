<?php
	require_once './dompdf/autoload.inc.php';
	use Dompdf\Dompdf;

	function vmostrarstartpage($listadorazas) {
		$cadena = file_get_contents("../html/startpage.html");
		echo vmontarcabecera($cadena, $listadorazas);
	}

	function vmostrarpaginaanimales($listadorazas) {
		$cadena = file_get_contents("../html/animales.html");
		echo vmontarcabecera($cadena, $listadorazas);
	}

	function vmontarcabecera($cadena/*, $listadorazas*/) {
		$cabecera = file_get_contents("../html/cabecera.html");
		if (isset($_SESSION["usuario"]) and isset($_SESSION["password"])) {
			$botones = file_get_contents("../html/botonesusuarioregistrado.html");
			$cabecera = str_replace("##botones##", $botones, $cabecera);
		} else {
			$botones = file_get_contents("../html/botonesentrar.html");
			$cabecera = str_replace("##botones##", $botones, $cabecera);
		}
		/*$trozos = explode("##fila", $cabecera);

		$aux = "";
		$cuerpo = "";
		while ($datos = $listadorazas->fetch_assoc()) {
			$aux = $trozos[1];
			$aux = str_replace("##idraza##", $datos["idraza"], $aux);
			$aux = str_replace("##raza##", $datos["raza"], $aux);
			$cuerpo .= $aux;
		}

		$cabecera = $trozos[0] . $cuerpo . $trozos[2];*/
		$cadena = str_replace("##cabecera##", $cabecera, $cadena);
		return $cadena;
	}

	function vmontarmenu($resultado) {
		$menu = file_get_contents("../html/menu_animales.html");
		$trozos = explode("##fila##", $menu);

		$aux = "";
		$cuerpo = "";
		while ($datos = $resultado->fetch_assoc()) {
			$aux = $trozos[1];
			$aux = str_replace("##idraza##", $datos["idraza"], $aux);
			$aux = str_replace("##raza##", $datos["raza"], $aux);
			$cuerpo .= $aux;
		}

		return $trozos[0] . $cuerpo . $trozos[2];

	}


	function vmontarmenumensajes($cadena) {
		$menu = file_get_contents("../html/menumensajes.html");
		$cadena = str_replace("##menu##", $menu, $cadena);
		return $cadena;
	}
	
	function vmostrarmensaje($titulo, $subtitulo, $texto) {
		$cadena = file_get_contents("../html/mensaje.html");
		$cadena = vmontarcabecera($cadena);
		$cadena = str_replace("##titulo##", $titulo, $cadena);
		$cadena = str_replace("##subtitulo##", $subtitulo, $cadena);
		$cadena = str_replace("##texto##", $texto, $cadena);
		echo $cadena;
	}

	function vmostrarlogin() {
		echo file_get_contents("../html/login.html");
	}

	function vmostrarresultadologin($resultado) {
		// si da error hacer que te mande nuevo al login directamnete 
		//$cadena = file_get_contents("../html/login.html");
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Login", "Login de usuario", "El login se ha realizado corretamente.");
				break;
			case '-1' :
				vmostrarmensaje("Login", "Login de usuario", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1208");
				break; 
			case '-2' :
				vmostrarmensaje("Login", "Login de usuario", "El usuario no existe.");
				break; 
			case '-1' :
				vmostrarmensaje("Login", "Login de usuario", "La contraseña es incorrecta.");
				break; 
		}	
		
	}

	function vmostrarresultadologout($resultado) {
		//$cadena = file_get_contents("../html/logout.html");
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Logout", "Logout de usuario", "Se ha cerrado la sesión corretamente.");
				break;
			case '-1' :
				vmostrarmensaje("Login", "Login de usuario", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1208");
				break; 
			case '-2' :
				vmostrarmensaje("Login", "Login de usuario", "El usuario no existe.");
				break; 
			case '-1' :
				vmostrarmensaje("Login", "Login de usuario", "La contraseña es incorrecta.");
				break; 
		}	
		
	}

	function vmostrarsignup() {
		echo file_get_contents("../html/signup.html");
	}

	function vmostrarresultadosignup($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Registro de Usuario", "Registrar", "Se ha registrado el usuario correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Registro de Usuario", "Registrar", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1111");
				break; 
			case '-2' :
				vmostrarmensaje("Registro de Usuario", "Registrar", "Ya existe un usuario con ese nombre.");
				break; 
			case '-2' :
				vmostrarmensaje("Registro de Usuario", "Registrar", "Las contraseñas no coinciden.");
				break; 
		}
	}

	function vmostrardatos($resultado, $resultado2){
		if ($resultado[0] >= 0) {
			//Nos centramos en la paginas
			$numpaginas = intdiv($resultado[0],3);
			$resto = $resultado[0] % 3;

			if ($resto > 0) {
				$numpaginas ++;
			}

			$paginacionprimera = file_get_contents("../html/paginacion_primera.html");
			$paginacionsiguientes = file_get_contents("../html/paginacion_siguientes.html");
			$paginacionsiguientes = str_replace("##idraza##", $resultado[2], $paginacionsiguientes);

			$paginacion = str_replace("##idraza##", $resultado[2], $paginacionprimera);
			for ($i = 2; $i <= $numpaginas; $i++) {
				$paginacion .= str_replace("##numero##", $i, $paginacionsiguientes);
			}

			// Nos centramos en la tabla de palabras
			$cadena = file_get_contents("../html/tabladatos.html");
			$trozos = explode("##fila##", $cadena);

			$aux = "";
			$cuerpo = "";
			while ($datos = $resultado[1]->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##nombre##", $datos["nombre"], $aux);
				$aux = str_replace("##raza##", $datos["raza"], $aux);
				$aux = str_replace("##edad##", $datos["edad"], $aux);
				$aux = str_replace("##genero##", $datos["genero"], $aux);
				$aux = str_replace("##fechaentrada##", $datos["fechaentrada"], $aux);
				$aux = str_replace("##descripcion##", $datos["descripcion"], $aux);
				$aux = str_replace("##idanimal##", $datos["idanimal"], $aux);
				$imagen = mseleccionarimagen($datos["idanimal"]);
				$aux = str_replace("##imagen##", $imagen, $aux);

				$cuerpo .= $aux;
			}

			$cadena = $trozos[0] . $cuerpo . $trozos[2];
			

			$completo = array();
			$completo[0] = $paginacion;
			$completo[1] = $cadena;
			$completo[2] = vmontarmenu($resultado2);

			echo json_encode($completo);

		} else {
			echo "Error en la consulta.";
		}
	}

	function vcargarpdf($resultado) {
		$contenido = "<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>PDF</title></head><body><table><thead><th>Nombre</th><th>Raza</th><th>Edad</th><th>Género</th><th>FechaEntrada</th><th>Descripcion</th></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		while ($datos = $resultado->fetch_assoc()) {
			$contenido .= "<tr><td>".$datos["nombre"]."</td><td>".$datos["raza"]."</td><td>".$datos["edad"]."</td><td>".$datos["genero"]."</td><td>".$datos["fechaentrada"]."</td><td>".$datos["descripcion"]."</td></tr>";
		}
		$contenido .= "</tbody></table></body></html>";
		$dompdf = new Dompdf();
		$dompdf->loadHtml($contenido);
		$dompdf->render();
		$pdf = $dompdf->output();
		$dompdf-> stream();
	}

	function vmostraranimal($resultadoImagenes,$resultadoAnimal) {
		$cadena = file_get_contents("../html/mostraranimal.html");
		$cadena = vmontarcabecera($cadena);

		$numFotos = 0;
		while ($datos = $resultadoAnimal->fetch_assoc()) {
			$cadena = str_replace("##nombre##", $datos["nombre"], $cadena);
			$cadena = str_replace("##edad##", $datos["edad"], $cadena);
			$cadena = str_replace("##genero##", $datos["genero"], $cadena);
			$cadena = str_replace("##fechaentrada##", $datos["fechaentrada"], $cadena);
			$cadena = str_replace("##descripcion##", $datos["descripcion"], $cadena);
			$cadena = str_replace("##raza##", $datos["raza"], $cadena);
			$cadena = str_replace("##idanimal##", $datos["idanimal"], $cadena);
			$imagen = mseleccionarimagen($datos["idanimal"]);
			$cadena = str_replace("##imagenes##", $imagen, $cadena);
		}

		while ($imagenes = $resultadoImagenes->fetch_assoc()) {
			$numFotos = $numFotos + 1;
			$imagen = $imagenes["imagen"];
			$dir = "../../backend/admin/imagenes";
			$nomOriginal = $dir. "/". $imagen;
			$tipo = strstr($nomOriginal, ".");
			if ($tipo == ".png") {
				
				$nomOriginalSinTipo = str_replace(".png", "", $nomOriginal);
				$tipo = ".png";
			} else {
				$nomOriginalSinTipo = str_replace(".jpeg", "", $nomOriginal);
				$tipo = ".jpeg";
			}
			if ($numFotos == 1) {
				/* La primera foto de la galería va a tener tamaño mediano */
				$cadena = str_replace("##IMAGEN$numFotos##", $nomOriginalSinTipo . "_thumbM" . $tipo, $cadena);
			}
			if (($numFotos > 1) && ($numFotos < 7)) {
				/* El resto de fotos van a tener tamaño mediano */
				$cadena = str_replace("##IMAGEN$numFotos##", $nomOriginalSinTipo . "_thumbP" . $tipo, $cadena);
				$cadena = str_replace("##reemplazo$numFotos##", "onclick='replaceP()'", $cadena);
			}
		}
		while ($numFotos < 6) {
			$numFotos = $numFotos + 1;
			if ($numFotos == 1) {
				/* La primera foto de la galería va a tener tamaño mediano */
				$cadena = str_replace("##IMAGEN$numFotos##", "imagenes/image-not-found_thumbM.png", $cadena);
			}
			if (($numFotos > 1) && ($numFotos < 7)) {
				/* El resto de fotos van a tener tamaño mediano */
				$cadena = str_replace("##IMAGEN$numFotos##", "imagenes/image-not-found_thumbP.png", $cadena);
				$cadena = str_replace("##reemplazo$numFotos##", "", $cadena);
			}
		}
		if ($numFotos == 0) {
			$cadena = str_replace("##MENSAJEVACIA##", "No hay contenido que mostrar", $cadena);
		} else {
			$cadena = str_replace("##MENSAJEVACIA##", "", $cadena);
		}
		echo $cadena;
	}


	function vmostrarredactarmensaje() {
		$cadena = file_get_contents("../html/redactarmensaje.html");
		$cadena = vmontarcabecera($cadena);
		$cadena = vmontarmenumensajes($cadena);
		if (isset($_GET["idanimal"])){
			$cadena = str_replace("##idanimal##", $_GET["idanimal"], $cadena);
		} else {
			$cadena = str_replace("##idanimal##", "", $cadena);

		}
		echo $cadena;		
	}

	/******************************
	Función encargada de mostrar resultado redactar mensaje
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadoredactarmensaje($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Mensajes", "Redactar mensaje", "Se ha enviado el mensaje correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Mensajes", "Redactar mensaje", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1223");
				break;
		}
	}

	function vmostrarmensajeenviado($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/mostrarmensaje.html");
			$cadena = vmontarcabecera($cadena);
			$cadena = vmontarmenumensajes($cadena);
			
			while ($datos = $resultado->fetch_assoc()) {
				$cadena = str_replace("##asunto##", $datos["asunto"], $cadena);
				$cadena = str_replace("##corresponsal##", $_SESSION["usuario"], $cadena);
				$cadena = str_replace("##fecha##", $datos["fecha"], $cadena);
				$cadena = str_replace("##hora##", $datos["hora"], $cadena);
				$cadena = str_replace("##destinatario##", $datos["usuario"], $cadena);
				$cadena = str_replace("##mensaje##", $datos["mensaje"], $cadena);
				$cadena = str_replace("##animal##", $datos["nombre"], $cadena);
				$cadena = str_replace("##idanimal##", $datos["idanimal"], $cadena);

			}

			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1112");
			}
		}
	}

	function vmostrarmensajerecibido($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/mostrarmensaje.html");
			$cadena = vmontarcabecera($cadena);
			$cadena = vmontarmenumensajes($cadena);
			
			while ($datos = $resultado->fetch_assoc()) {
				$cadena = str_replace("##asunto##", $datos["asunto"], $cadena);
				$cadena = str_replace("##corresponsal##", $datos["usuario"], $cadena);
				$cadena = str_replace("##fecha##", $datos["fecha"], $cadena);
				$cadena = str_replace("##hora##", $datos["hora"], $cadena);
				$cadena = str_replace("##destinatario##", $_SESSION["usuario"], $cadena);
				$cadena = str_replace("##mensaje##", $datos["mensaje"], $cadena);
			}

			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1112");
			}
		}
	}

	function vmostrarlistadomensajesenviados($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/listadomensajesenviados.html");
			$cadena = vmontarcabecera($cadena);
			$cadena = vmontarmenumensajes($cadena);

			$trozos = explode("##fila##", $cadena);
			$cuerpo = "";
			$aux = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##usuario##", $datos["usuario"], $aux);
				$aux = str_replace("##asunto##", $datos["asunto"], $aux);
				$aux = str_replace("##idmensaje##", $datos["idmensaje"], $aux);
				$aux = str_replace("##fecha##", $datos["fecha"], $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1112");
			}
		}
	}

	function vmostrarlistadomensajesrecibidos($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/listadomensajesrecibidos.html");
			$cadena = vmontarcabecera($cadena);
			$cadena = vmontarmenumensajes($cadena);
			
			$trozos = explode("##fila##", $cadena);
			$cuerpo = "";
			$aux = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##usuario##", $datos["usuario"], $aux);
				$aux = str_replace("##asunto##", $datos["asunto"], $aux);
				$aux = str_replace("##idmensaje##", $datos["idmensaje"], $aux);
				$aux = str_replace("##fecha##", $datos["fecha"], $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1112");
			}
		}
	}

	function vmostrardatosbusqueda($resultado) {
		if ($resultado[0] >= 0) {
			//Nos centramos en la paginas
			$numpaginas = intdiv($resultado[0],3);
			$resto = $resultado[0] % 3;

			if ($resto > 0) {
				$numpaginas ++;
			}

			$paginacionprimera = file_get_contents("../html/paginacion_primera_buscar.html");
			$paginacionsiguientes = file_get_contents("../html/paginacion_siguientes_buscar.html");

			$paginacion = $paginacionprimera;
			for ($i = 2; $i <= $numpaginas; $i++) {
				$paginacion .= str_replace("##numero##", $i, $paginacionsiguientes);
			}

			// Nos centramos en la tabla de animales
			$cadena = file_get_contents("../html/tabladatos.html");
			$trozos = explode("##fila##", $cadena);

			$aux = "";
			$cuerpo = "";
			while ($datos = $resultado[1]->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##nombre##", $datos["nombre"], $aux);
				$aux = str_replace("##raza##", $datos["raza"], $aux);
				$aux = str_replace("##edad##", $datos["edad"], $aux);
				$aux = str_replace("##genero##", $datos["genero"], $aux);
				$aux = str_replace("##fechaentrada##", $datos["fechaentrada"], $aux);
				$aux = str_replace("##descripcion##", $datos["descripcion"], $aux);
				$aux = str_replace("##idanimal##", $datos["idanimal"], $aux);
				$imagen = mseleccionarimagen($datos["idanimal"]);
				$aux = str_replace("##imagen##", $imagen, $aux);

				$cuerpo .= $aux;
			}

			$cadena = $trozos[0] . $cuerpo . $trozos[2];
			

			$completo = array();
			$completo[0] = $paginacion;
			$completo[1] = $cadena;

			echo json_encode($completo);

		} else {
			if ($resultado == -2) {
				echo "No se han encontrado coincidencias con sus criterios de búsqueda.";
			} else {
				echo "Error consulta.";
			}
		}

	}

	function vmostrarfaqs(){
		$cadena = file_get_contents("../html/faqs.html");
		$cadena = vmontarcabecera($cadena);
		echo $cadena;
	}
	
?>