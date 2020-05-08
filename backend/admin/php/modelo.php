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

	/******************************
	Función encargada de dar de alta un animal
	Devuelve
		 1 --> Si se ha realizado el alta correctamente
		-1 --> Si hay un problema con la base de datos
		-2 --> Si ha habido un problema a la hora de coger la imagen
		-3 --> Si ha tenido un problema al coger el idanimal
		-4 --> Si hay un problema en la tabla de imagenes
	*******************************/
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
				// El foreach no funciona bien, solo incluye una de las imagenes ver porque
				foreach($_FILES["imagenes"]['tmp_name'] as $key => $tmp_name) {
					if($_FILES["imagenes"]["name"][$key]) {
						$filename = $_FILES["imagenes"]["name"][$key]; //Obtenemos el nombre original del archivo
						$source = $_FILES["imagenes"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
						
						$directorio = '../imagenes'; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						//Validamos si la ruta de destino existe, en caso de no existir la creamos
						if(!file_exists($directorio)){
							mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
						}
						
						$dir=opendir($directorio); //Abrimos el directorio de destino
						$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
						//Movemos y validamos que el archivo se haya cargado correctamente
						//El primer campo es el origen y el segundo el destino
						if(move_uploaded_file($source, $target_path)) {	
							closedir($dir); 
							$consulta = "select idanimal from animales order by idanimal desc limit 1";
							if ($resultado = $con->query($consulta)) {
								if ($datos = $resultado->fetch_assoc()){
									$idanimal = $datos["idanimal"];
									$consulta = "insert into imagenes (idanimal, imagen) value ('$idanimal','$filename')";
									if ($resultado = $con->query($consulta)) {
										return 1;
									}
									else {
										return -4;
									}
								} else {
									return -3;
								}
							} else {
								return -3;
							}
							} else {	
								closedir($dir); 
								return -2;
						}
					}
				}
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

	function mlistadoimagenes($idanimal){
		$con = conexionbasedatos();
		$consulta = "select imagen from imagenes where idanimal = '$idanimal'";
		if ($resultado = $con->query($consulta)) {
			$rows = [];
			$i=0;
			while ($datos = $resultado->fetch_array()){
				$rows[$i] = $datos["imagen"];
				$i++;
			}
			return $rows;
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
	/******************************
	Función encargada de modificar los datos de un animal
	Devuelve
		 1 --> Si se ha realizado el alta correctamente
		-1 --> Si hay un problema con la base de datos de animal
		-2 --> Si hay un problema en mover las imagenes
		-3 --> Si hay un erro al insertar los datos de las imagenes
	*******************************/
	function mvalidarmodificaranimal() {
		$con = conexionbasedatos();

		$idanimal = $_POST["idanimal"];echo $idanimal;
		$nombre = $_POST["nombre"];echo $nombre;
		$edad = $_POST["edad"];
		$genero = $_POST["genero"];
		$fechaentrada = $_POST["fechaentrada"];
		$descripcion = $_POST["descripcion"];
		$idraza = $_POST["idraza"];
		$imagen = $_POST["e_imagenes"];

		if ($idraza == 0) {
			//no se ha elegido raza
			return -2;
		} else {
			$consulta = "update animales set nombre = '$nombre', edad = '$edad', genero = '$genero', fechaentrada = '$fechaentrada', descripcion = '$descripcion', idraza = $idraza where idanimal = $idanimal";

			if ($resultado = $con->query($consulta)) {
				$consulta = "delete from imagenes where idanimal = '$idanimal' and imagen = '$imagen'";
				if ($resultado = $con->query($consulta)){
					foreach($_FILES["a_imagenes"]['tmp_name'] as $key => $tmp_name) {
						if($_FILES["a_imagenes"]["name"][$key]) {
							$filename = $_FILES["a_imagenes"]["name"][$key]; //Obtenemos el nombre original del archivo
							$source = $_FILES["a_imagenes"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
							
							$directorio = '../imagenes'; //Declaramos un  variable con la ruta donde guardaremos los archivos
							
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}
							
							$dir=opendir($directorio); //Abrimos el directorio de destino
							$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							if(move_uploaded_file($source, $target_path)) {	
								closedir($dir); 
								$consulta = "insert into imagenes (idanimal, imagen) value ('$idanimal','$filename')";

								// Hay que comprobar para ello  que no exista ya la imagen para el animal porque si no da error.
								if ($resultado = $con->query($consulta)) {
									return 1;
								}
								else {
									return -3;
								}
							} else {	
								closedir($dir); 
								return -2;
							}
						}
					return 1;
					}
				} else {
					return -2;
				}
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

	function mvalidarlogin() {
		$con = conexionbasedatos();

		$usuario = $_POST["usuario"];
		$password = md5($_POST["password"]);
		$consulta = "select * from usuariosadmin where usuario = '$usuario'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				if ($password == $datos["password"]) {
					$_SESSION["usuario"] = $usuario;
					$_SESSION["password"] = $password;
					return 1; // todo ok
				} else {
					return -3; //se ha introducido mal la contraseña
				}
			} else {
				return -2; //no existe usuario
			}
		} else {
			return -1;
		}
	}


	function mcomprobarusuariosesion() {
		$con = conexionbasedatos();

		$usuario = $_SESSION["usuario"];
		$password = $_SESSION["password"];

		$consulta = "select * from usuariosadmin where usuario = '$usuario'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				if ($password == $datos["password"]) {
					return 1; // todo ok
				} else {
					return -3; //se ha introducido mal la contraseña
				}
			} else {
				return -2; //no existe usuario
			}
		} else {
			return -1;
		}
		
	}





?>
