<?php
    //echo "entro";
	require_once("../../../conex.php");

    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_campo = "SELECT `idcampo_de_conocimiento`, `campo_de_conocimiento` FROM `campo_de_conocimiento` WHERE 1;";
    //echo $query_campo;
    $exec_query_campo = $conex->query($query_campo );
    
    if($exec_query_campo->num_rows>0){
    	$result['campos']=array();
    	while ($row_campo = $exec_query_campo->fetch_assoc()) {
    		$campo=array();
    		$campo['id'] = $row_campo['idcampo_de_conocimiento'];
    		$campo['cc'] = $row_campo['campo_de_conocimiento'];
            
            $query_disciplina = "SELECT `iddisciplina`, `disciplina` FROM `disciplina` WHERE `campo_de_conocimiento_idcampo_de_conocimiento`='{$row_campo['idcampo_de_conocimiento']}';";
            //echo $query_disciplina;
            $exec_query_disciplina = $conex->query($query_disciplina );
            
            if($exec_query_disciplina->num_rows>0){
            	$campo['disciplinas']=array();
            	while ($row_disciplina = $exec_query_disciplina->fetch_assoc()) {
            		$disciplina=array();
            		$disciplina['id'] = $row_disciplina['iddisciplina'];
            		$disciplina['disciplina'] = $row_disciplina['disciplina'];
                                        
                    $query_subdisciplina = "SELECT `idsubdisciplina`, `subdisciplina` FROM `subdisciplina` WHERE `disciplina_iddisciplina`='{$row_disciplina['iddisciplina']}';";
                    //echo $query_subdisciplina;
                    $exec_query_subdisciplina = $conex->query($query_subdisciplina );
                    
                    if($exec_query_subdisciplina->num_rows>0){
                    	$disciplina['subdisciplinas']=array();
                    	while ($row_subdisciplina = $exec_query_subdisciplina->fetch_assoc()) {
                    		$subdisciplina=array();
                    		$subdisciplina['id'] = $row_subdisciplina['idsubdisciplina'];
                    		$subdisciplina['subdisciplina'] = $row_subdisciplina['subdisciplina'];
                    		array_push( $disciplina['subdisciplinas'] , $subdisciplina);	
                    	}
//                    	$result['msg'] .= "->data subdisciplina selected ";
                    }else{
                    	
                    	$result['error'] = true;
                        $result['msg'] .= "->ERROR selecting subdisciplina";
                    }
            		array_push( $campo['disciplinas'] , $disciplina);	
            	}
//            	$result['msg'] .= "->data disciplina selected ";
            }else{
            	
            	$result['error'] = true;
                $result['msg'] .= "->ERROR selecting disciplina";
            }
            
    		array_push( $result['campos'] , $campo);	
    	}
//    	$result['msg'] .= "->data campo selected ";
    }else{
    	
    	$result['error'] = true;
        $result['msg'] .= "->ERROR selecting campo";
    }
    
	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
