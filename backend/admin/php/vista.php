<?php
	
	function vmostrarmensaje($titulo, $subtitulo, $texto) {
		$cadena = file_get_contents("../html/mensaje.html");
		$cadena = vmontarmenu($cadena);
		$cadena = str_replace("##titulo##", $titulo, $cadena);
		$cadena = str_replace("##subtitulo##", $subtitulo, $cadena);
		$cadena = str_replace("##texto##", $texto, $cadena);
		echo $cadena;
	}


	function vmontarmenu($cadena) {
		$menu = file_get_contents("../html/menu.html");
		$cadena = str_replace("##menu##", $menu, $cadena);
		$botones = file_get_contents("../html/botonsalir.html");
		$cadena = str_replace("##botones##", $botones, $cadena);
		return $cadena;
	}

	function vcargarinicio() {
		$cadena = file_get_contents("../html/starter.html");
		$cadena = vmontarmenu($cadena);
		echo $cadena;
	}

	function vmostrarlogin() {
		$cadena = file_get_contents("../html/login.html");
		echo $cadena;
	}
	
	function vmostrarresultadologin($resultado) {
		// si da error hacer que te mande nuevo al login directamnete
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Login", "Login de usuario", "El login se ha realizado corretamente.");
				break;
			case '-1' :
				$cadena = file_get_contents("../html/login.html");
				echo $cadena;
				echo "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1208";
				break; 
			case '-2' :
				$cadena = file_get_contents("../html/login.html");
				echo $cadena;
				echo "El usuario no existe.";
				break; 
			case '-3' :
				$cadena = file_get_contents("../html/login.html");
				echo $cadena;
				echo "La contraseña es incorrecta.";
				break; 
		}	
		
	}

	function vmostraraltaraza() {
		$cadena = file_get_contents("../html/altaraza.html");
		$cadena = vmontarmenu($cadena);
		echo $cadena;		
	}

	/******************************
	Función encargada de mostrar resultado alta raza
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
		-2 --> Si la raza ya existe
	*******************************/
	function vmostrarresultadoaltaraza($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha dado de alta la raza correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1111");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Ya existe una raza con ese nombre.");
				break; 
		}
	}


	function vmostrarlistadobymrazas($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/bymraza.html");
			$cadena = vmontarmenu($cadena);
			
			$trozos = explode("##fila##", $cadena);
			$cuerpo = "";
			$aux = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##raza##", $datos["raza"], $aux);
				$aux = str_replace("##idraza##", $datos["idraza"], $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Alta de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1112");
			}
		}
	}

	/******************************
	Función encargada de mostrar modificar una raza
	Recibe
		Datos de una raza
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarmodificarraza($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/modificarraza.html");
			$cadena = vmontarmenu($cadena);
			
			$datos = $resultado -> fetch_assoc();
			$cadena = str_replace("##raza##", $datos["raza"], $cadena);
			$cadena = str_replace("##idraza##", $datos["idraza"], $cadena);

			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Modificar raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1113");
			}
		}
	}

	/******************************
	Función encargada de mostrar mensaje de modificar una raza
	Recibe
		Datos de una raza
		 1 --> Si se ha realizado la modificación correctamente
		-1 --> Si hay un problema con la base de datos
		-2 --> Ya existe una raza con ese nombre
	*******************************/
	function vresultadomodificarraza($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de razas", "Modificar de raza", "Se ha modificado la raza correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de razas", "Modificar de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1114");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de razas", "Modificar de raza", "El nuevo nombre de raza introducido ya existe en la base de datos. Vuelva a intentarlo. Error: -1115");
				break; 
		}
	}

	/******************************
	Función encargada de mostrar eliminar una raza
	Recibe
		Datos de una raza
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrareliminarraza($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/eliminarraza.html");
			$cadena = vmontarmenu($cadena);
			
			$datos = $resultado -> fetch_assoc();
			$cadena = str_replace("##raza##", $datos["raza"], $cadena);
			$cadena = str_replace("##idraza##", $datos["idraza"], $cadena);

			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de razas", "Eliminar raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1116");
			}
		}
	}


	function vmostrarresultadoeliminarraza($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de razas", "Eliminar de raza", "Se ha eliminado la raza correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de razas", "Eliminar de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1117");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de razas", "Eliminar de raza", "Esta raza tiene animales asociadas, no se puede eliminar. Vuelva a intentarlo. Error: -1118");
				break; 
		}
	}


	function vmostraraltaanimal($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/altaanimal.html");
			$cadena = vmontarmenu($cadena);

			$trozos = explode("##fila##", $cadena);

			$aux = "";
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##idraza##", $datos["idraza"], $aux);
				$aux = str_replace("##raza##", $datos["raza"], $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];	
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de animales", "Alta de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1119");

			}
		}

	}


	function vmostrarresultadoaltaanimal($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de animales", "Alta de animal", "Se ha dado de alta la animal correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de animales", "Alta de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1120");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de animales", "Alta de animal", "Se debe elegir una raza.");
				break; 
		}		
	}

	function vmostrarlistadoanimalesbym($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/bymanimal.html");
			$cadena = vmontarmenu($cadena);
			
			$trozos = explode("##fila##", $cadena);
			$cuerpo = "";
			$aux = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##idanimal##", $datos["idanimal"], $aux);
				$aux = str_replace("##nombre##", $datos["nombre"], $aux);
				$aux = str_replace("##edad##", $datos["edad"], $aux);
				$aux = str_replace("##genero##", $datos["genero"], $aux);
				$aux = str_replace("##fechaentrada##", $datos["fechaentrada"], $aux);
				$aux = str_replace("##descripcion##", $datos["descripcion"], $aux);
				$aux = str_replace("##raza##", $datos["raza"], $aux);
				$imagenes_array = mlistadoimagenes($datos["idanimal"]);
				$i = 0;
				$dir= '../imagenes';
				foreach($imagenes_array as $img){
					$target_path = $dir.'/'.$img;
					$imagenes_array[$i] = "<a href='".$target_path."'>".$img."</a>";
					$i++;
				}
				$imagenes = implode(" ", $imagenes_array);
				$aux = str_replace("##imagenes##", $imagenes, $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de animales", "Baja y modificación de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1121");
			}
		}

	}

	function vmostrarmodificaranimal($resultado, $listadorazas) {
		if (!is_object($listadorazas)) {
			if ($listadorazas == -1) {
				vmostrarmensaje("Gestión de animales", "Baja y modificación de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1122");
				
			}
		}
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/modificaranimal.html");
			$cadena = vmontarmenu($cadena);

			$datos = $resultado->fetch_assoc();
			$cadena = str_replace("##nombre##", $datos["nombre"], $cadena);
			$cadena = str_replace("##edad##", $datos["edad"], $cadena);
			if ($datos["genero"] == "macho") {
				$cadena = str_replace("##macho##", "checked", $cadena);
				$cadena = str_replace("##hembra##", "", $cadena);
				$cadena = str_replace("##otro##", "", $cadena);
			} else if ($datos["genero"] == "hembra") {
				$cadena = str_replace("##macho##", "", $cadena);
				$cadena = str_replace("##hembra##", "checked", $cadena);
				$cadena = str_replace("##otro##", "", $cadena);
			} else {
				$cadena = str_replace("##macho##", "", $cadena);
				$cadena = str_replace("##hembra##", "", $cadena);
				$cadena = str_replace("##otro##", "checked", $cadena);
			}
			$cadena = str_replace("##fechaentrada##", $datos["fechaentrada"], $cadena);
			$cadena = str_replace("##descripcion##", $datos["descripcion"], $cadena);
			$cadena = str_replace("##idanimal##", $datos["idanimal"], $cadena);
			$idanimal = $datos["idanimal"];
			$idraza = $datos["idraza"];

			$trozos = explode("##fila##", $cadena);
			$aux ="";
			$cuerpo ="";
			while ($datos = $listadorazas->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##idraza##", $datos["idraza"], $aux);	
				$aux = str_replace("##raza##", $datos["raza"], $aux);

				if ($idraza == $datos["idraza"]) {
					$aux = str_replace("##seleccionada##", "selected", $aux);
				} else {
					$aux = str_replace("##seleccionada##", "", $aux);					
				}
	
				$cuerpo .= $aux;
			}
			$cadena = $trozos[0] . $cuerpo . $trozos[2];

			$imagenes = mlistadoimagenes($idanimal);
			$cuerpo = "";
			$aux = "";
			$trozos = explode("##fila2##", $cadena);
			foreach($imagenes as $img){
				$aux = $trozos[1];
				$aux = str_replace("##imagen##", $img, $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
			
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de animales", "Baja y modificación de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1123");
			}
		}

	}


	function vmostrarresultadomodificaranimal($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de animales", "Modificar de animal", "Se ha modificado el animal correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de animales", "Modificar de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1124");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de animales", "Modificar de animal", "Se debe elegir una raza .Vuelva a intentarlo. Error: -1125");
				break; 
		}
		
	}

	function vmostrareliminaranimal($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/eliminaranimal.html");
			$cadena = vmontarmenu($cadena);

			$datos = $resultado->fetch_assoc();
			$cadena = str_replace("##nombre##", $datos["nombre"], $cadena);
			$cadena = str_replace("##edad##", $datos["edad"], $cadena);
			$cadena = str_replace("##genero##", $datos["genero"], $cadena);
			$cadena = str_replace("##fechaentrada##", $datos["fechaentrada"], $cadena);
			$cadena = str_replace("##descripcion##", $datos["descripcion"], $cadena);
			$cadena = str_replace("##raza##", $datos["raza"], $cadena);
			$cadena = str_replace("##idanimal##", $datos["idanimal"], $cadena);

			echo $cadena;
			
		} else {
				vmostrarmensaje("Gestión de animales", "Eliminar de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1126");
		}
	}

	function vmostrarresultadoeliminaranimal($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de animales", "Eliminar de animal", "Se ha eliminado el animal correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de animales", "Eliminar de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1127");
				break; 
		}		
	}

	function vmostraraltapost() {
		$cadena = file_get_contents("../html/altapost.html");
		$cadena = vmontarmenu($cadena);
		echo $cadena;		
	}

	/******************************
	Función encargada de mostrar resultado alta post
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
		-2 --> Si un post con el mismo título ya existe
	*******************************/
	function vmostrarresultadoaltapost($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de posts", "Alta de post", "Se ha dado de alta el post correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de posts", "Alta de post", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1111");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de posts", "Alta de post", "Ya existe un post con ese título.");
				break; 
			case '-3' :
				vmostrarmensaje("Gestión de posts", "Alta de post", "Ya existe un post con ese contenido.");
				break; 
		}
	}


	function vmostrarlistadopostsbym($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/bympost.html");
			$cadena = vmontarmenu($cadena);
			
			$trozos = explode("##fila##", $cadena);
			$cuerpo = "";
			$aux = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##idpost##", $datos["idpost"], $aux);
				$aux = str_replace("##titulo##", $datos["titulo"], $aux);
				$aux = str_replace("##post##", $datos["post"], $aux);
				$aux = str_replace("##idadmin##", $datos["idadmin"], $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de posts", "Baja y modificación de post", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1121");
			}
		}		
	}

	/******************************
	Función encargada de mostrar modificar un post
	Recibe
		Datos de una post
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarmodificarpost($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/modificarpost.html");
			$cadena = vmontarmenu($cadena);
			
			$datos = $resultado -> fetch_assoc();
			$cadena = str_replace("##idpost##", $datos["idpost"], $cadena);
			$cadena = str_replace("##titulo##", $datos["titulo"], $cadena);
			$cadena = str_replace("##post##", $datos["post"], $cadena);
			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de posts", "Modificar post", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1113");
			}
		}
	}


	function vmostrarresultadomodificarpost($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de posts", "Modificar de post", "Se ha modificado el post correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de animales", "Modificar de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1124");
				break; 
			case '-2' :
				vmostrarmensaje("Gestión de posts", "Modificar de post", "Ya existe un post con ese título.");
				break; 
			case '-3' :
				vmostrarmensaje("Gestión de posts", "Modificar de post", "Ya existe un post con ese contenido.");
				break; 
		}

	}


	function vmostrareliminarpost($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/eliminarpost.html");
			$cadena = vmontarmenu($cadena);
			
			$datos = $resultado -> fetch_assoc();
			$cadena = str_replace("##titulo##", $datos["titulo"], $cadena);
			$cadena = str_replace("##post##", $datos["post"], $cadena);
			$cadena = str_replace("##idpost##", $datos["idpost"], $cadena);

			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de posts", "Eliminar post", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1116");
			}
		}

	}


	function vmostrarresultadoeliminarpost($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de posts", "Eliminar post", "Se ha eliminado el post correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de posts", "Eliminar post", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1116");
				break; 
		}
	}


	/******************************
	Función encargada de mostrar si han sido correctamente exportados los datos de posts a json
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadoexportjsonpost($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de posts", "Exportacion a json", "Se ha formado el fichero json correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de posts", "Exportacion a json", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1231");
				break; 
		}
	}


	function vmostrarlistadocomentariosbaja($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/bcomentarios.html");
			$cadena = vmontarmenu($cadena);
			
			$trozos = explode("##fila##", $cadena);
			$cuerpo = "";
			$aux = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##idcomentario##", $datos["idcomentario"], $aux);
				$aux = str_replace("##comentario##", $datos["comentario"], $aux);
				$aux = str_replace("##idpost##", $datos["idpost"], $aux);
				$aux = str_replace("##idusuario##", $datos["idusuario"], $aux);
				$cuerpo .= $aux;
			}

			echo $trozos[0] . $cuerpo . $trozos[2];
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de comentarios", "Baja de comentarios", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1121");
			}
		}

	}


	function vmostrareliminarcomentario($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/eliminarcomentario.html");
			$cadena = vmontarmenu($cadena);
			
			$datos = $resultado -> fetch_assoc();
			$cadena = str_replace("##idcomentario##", $datos["idcomentario"], $cadena);
			$cadena = str_replace("##comentario##", $datos["comentario"], $cadena);
			$cadena = str_replace("##idpost##", $datos["idpost"], $cadena);
			$cadena = str_replace("##idusuario##", $datos["idusuario"], $cadena);

			echo $cadena;
		} else {
			if ($resultado == -1) {
				vmostrarmensaje("Gestión de comentarios", "Eliminar comentario", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1116");
			}
		}

	}


	function vmostrarresultadoeliminarcomentario($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de comentarios", "Eliminar comentario", "Se ha eliminado el comentario correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de comentarios", "Eliminar comentario", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1116");
				break; 
		}
	}


	/******************************
	Función encargada de mostrar si han sido correctamente exportados los datos de comentarios a json
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadoexportjsoncomentario($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de posts", "Exportacion a json", "Se ha formado el fichero json correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de posts", "Exportacion a json", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1231");
				break; 
		}
	}

	
	function vmostrarlistadomensajesenviados($resultado) {
		if (is_object($resultado)) {
			$cadena = file_get_contents("../html/listadomensajesenviados.html");
			$cadena = vmontarmenu($cadena);
			
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
			$cadena = vmontarmenu($cadena);
			
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


	function vmostrarredactarmensaje() {
		$cadena = file_get_contents("../html/redactarmensaje.html");
		$cadena = vmontarmenu($cadena);
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
			$cadena = vmontarmenu($cadena);
			
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
			$cadena = vmontarmenu($cadena);
			
			while ($datos = $resultado->fetch_assoc()) {
				$cadena = str_replace("##asunto##", $datos["asunto"], $cadena);
				$cadena = str_replace("##corresponsal##", $datos["usuario"], $cadena);
				$cadena = str_replace("##fecha##", $datos["fecha"], $cadena);
				$cadena = str_replace("##hora##", $datos["hora"], $cadena);
				$cadena = str_replace("##destinatario##", $_SESSION["usuario"], $cadena);
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


	function vmostrarcsvraza() {
		$cadena = file_get_contents("../html/csvraza.html");
		$cadena = vmontarmenu($cadena);
		echo $cadena;		
	}

	function vmostrarcsvanimal() {
		$cadena = file_get_contents("../html/csvanimal.html");
		$cadena = vmontarmenu($cadena);
		echo $cadena;		
	}


	/******************************
	Función encargada de mostrar resultado alta de csv de razas
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadocsvraza($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de razas", "Alta csv de raza", "Se ha dado de alta las razas correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de razas", "Alta csv de raza", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1223");
				break; 
		}
	}

	/******************************
	Función encargada de mostrar resultado alta de csv de animales
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadocsvanimal($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de animales", "Alta csv de animal", "Se ha dado de alta los animales correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de animal", "Alta csv de animal", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1224");
				break; 
		}
	}


	/******************************
	Función encargada de mostrar si han sido correctamente exportados los datos de animales a json
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadoexportjsonanimal($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de animales", "Exportacion a json", "Se ha formado el fichero json correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de animales", "Exportacion a json", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1230");
				break; 
		}
	}

	/******************************
	Función encargada de mostrar si han sido correctamente exportados los datos de razas a json
	Recibe
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadoexportjsonraza($resultado) {
		switch ($resultado) {
			case '1':
				vmostrarmensaje("Gestión de razas", "Exportacion a json", "Se ha formado el fichero json correctamente.");
				break;
			case '-1' :
				vmostrarmensaje("Gestión de razas", "Exportacion a json", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1231");
				break; 
		}
	}

	/******************************
	Función encargada de mostrar insetar imagenes dropzone
	Recibe
		Datos de una raza
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarsubirimagenesdropzone() {
			$cadena = file_get_contents("../html/subirImagenesdropzone.html");
			$cadena = vmontarmenu($cadena);
			
			$idanimal= $_GET["idanimal"];
			$cadena = str_replace("##idanimal##", $idanimal, $cadena);

			echo $cadena;
	}

	/******************************
	Función encargada de mostrar el resultado del logout
	Recibe
		1 --> el logout ha sido exitoso
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function vmostrarresultadologout($resultado) {
		switch ($resultado) {
			case '1':
				$cadena = file_get_contents("../html/login.html");
				echo $cadena;
				"Se ha cerrado la sesión corretamente.";
				break;
			case '-1' :
				vmostrarmensaje("Logout", "Logout de usuario", "Se ha producido un error. Vuelva a intentarlo pasados unos minutos.<br>Si el problema persiste póngase en contacto con el administrador. Error: -1208");
				break; 
		}	
		
	}
?>
