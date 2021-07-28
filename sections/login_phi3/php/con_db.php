<?php
	$host = 'localhost';
	$user = 'root';
	$password = 'root';
	$db = 'tbr';

	$conex = @mysqli_connect($host, $user, $password, $db);
	if(!$conex){
		echo "Error en la conexión";
	}else{
		echo "Conexión exitosa";
	}
	$conex -> set_charset("utf8");
?>