<?php
	
	function conexionbasedatos() {
		$dblink = mysqli_connect("localhost", "root", "", "db_grupo35");
		return $dblink;
	}

	function mmontarmenu() {
		$con = conexionbasedatos();

		$consulta = "select * from razas";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	function mcargarrazas() {
		$con = conexionbasedatos();

		$consulta = "select * from razas";

		if ($resultado = $con->query($consulta)) {
				return $resultado;
			} else {
				return -1;
			}
	}

	function mcargaranimales() {
		$con = conexionbasedatos();

		if (isset($_GET["idraza"])) {
			$idraza = $_GET["idraza"];
		} elseif (isset($_POST["idraza"])) {
			$idraza = $_POST["idraza"];
		} else {
			$idraza = "";
		}

		if (isset($_GET["pagina"])) {
			$pagina = $_GET["pagina"];
		} elseif (isset($_POST["pagina"])) {
			$pagina = $_POST["pagina"];
		} else {
			$pagina = 1;
		}

		$numerototal = 0;
		$res = array();

		if (strlen($idraza) == 0){

			$consulta = "select count(nombre) as cuenta from animales";

			if ($resultado = $con->query($consulta)) {
				$datos = $resultado->fetch_assoc();
				$numerototal = $datos["cuenta"];
			} else {
				$res[0] = -1;
				return $res;
			}

			if ($pagina == 1) {
				$consulta = "select nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza order by nombre limit 3";
			} else {
				$consulta = "select nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza order by nombre limit " . (($pagina - 1) * 3) . ", 3";
			}

		} else {

			$consulta = "select count(nombre) as cuenta from animales where idraza='$idraza'";

			if ($resultado = $con->query($consulta)) {
				$datos = $resultado->fetch_assoc();
				$numerototal = $datos["cuenta"];
			} else {
				$res[0] = -1;
				return $res;
			}

			if ($pagina == 1) {
				$consulta = "select nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza where animales.idraza='$idraza' order by nombre limit 3";
			} else {
				$consulta = "select nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza where animales.idraza='$idraza' order by nombre limit " . (($pagina - 1) * 3) . ", 3";
			}
		}

		if ($resultado = $con->query($consulta)) {
			$res[0] = $numerototal; // número total de páginas
			$res[1] = $resultado; // contenido de la consulta
			$res[2] = $idraza; // código de la raza
 			return $res;
		} else {
			$res[0] = -1;
			return $res;
		}

	}



?>