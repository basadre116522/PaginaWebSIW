<?php
	$con = mysqli_connect("localhost", "root", "", "db_grupo35");
	$idanimal = $_GET["idanimal"];
	$trozos = explode("/", $_FILES["file"]["type"]);
	$tipo = strstr($_FILES["file"]["name"], ".");
	$dir = "../imagenes";
	$name = $idanimal . "-" . uniqid() . "-" . time() . "-" . uniqid();
	if($tipo== ".jpg"){
		$tipo = ".jpeg";
	}
	$name_bd = $name.$tipo;
	$rutaSinType = $dir."/" . $name;
	$ruta = $rutaSinType . "." .  $trozos[1];
	$tmp_name = $_FILES["file"]["tmp_name"];
	    if (move_uploaded_file($tmp_name, $ruta)) {
    	echo "Subida OK";
    	// INSERTAMOS LA IMAGEN EN LA BD [OK]
		$consulta = "insert into imagenes (idanimal, imagen) values ('$idanimal', '$name_bd')";
		if ($con->query($consulta)) {
			echo "Consulta OK";
			// Miniatura de tamaño pequeño
			$tam = "P";
			crearMiniaturas($rutaSinType, 150, $trozos[1], $tam);
			// Miniatura de tamaño mediano
			$tam = "M";
			crearMiniaturas($rutaSinType, 300, $trozos[1], $tam);
			// Miniatura de tamaño grande
			$tam = "G";
			crearMiniaturas($rutaSinType, 600, $trozos[1], $tam);
		} else {
			echo "Error en la consulta SQL";
		}
	}


	function crearMiniaturas($rutaSinType, $width, $type, $tam) {
		$ruta = $rutaSinType . "." . $type;
		$newWidth = $width;
		$newHeight = $newWidth;
		$file_info = getimagesize($ruta);
		/* Si quisieramos respetar las proporciones...
		$ratio = $file_info[0] / $file_info[1];
		$newHeight = round($newWidth / $ratio);
		*/
		// Solamente aceptamos los formatos jpeg y png
		if ($type == "jpeg") {
        	$imagenOriginal = imagecreatefromjpeg($ruta);
        } else {
        	$imagenOriginal = imagecreatefrompng($ruta);
        }
        $mini = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($mini, $imagenOriginal, 0, 0, 0, 0, $newWidth, $newHeight, $file_info[0], $file_info[1]);
		if ($type == "jpeg") {
        	imagejpeg($mini, $rutaSinType . "_thumb" . $tam . "." . $type);
        } else {
        	imagepng($mini, $rutaSinType . "_thumb" . $tam . "." . $type);
        }
    }

?>