<?php
    //echo "entro";
	require_once("conex.php");
//=============Get Variables===============
	
	//$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
	

	echo json_encode($result);

	$conex->close();

?>
