<?php

	function conexionbasedatos() {
		$dblink = mysqli_connect("localhost", "root", "", "db_grupo35");
		return $dblink;
	}


	/******************************
	Función encargada de dar de alta una raza
	Devuelve
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
		-2 --> Si la raza ya existe
	*******************************/
	function malmacenarraza() {
		$con = conexionbasedatos();

		$raza = $_POST["raza"];

		$consulta = "select raza from razas where raza = '$raza'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -2;
			}
		} else {
			return -1;
		}

		$consulta = "insert into razas (raza) values ('$raza')";

		if ($resultado = $con->query($consulta)) {
			return 1;
		} else {
			return -1;
		}

	}

	/******************************
	Función encargada de coger listado de razas
	Devuelve
		Listado de razas
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mlistadorazas() {
		$con = conexionbasedatos();

		$consulta = "select * from razas order by raza";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	/******************************
	Función encargada de coger una raza
	Devuelve
		Datos de una raza
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mdatosraza() {
		$con = conexionbasedatos();

		$idraza = $_GET["idraza"];
		
		$consulta = "select * from razas where idraza='$idraza'";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	/******************************
	Función encargada de actualizar / modificar una raza
	Devuelve
		 1 --> Si se ha realizado la modificación correctamente
		-1 --> Si hay un problema con la base de datos
		-2 --> Ya existe una raza con ese nombre
	*******************************/
	function mmodificarraza() {
		$con = conexionbasedatos();
	
		$idraza = $_POST["idraza"];	
		$raza = $_POST["raza"];

		$consulta = "select raza from razas where raza = '$raza' and idraza != '$idraza'";

		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -2;
			} else {
				$consulta = "update razas set raza = '$raza' where idraza = '$idraza'";
			
				if ($resultado = $con->query($consulta)) {
					return 1;
				} else {
					return -1;
				}				
			}
		} else {
			return -1;
		}
	}


	function mvalidareliminarraza() {
		$con = conexionbasedatos();

		$idraza = $_POST["idraza"];

		$consulta = "select idanimal from animales where idraza = $idraza";

		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				// sí tenemos animales con esa raza
				return -2; 
			} else {
				// no tenemos animales con esa raza
				$consulta = "delete from razas where idraza = $idraza";
				if ($resultado = $con->query($consulta)) {
					return 1;
				} else {
					return -1;
				}
			}
		} else {
			return -1;
		}
	}


	function mvalidaraltaanimal() {
		$con = conexionbasedatos();

		$nombre = $_POST["nombre"];
		$edad = $_POST["edad"];
		$genero = $_POST["genero"];
		$fechaentrada = $_POST["fechaentrada"];
		$descripcion = $_POST["descripcion"];
		$idraza = $_POST["idraza"];
		
		if ($idraza == 0) {
			// no se ha elegido la raza			
			return -2;
		} else {
			$consulta = "insert into animales (nombre, edad, genero, fechaentrada, descripcion, idraza) value ('$nombre','$edad','$genero','$fechaentrada','$descripcion','$idraza')";

			if ($resultado = $con->query($consulta)) {
				return 1;
			} else {
				return -1;
			}
		}
	
	}

	function mlistadoanimales() {
		$con = conexionbasedatos();

		$consulta = "select idanimal, nombre, edad, genero, fechaentrada, descripcion, raza from animales join razas on animales.idraza = razas.idraza order by nombre";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}


	function mdatosanimal() {
		$con = conexionbasedatos();

		$idanimal = $_GET["idanimal"];
		
		$consulta = "select * from animales where idanimal = $idanimal";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}

	}

	function mvalidarmodificaranimal() {
		$con = conexionbasedatos();

		$idanimal = $_POST["idanimal"];echo $idanimal;
		$nombre = $_POST["nombre"];echo $nombre;
		$edad = $_POST["edad"];
		$genero = $_POST["genero"];
		$fechaentrada = $_POST["fechaentrada"];
		$descripcion = $_POST["descripcion"];
		$idraza = $_POST["idraza"];

		if ($idraza == 0) {
			//no se ha elegido raza
			return -2;
		} else {
			$consulta = "update animales set nombre = '$nombre', edad = '$edad', genero = '$genero', fechaentrada = '$fechaentrada', descripcion = '$descripcion', idraza = $idraza where idanimal = $idanimal";

			if ($resultado = $con->query($consulta)) {
				return 1;
			} else {
				return -1; 
			}
		}
	
	}

	function mdatosanimalconraza() {
		$con = conexionbasedatos();

		$idanimal = $_GET["idanimal"];
		
		$consulta = "select idanimal, nombre, edad, genero, fechaentrada, descripcion, raza from animales join razas on animales.idraza = razas.idraza where idanimal='$idanimal'";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}

	}

	function meliminaranimal() {
		$con = conexionbasedatos();

		$idanimal = $_POST["idanimal"];
		
		$consulta = "delete from animales where idanimal = $idanimal";
	
		if ($resultado = $con->query($consulta)) {
			return 1;
		} else {
			return -1;
		}
	}




?>
