<?php
    //echo "entro";
	require_once("conex.php");
	$action = "Register";
	//$postdata = file_get_contents("php://input");
//=============Get Variables===============
	
	//$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "error desconocido";
	
//query code

    $query_string = ";";
    $exec_query = $conex->query($query_string );
	
	if($exec_query->num_rows>0){
		
		while ($row = $exec_query->fetch_assoc()) {
			
		}
		
	}else{
		
		$asunto = 'Resgitro fallido';
		$cause = 'Causa del error';
		$result['error'] = true;
		$result['msg'] = "<h4>Ha ocurrido un error.</h4><br><br>--{$cause}<br><br>  Para resolver esta situacion manda un correo a <a href='mailto:{$admin_email}?subject={$asunto}'>{$admin_email}</a> con el asunto <h4>'{$asunto}'</h4> y describe tu problema";
		
		$query_log = $conex->query("INSERT INTO `logs`( `action`, `details`) VALUES ('error:{$action}','{$cause} // {$postdata}');");
	}
	
	$result_json = json_encode($result);
	echo "{$_GET['callback']}({$result_json})";

	$conex->close();

?>
