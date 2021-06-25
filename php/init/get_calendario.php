<?php
    //echo "entro";
	require_once("../conex.php");
//=============Get Variables===============
	
	//$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    $result['eventos']=array();

    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_eventos = "SELECT `idcalendario`, `evento`, `lugar`,`fecha_inicio`,`fecha_fin`, DATE_FORMAT(`fecha_inicio`, '%d/%m/%Y %H:%i') as 'fi', DATE_FORMAT(`fecha_fin`, '%d/%m/%Y %H:%i') as 'ff', `link_info`, `impartido_por`, `sede`, `descripcion`, `publico_objetivo`, `precio`, `link_pagar` FROM `calendario` WHERE DATE(`fecha_inicio`) > DATE(NOW()) ORDER BY `fecha_inicio` ASC;";
    //echo $query_eventos;
    $exec_query_eventos = $conex->query($query_eventos );
    
    if($exec_query_eventos->num_rows>0){
    	while ($row = $exec_query_eventos->fetch_assoc()) {
    		$evento=array();
    		$evento['idcalendario']=$row['idcalendario'];
            $evento['evento']=$row['evento'];
            $evento['lugar']=$row['lugar'];
            $evento['fi']=$row['fi'];
            $evento['ff']=$row['ff'];
            $evento['fecha_inicio']=$row['fecha_inicio'];
            $evento['fecha_fin']=$row['fecha_fin'];
            $evento['link']=$row['link_info'];
            $evento['impartido_por']=$row['impartido_por'];
            $evento['sede']=$row['sede'];
            $evento['descripcion']=$row['descripcion'];
            $evento['publico_objetivo']=$row['publico_objetivo'];
            $evento['precio']=$row['precio'];
            $evento['link_pagar']=$row['link_pagar'];

            
    		array_push( $result['eventos'] , $evento);	
    	}
    	$result['msg'] .= "->data eventos selected ";
    }else{
    	
    	$result['error'] = false;
        $result['msg'] .= "->Sin eventos";
    }
	

	echo json_encode($result);

	$conex->close();

?>
