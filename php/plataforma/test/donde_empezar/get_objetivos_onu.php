<?php
    //echo "entro";
	require_once("../../../conex.php");

    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_objetivos = "SELECT `idobjetivos_onu`, `objetivo`, `color` FROM `objetivos_onu` WHERE 1 ORDER by `idobjetivos_onu` ASC;";
    //echo $query_objetivos;
    $exec_query_objetivos = $conex->query($query_objetivos );
    
    if($exec_query_objetivos->num_rows>0){
    	$result['objetivos']=array();
    	while ($row_objetivos = $exec_query_objetivos->fetch_assoc()) {
    		$objetivos=array();
    		$objetivos['id'] = $row_objetivos['idobjetivos_onu'];
    		$objetivos['objetivo'] = $row_objetivos['objetivo'];
    		$objetivos['color'] = $row_objetivos['color'];
    		array_push( $result['objetivos'] , $objetivos);	
    	}
    	$result['msg'] .= "->data objetivos selected ";
    }else{
    	
    	$result['error'] = true;
        $result['msg'] .= "->ERROR selecting objetivos";
    }

	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
