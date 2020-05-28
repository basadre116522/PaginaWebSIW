<?php

	function conexionbasedatos() {
		$dblink = mysqli_connect("localhost", "root", "", "db_grupo35");
		return $dblink;
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

	function mvalidarlogout() {
		$con = conexionbasedatos();
		$usuario = $_SESSION["usuario"];
		$password = $_SESSION["password"];
		$consulta = "select * from usuariosadmin where usuario = '$usuario'";
		if ($resultado = $con->query($consulta)) {
			$_SESSION["usuario"] = null;
			$_SESSION["password"] = null;
			return 1; // todo ok
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
				return $resultado;
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
		-2 --> Si hay un problema al eliminar las imagenes
	*******************************/
	function mvalidarmodificaranimal() {
		$con = conexionbasedatos();

		$idanimal = $_POST["idanimal"];
		$nombre = $_POST["nombre"];
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
					return 1;
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

	function mvalidaraltapost() {
		$con = conexionbasedatos();

		$titulo = $_POST["titulo"];
		$post = $_POST["post"];
		$usuarioadmin = $_SESSION["usuario"];

		$consulta = "select id from usuariosadmin where usuario='$usuarioadmin'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				$idusuarioadmin = $datos["id"];
			}
		} else {
			return -1;
		}

		$consulta = "select titulo from posts where titulo = '$titulo'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -2;
			}
		} else {
			return -1;
		}

		$consulta = "select post from posts where post = '$post'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -3;
			}
		} else {
			return -1;
		}

		$consulta = "insert into posts (titulo, post, idadmin) values ('$titulo', '$post', '$idusuarioadmin')";
		if ($resultado = $con->query($consulta)) {
			return 1;
		} else {
			echo "hey";
			return -1;
		}

	}

	function mlistadoposts() {
		$con = conexionbasedatos();

		$consulta = "select * from posts order by idpost";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}

	}


	/******************************
	Función encargada de coger un post
	Devuelve
		Datos de un post
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mdatospost() {
		$con = conexionbasedatos();

		$idpost = $_GET["idpost"];
		
		$consulta = "select * from posts where idpost='$idpost'";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}


	/******************************
	Función encargada de coger un post
	Devuelve
		Datos de un post
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mvalidarmodificarpost() {
		$con = conexionbasedatos();
	
		$idpost = $_POST["idpost"];	
		$titulo = $_POST["titulo"];
		$post = $_POST["post"];

		$consulta = "select titulo, post from posts where titulo = '$titulo' and idpost != '$idpost'";

		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -2;
			} else {

				$consulta = "select titulo, post from posts where post = '$post' and idpost != '$idpost'";

				if ($resultado = $con->query($consulta)) {
					if ($datos = $resultado->fetch_assoc()) {
						return -3;
					} else {
						$consulta = "update posts set titulo = '$titulo', post = '$post' where idpost = '$idpost'";

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
		} else {
			return -1;
		}
	}


	function meliminarpost() {
		$con = conexionbasedatos();

		$idpost = $_POST["idpost"];

		$consulta = "delete from comentarios where idpost = '$idpost'";
			
		if ($resultado = $con->query($consulta)) {

			$consulta = "delete from posts where idpost = '$idpost'";
			
			if ($resultado = $con->query($consulta)) {
				return 1;
			} else {
				return -1;
			}
		} else {
			return -1;
		}
		
	}


	function mlistadocomentarios() {
		$con = conexionbasedatos();

		$consulta = "select * from comentarios";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}

	}


	function mdatoscomentario() {
		$con = conexionbasedatos();

		$idcomentario = $_GET["idcomentario"];
		
		$consulta = "select * from comentarios where idcomentario='$idcomentario'";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}

	}


	function meliminarcomentario() {
		$con = conexionbasedatos();

		$idcomentario = $_POST["idcomentario"];
		
		$consulta = "delete from comentarios where idcomentario = $idcomentario";
	
		if ($resultado = $con->query($consulta)) {
			return 1;
		} else {
			return -1;
		}

	}



	/******************************
	Función encargada de coger listado de mensajes recibidos
	Devuelve
		Listado de mensajes
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mlistadomensajesrecibidos() {
		$con = conexionbasedatos();

		$usuario = $_SESSION["usuario"];
		$consulta = "select idmensaje, animales.idanimal, usuarios.usuario, nombre, mensaje, idusuario, fecha, hora, asunto from mensajes
						join usuarios on mensajes.idusuario = usuarios.id 
						join usuariosadmin on mensajes.idadmin = usuariosadmin.id 
						left join animales on animales.idanimal = mensajes.idanimal
						where usuariosadmin.usuario = '$usuario' and mensajes.recibido = 1
						union
						select idmensaje, animales.idanimal, usuarios.usuario, nombre, mensaje, idusuario, fecha, hora, asunto from mensajes
						join usuarios on mensajes.idusuario = usuarios.id 
						join usuariosadmin on mensajes.idadmin = usuariosadmin.id 
						left join animales on animales.idanimal = mensajes.idanimal
						where mensajes.idadmin = 100 and mensajes.recibido = 1
						order by fecha, hora desc";
						
		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	/******************************
	Función encargada de coger listado de mensajes enviados
	Devuelve
		Listado de mensajes
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mlistadomensajesenviados() {
		$con = conexionbasedatos();

		$usuario = $_SESSION["usuario"];
		$consulta = "select idmensaje, animales.idanimal, usuarios.usuario, nombre, mensaje, idusuario, fecha, hora, asunto from mensajes
						join usuarios on mensajes.idusuario = usuarios.id 
						join usuariosadmin on mensajes.idadmin = usuariosadmin.id 
						left join animales on animales.idanimal = mensajes.idanimal
						where usuariosadmin.usuario = '$usuario' and mensajes.recibido = 0
						order by fecha, hora desc";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	/******************************
	Función encargada de introducir un mensaje en la base de datos
	Devuelve
		1 --> Si se ha dado de alta correctamente
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function malmacenarmensaje() {
		$con = conexionbasedatos();
		setlocale(LC_ALL,"es_ES");

		$usuarioadmin = $_SESSION["usuario"];
		$consulta = "select * from usuariosadmin where usuario ='$usuarioadmin'";

		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				$idadmin = $datos["id"];
				$destinatario = $_POST["destinatario"];
				$consulta = "select * from usuarios where usuario ='$destinatario'";
				if ($resultado = $con->query($consulta)) {
					if ($datos = $resultado->fetch_assoc()) {
						$idusuario = $datos["id"];
						$mensaje = $_POST["mensaje"];
						$asunto = $_POST["asunto"];
						$hora = date('H:i:s');
						$fecha = date('Y-m-d');
						$consulta = "insert into mensajes (idusuario, mensaje, idadmin, asunto, fecha, hora, recibido )
						VALUES ('$idusuario', '$mensaje', '$idadmin', '$asunto', '$fecha', '$hora', '0');";
						if ($resultado = $con->query($consulta)) {
							return 1;
						} else {
							return -1;
						}
					} else {
						return -1;
					}
				} else {
					return -1;
				}
			} else {
				return -1;
			}
		} else {
			return -1;
		}


	}

	/******************************
	Función encargada de coger un mensaje
	Devuelve
		Datos de un mensaje
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mdatosmensaje() {
		$con = conexionbasedatos();

		$idmensaje = $_GET["idmensaje"];
		
		$consulta = "select animales.idanimal, usuarios.usuario, nombre, mensaje, fecha, hora, asunto from mensajes
							join usuarios on mensajes.idusuario = usuarios.id
							left join animales on animales.idanimal = mensajes.idanimal
							where idmensaje = '$idmensaje'";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	/******************************
	Función encargada de leer un fichero csv que contenga los datos de razas y introducirlo en la base de datos
	Devuelve
		Datos de un mensaje
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mleercsvraza(){
		$con = conexionbasedatos();
		$fichero = $_GET["fichero"];
		$directorio = '../csv';
		$path = $directorio.'/'.$fichero;
		
		if (($gestor = fopen($path, "r")) !== FALSE) {
			while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
				$raza = $datos[0];

				$consulta = "select raza from razas where raza = '$raza'";
				if ($resultado = $con->query($consulta)) {
					if ($datos = $resultado->fetch_assoc()) {
						echo "error: la raza $raza ya existe <br/>";
					}
					else {
						$consulta = "insert into razas (raza) values ('$raza')";
		
						if ($resultado != $con->query($consulta)) {
							return -1;
						}
					}
				} else {
					return -1;
				}
		

			}
			fclose($gestor);
			return 1;
		}
	}

	/******************************
	Función encargada de leer un fichero csv que contenga datos de animales y introducirlo en la base de datos
	Devuelve
		Datos de un mensaje
		-1 --> Si hay un problema con la base de datos
	*******************************/
	function mleercsvanimal(){
		$con = conexionbasedatos();
		$fichero = $_GET["fichero"];
		$directorio = '../csv';
		$path = $directorio.'/'.$fichero;
		
		if (($gestor = fopen($path, "r")) !== FALSE) {
			while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
				$nombre = $datos[0];
				$edad = $datos[1];
				$genero = $datos[2];
				$descripcion = $datos[3];
				$fechaentrada = $datos[4];
				$raza = $datos[5];
				$consulta = "select idraza from razas where raza = '$raza'";
				if ($resultado = $con->query($consulta)) {
					if ($datos = $resultado->fetch_assoc()) {
						$idraza = $datos["idraza"];
					}
				}
				if (isset($idraza)) {
					$consulta = "insert into animales (nombre, edad, genero, fechaentrada, descripcion, idraza) value ('$nombre','$edad','$genero','$fechaentrada','$descripcion','$idraza')";
		
					if ($resultado = $con->query($consulta)) {
						return $resultado;
					}
				}		
			}
			fclose($gestor);
			return 1;
		} else {
			return -2;
		}
	}


	function mexportjsonanimal(){
		$con = conexionbasedatos();

		//generamos la consulta
		
		$consulta = "select * from animales";
		
			if($result = $con->query($consulta)){
				$data = array(); //creamos un array
		
				//guardamos en un array multidimensional todos los datos de la consulta
				$i=0;
			
				while($row = $result->fetch_array())
				{
					$data[$i] = $row;
					for ($j = 0; $j <= 6 ; $j++) {
						unset($data[$i]["$j"]);
					}
					$i++;
				}
		
				$file = '../json/animales.json';
				file_put_contents($file, json_encode($data));
			} else {
				return -1;
			}
			return 1;
	}

	function mexportjsonraza(){
		$con = conexionbasedatos();

		//generamos la consulta
		
		$consulta = "select * from razas";
		
			if($result = $con->query($consulta)){
				$data = array(); //creamos un array
		
				//guardamos en un array multidimensional todos los datos de la consulta
				$i=0;
			
				while($row = $result->fetch_array())
				{
					$data[$i] = $row;
					unset($data[$i]["0"]);
					unset($data[$i]["1"]);
					$i++;
				}
				
				$file = '../json/razas.json';
				file_put_contents($file, json_encode($data));

			} else {
				return -1;
			}
			return 1;
	}

	function mexportjsonpost(){
		$con = conexionbasedatos();

		//generamos la consulta
		
		$consulta = "select * from posts";
		
			if($result = $con->query($consulta)){
				$data = array(); //creamos un array
		
				//guardamos en un array multidimensional todos los datos de la consulta
				$i=0;
			
				while($row = $result->fetch_array())
				{
					$data[$i] = $row;
					unset($data[$i]["0"]);
					unset($data[$i]["1"]);
					unset($data[$i]["2"]);
					unset($data[$i]["3"]);
					$i++;
				}
				
				$file = '../json/posts.json';
				file_put_contents($file, json_encode($data));

			} else {
				return -1;
			}
			return 1;
	}

	function mexportjsoncomentario(){
		$con = conexionbasedatos();

		//generamos la consulta
		
		$consulta = "select * from comentarios";
		
			if($result = $con->query($consulta)){
				$data = array(); //creamos un array
		
				//guardamos en un array multidimensional todos los datos de la consulta
				$i=0;
			
				while($row = $result->fetch_array())
				{
					$data[$i] = $row;
					unset($data[$i]["0"]);
					unset($data[$i]["1"]);
					unset($data[$i]["2"]);
					unset($data[$i]["3"]);
					$i++;
				}
				
				$file = '../json/comentarios.json';
				file_put_contents($file, json_encode($data));

			} else {
				return -1;
			}
			return 1;
	}

?>
