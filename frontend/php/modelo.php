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

	function mvalidarlogin() {
		$con = conexionbasedatos();

		$usuario = $_POST["usuario"];
		$password = md5($_POST["password"]);
		$consulta = "select * from usuarios where usuario = '$usuario'";
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
		$consulta = "select * from usuarios where usuario = '$usuario'";
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
		$consulta = "select * from usuarios where usuario = '$usuario'";
		if ($resultado = $con->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				return -2; // ya existe usuario con ese nombre
			}
		} else {
			return -1; // fallo base de datos
		}

		if ($password1 == $password2) {
			$consulta = "insert into usuarios (usuario, password) values ('$usuario','$password1')";

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

	function mcargarrazas() {
		$con = conexionbasedatos();

		$consulta = "select * from razas";

		if ($resultado = $con->query($consulta)) {
				return $resultado;
			} else {
				return -1;
			}
	}

	function mlistadoanimales() {
		$con = conexionbasedatos();

		$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza";

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
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza order by nombre limit 3";
			} else {
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza order by nombre limit " . (($pagina - 1) * 3) . ", 3";
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
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza where animales.idraza='$idraza' order by nombre limit 3";
			} else {
				$consulta = "select idanimal, nombre, raza, edad, genero, fechaentrada, descripcion from animales join razas on animales.idraza = razas.idraza where animales.idraza='$idraza' order by nombre limit " . (($pagina - 1) * 3) . ", 3";
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

	function mlistadoimagenesconlink($idanimal){
		$imagenes_array = mlistadoimagenes($idanimal);
		$i = 0;
		$dir= '../../backend/admin/imagenes';
		foreach($imagenes_array as $img){
			$target_path = $dir.'/'.$img;
			$imagenes_array[$i] = "<a title= '".$img."'href='".$target_path."'><img src= '".$target_path."' alt='".$img."''".$img."'width='200' height='200'/></a>";
			$i++;
			// Quitar la modificacion del tamaño en cuanto sepa usar css
		}
		$imagenes = implode(" ", $imagenes_array);
		return $imagenes;
	}

?>