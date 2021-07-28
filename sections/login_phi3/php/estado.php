<?php
	include("con_db.php");

	$consulta_estado = "SELECT * FROM estado";
	$ejecutarEstado = mysqli_query($conex, $consulta_estado);

	while($fila = mysqli_fetch_array($ejecutarEstado)){
		echo "<option value = '".$fila['idestado']."'>".$fila['estado']."</option>";
	}
?>