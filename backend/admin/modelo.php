<?php

	function conexionbasedatos() {
		$dblink = mysqli_connect("bdserver", "grupo35", "dah2Quae8v", "db_grupo35");
		return $dblink;
	}

?>
