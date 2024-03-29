<?php
	
	function conexionbasedatos() {
		$dblink = mysqli_connect("localhost", "root", "", "db_grupo35");
		return $dblink;
	}

	function mmontarmenu() {
		$con = conexionbasedatos();

		$consulta = "select * from final_razas";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	function mvalidarlogin() {
		$con = conexionbasedatos();

		$usuario = $_POST["usuario"];
		$password = md5($_POST["password"]);
		$consulta = "select * from final_usuarios where usuario = '$usuario'";
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
		$consulta = "select * from final_usuarios where usuario = '$usuario'";
		if ($resultado = $con->query($consulta)) {
			$_SESSION["usuario"] = null;
			$_SESSION["password"] = null;
			return 1; // todo ok
		} else {
			return -1;
		}
	}

	function mvalidarsignup() {
		$con = conexionbasedatos();

		$usuario = $_POST["usuario"];
		$password1 = md5($_POST["password1"]);
		$password2 = md5($_POST["password2"]);
		$consulta = "select * from final_usuarios where usuario = '$usuario'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -2; // ya existe usuario con ese nombre
			}
		} else {
			return -1; // fallo base de datos
		}

		if ($password1 == $password2) {
			$consulta = "insert into final_usuarios (usuario, password) values ('$usuario','$password1')";

			if ($resultado = $con->query($consulta)) {
				$_SESSION["usuario"] = $usuario;
				$_SESSION["password"] = $password1;
				return 1;
			} else {
				return -1;
			}
		} else {
			return -3; // las contraseñas no coinciden
		}
		
	}

	function mcomprobarusuariosesion() {
		$con = conexionbasedatos();

		$usuario = $_SESSION["usuario"];
		$password = $_SESSION["password"];

		$consulta = "select * from final_usuarios where usuario = '$usuario'";
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

	function mcargarrazas() {
		$con = conexionbasedatos();

		$consulta = "select * from final_razas";

		if ($resultado = $con->query($consulta)) {
				return $resultado;
			} else {
				return -1;
			}
	}

	function mlistadoanimales() {
		$con = conexionbasedatos();

		$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza";

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

			$consulta = "select count(nombre) as cuenta from final_animales";

			if ($resultado = $con->query($consulta)) {
				$datos = $resultado->fetch_assoc();
				$numerototal = $datos["cuenta"];
			} else {
				$res[0] = -1;
				return $res;
			}

			if ($pagina == 1) {
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza order by nombre limit 3";
			} else {
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza order by nombre limit " . (($pagina - 1) * 3) . ", 3";
			}

		} else {

			$consulta = "select count(nombre) as cuenta from final_animales where idraza='$idraza'";

			if ($resultado = $con->query($consulta)) {
				$datos = $resultado->fetch_assoc();
				$numerototal = $datos["cuenta"];
			} else {
				$res[0] = -1;
				return $res;
			}

			if ($pagina == 1) {
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza where final_animales.idraza='$idraza' order by nombre limit 3";
			} else {
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza where final_animales.idraza='$idraza' order by nombre limit " . (($pagina - 1) * 3) . ", 3";
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

	function mlistadoimagenes($idanimal){
		$con = conexionbasedatos();
		$consulta = "select imagen from final_imagenes where idanimal = '$idanimal'";
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

	function mlistadoimagenesconlink($idanimal){
		$imagenes_array = mlistadoimagenes($idanimal);
		$i = 0;
		$dir= '../../backend/admin/imagenes';
		foreach($imagenes_array as $img){
			$target_path = $dir.'/'.$img;
			$imagenes_array[$i] = "<a title= '".$img."'href='".$target_path."'><img src= '".$target_path."_thumbM' alt='".$img."''".$img."'width='200' height='200'/></a>";
			$i++;
			// Quitar la modificacion del tamaño en cuanto sepa usar css
		}
		$imagenes = implode(" ", $imagenes_array);
		return $imagenes;
	}

	//Selecciona de las imagenes de el animal en concreto para incluirla reescalada en la lista
	function mseleccionarimagen($idanimal){
		$con = conexionbasedatos();
		$consulta = "select imagen from final_imagenes where idanimal = '$idanimal'";
		if ($resultado = $con->query($consulta)) {
			if($resultado->num_rows > 0){
				$row = $resultado->fetch_assoc();
				$imagen = $row["imagen"];
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
				$target_path = $nomOriginalSinTipo . "_thumbM" . $tipo;
				return "<a title= '".$imagen."'href='".$nomOriginal."'><img src= '".$target_path."' alt='".$imagen."''".$imagen."/></a>";
	
			}
			else {
				return "";
			}
			
		} else {
			return -1;
		}
	}

	function mgetimagenesanimal() {
		$con = conexionbasedatos();
		$idanimal = $_GET["idanimal"];
		$consulta = "select imagen from final_imagenes where idanimal = '$idanimal'";
		if ($resultado = $con->query($consulta)){
			return $resultado;
		} else {
			return -1;
		}
	}
	
	function mdatosanimal(){
		$con = conexionbasedatos();
		$idanimal = $_GET["idanimal"];
		$consulta = "select * from final_animales join final_razas on final_animales.idraza = final_razas.idraza where idanimal = '$idanimal'";
		if ($resultado = $con->query($consulta)){
			return $resultado;
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
		$consulta = "select idmensaje, final_animales.idanimal, final_usuariosadmin.usuario, nombre, mensaje, idusuario, fecha, hora, asunto from final_mensajes
						join final_usuarios on final_mensajes.idusuario = final_usuarios.id 
						join final_usuariosadmin on final_mensajes.idadmin = final_usuariosadmin.id 
						left join final_animales on final_animales.idanimal = final_mensajes.idanimal
						where final_usuarios.usuario = '$usuario' and final_mensajes.recibido = 0
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
		$consulta = "select idmensaje, final_animales.idanimal, final_usuariosadmin.usuario, nombre, mensaje, idusuario, fecha, hora, asunto from final_mensajes
						join final_usuarios on final_mensajes.idusuario = final_usuarios.id 
						join final_usuariosadmin on final_mensajes.idadmin = final_usuariosadmin.id 
						left join final_animales on final_animales.idanimal = final_mensajes.idanimal
						where final_usuarios.usuario = '$usuario' and final_mensajes.recibido = 1
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
		$idanimal = $_POST["idanimal"];
		$usuario = $_SESSION["usuario"];
		$consulta = "select * from final_usuarios where usuario ='$usuario'";
		if ($idanimal == ""){
			$idanimal = "NULL";
		}
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				$idusuario = $datos["id"];
				$idusuario = $datos["id"];
				$mensaje = $_POST["mensaje"];
				$asunto = $_POST["asunto"];
				$hora = date('H:i:s');
				$fecha = date('Y-m-d');
				$consulta = "insert into final_mensajes (idanimal,idusuario, mensaje, idadmin, asunto, fecha, hora, recibido )
				VALUES ($idanimal,'$idusuario', '$mensaje', '100', '$asunto', '$fecha', '$hora', '1');";
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
		
		$consulta = "select final_animales.idanimal, final_usuarios.usuario, nombre, mensaje, fecha, hora, asunto from final_mensajes
							join final_usuarios on final_mensajes.idusuario = final_usuarios.id
							left join final_animales on final_animales.idanimal = final_mensajes.idanimal
							where idmensaje = '$idmensaje'";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	function mdatosbusqueda() {
		$con = conexionbasedatos();

		$search = "";	
		if (isset($_GET["search"])) {
			$search = $_GET["search"];
		} elseif (isset($_POST["search"])) {
			$search = $_POST["search"];			
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

		$consulta = "select count(nombre) as cuenta from final_animales where nombre LIKE '%{$search}%' or descripcion LIKE '%{$search}%'";

		if ($resultado = $con->query($consulta)) {
			$datos = $resultado->fetch_assoc();
			$numerototal = $datos["cuenta"];
		} else {
			$res[0] = -1;
			return $res;
		}

		if ($pagina == 1) {
			$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza where 
				nombre LIKE '%{$search}%' or 
				descripcion LIKE '%{$search}%' or
				edad LIKE '%{$search}%' or
				raza LIKE '%{$search}%'
				order by nombre limit 3";
		} else {
			$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from final_animales join final_razas on final_animales.idraza = final_razas.idraza where 
				nombre LIKE '%{$search}%' or 
				descripcion LIKE '%{$search}%' or
				edad LIKE '%{$search}%' or
				raza LIKE '%{$search}%'
				order by nombre limit " . (($pagina - 1) * 3) . ", 3";
		}

		if ($resultado = $con->query($consulta)) {
			$res[0] = $numerototal; // número total de páginas
			$res[1] = $resultado; // contenido de la consulta
 			return $res;
		} else {
			$res[0] = -1;
			return $res;
		}
	}


	function mcargarposts() {
		$con = conexionbasedatos();

		if (isset($_GET["pagina"])) {
			$pagina = $_GET["pagina"];
		} elseif (isset($_POST["pagina"])) {
			$pagina = $_POST["pagina"];
		} else {
			$pagina = 1;
		}

		$numerototal = 0;
		$res = array();

		$consulta = "select count(post) as cuenta from final_posts";

		if ($resultado = $con->query($consulta)) {
			$datos = $resultado->fetch_assoc();
			$numerototal = $datos["cuenta"];
		} else {
			$res[0] = -1;
			return $res;
		}

		if ($pagina == 1) {
			$consulta = "select * from final_posts limit 3";
		} else {
			$consulta = "select * from final_posts limit " . (($pagina - 1) * 3) . ", 3";
		}

		if ($resultado = $con->query($consulta)) {
			$res[0] = $numerototal; // número total de páginas
			$res[1] = $resultado; // contenido de la consulta
 			return $res;
		} else {
			$res[0] = -1;
			return $res;
		}

	}

	function mdatospost() {
		$con = conexionbasedatos();

		$idpost = $_POST["idpost"];

		$consulta = "select * from final_posts join final_usuariosadmin on final_usuariosadmin.id = final_posts.idadmin where idpost = '$idpost'";
		if ($resultado = $con->query($consulta)){
			return $resultado;
		} else {
			return -1;
		}		
	}

	function mcargarcomentarios() {
		$con = conexionbasedatos();

		$idpost = $_POST["idpost"];
		$consulta = "select * from final_comentarios join final_usuarios on final_usuarios.id = final_comentarios.idusuario where idpost = '$idpost'";
		if ($resultado = $con->query($consulta)){
			return $resultado;
		} else {
			return -1;
		}	

	}

	function malmacenarcomentario() {
		$con = conexionbasedatos();

		$usuario = $_SESSION["usuario"];
		$comentario = $_POST["comentario"];
		$idpost = $_POST["idpost"];

		$consulta = "select id from final_usuarios where usuario='$usuario'";

		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				$idusuario = $datos["id"];

				$consulta = "insert into final_comentarios (comentario, idpost, idusuario) values ('$comentario', '$idpost', '$idusuario')";
				if ($resultado = $con->query($consulta)) {
					return 1;
				} else {
					return -1;
				}
			}
		}
	}


?>
