<?php
	include("con_db.php");

	$consulta_nivel_estudios = "SELECT * FROM phi3_nivel_estudios";
	$ejecutarNivelEstudios = mysqli_query($conex, $consulta_nivel_estudios);

	while($fila = mysqli_fetch_array($ejecutarNivelEstudios)){
		echo "<option value = '".$fila['id_nivel']."'>".$fila['nivel_estudios']."</option>";
	}
?>