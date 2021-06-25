<?php
    //echo "entro";
	require_once("../../../conex.php");

    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
    $query_sectores = "SELECT `idsector_scian`, `sector` FROM `sector_scian` WHERE 1;";
    //echo $query_sectores;
    $exec_query_sectores = $conex->query($query_sectores );
    
    if($exec_query_sectores->num_rows>0){
    	$result['sectores']=array();
    	while ($row_sectores = $exec_query_sectores->fetch_assoc()) {
    		$sectores=array();
    		$sectores['id'] = $row_sectores['idsector_scian'];
    		$sectores['sector'] = $row_sectores['sector'];
            
            $query_subsectores = "SELECT `idsubsector_scian`, `subsector` FROM `subsector_scian` WHERE `sector_scian_idsector_scian`='{$row_sectores['idsector_scian']}';";
            //echo $query_subsectores;
            $exec_query_subsectores = $conex->query($query_subsectores );
            
            if($exec_query_subsectores->num_rows>0){
            	$sectores['subsectores']=array();
            	while ($row_subsectores = $exec_query_subsectores->fetch_assoc()) {
            		$subsectores=array();
            		$subsectores['id'] = $row_subsectores['idsubsector_scian'];
            		$subsectores['subsector'] = $row_subsectores['subsector'];
                    
                    $query_ramas = "SELECT `idrama_scian`, `rama` FROM `rama_scian` WHERE `subsector_scian_idsubsector_scian`='{$row_subsectores['idsubsector_scian']}';";
                    //echo $query_ramas;
                    $exec_query_ramas = $conex->query($query_ramas );
                    
                    if($exec_query_ramas->num_rows>0){
                    	$subsectores['ramas']=array();
                    	while ($row_ramas = $exec_query_ramas->fetch_assoc()) {
                    		$ramas=array();
                    		$ramas['id'] = $row_ramas['idrama_scian'];
                    		$ramas['rama'] = $row_ramas['rama'];
                    		array_push( $subsectores['ramas'] , $ramas);	
                    	}
//                    	$result['msg'] .= "->data ramas selected ";
                    }else{
                    	
                    	$result['error'] = true;
                        $result['msg'] .= "->ERROR selecting ramas";
                    }
                    
            		array_push( $sectores['subsectores'] , $subsectores);	
            	}
//            	$result['msg'] .= "->data subsectores selected ";
            }else{
            	
            	$result['error'] = true;
                $result['msg'] .= "->ERROR selecting subsectores";
            }
            
    		array_push( $result['sectores'] , $sectores);	
    	}
//    	$result['msg'] .= "->data sectores selected ";
    }else{
    	
    	$result['error'] = true;
        $result['msg'] .= "->ERROR selecting sectores";
    }

	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
