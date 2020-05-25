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
			$botones = file_get_contents("../html/botonessalir.html");
			$cabecera = str_replace("##botones##", $botones, $cabecera);
		} else {
			$botones = file_get_contents("../html/botonesentrar.html");
			$cabecera = str_replace("##botones##", $botones, $cabecera);
		}
		/*$trozos = explode("##fila##", $cabecera);

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
				$imagenes = mlistadoimagenesconlink($datos["idanimal"]);
				$aux = str_replace("##imagen##", $imagenes, $aux);

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




?>