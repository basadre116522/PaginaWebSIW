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

	function mcargaranimales() {
		$con = conexionbasedatos();

		if (isset($_GET["idraza"])) {
			$idraza = $_GET["idraza"];
		} elseif (isset($_POST["id"])) {
			$idraza = $_POST["idraza"];
		} else {
			$idraza = "";
		}
		$pagina = $_GET["pagina"];

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
			$res[0] = $numerototal;
			$res[1] = $resultado;
			$res[2] = $idraza;
 			return $res;
		} else {
			$res[0] = -1;
			return $res;
		}

	}



?>