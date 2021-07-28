<?php
	include("con_db.php");

	$consulta_tipo_usuario = "SELECT * FROM phi3_tipo_usuario";
	$ejecutarTipoUsuario = mysqli_query($conex, $consulta_tipo_usuario);

	while($fila = mysqli_fetch_array($ejecutarTipoUsuario)){
		echo "<option value = '".$fila['id_tipo_usuario']."'>".$fila['tipo_usuario']."</option>";
	}
?>