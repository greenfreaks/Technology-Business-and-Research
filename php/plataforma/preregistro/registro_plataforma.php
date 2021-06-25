<?php
    //echo "entro";
	require_once("../../conex.php");

//print_r($_POST);

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    $result["data"] = $_POST;
//=============Get Variables===============


	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
