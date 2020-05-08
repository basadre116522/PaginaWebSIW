<?php

	function vmostrarstartpage($listadorazas) {
		$cadena = file_get_contents("../html/startpage.html");
		echo vmontarcabecera($cadena, $listadorazas);
	}

	function vmostrarpaginaanimales($listadorazas) {
		$cadena = file_get_contents("../html/animales.html");
		echo vmontarcabecera($cadena, $listadorazas);
	}

	function vmontarcabecera($cadena, $listadorazas) {
		$cabecera = file_get_contents("../html/cabecera.html");
		$trozos = explode("##fila##", $cabecera);

		$aux = "";
		$cuerpo = "";
		while ($datos = $listadorazas->fetch_assoc()) {
			$aux = $trozos[1];
			$aux = str_replace("##idraza##", $datos["idraza"], $aux);
			$aux = str_replace("##raza##", $datos["raza"], $aux);
			$cuerpo .= $aux;
		}

		$cabecera = $trozos[0] . $cuerpo . $trozos[2];
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




?>