<?php
    //echo "entro";
	require_once("../../conex.php");
//=============Get Variables===============    

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "inicio ";
	
//query code

    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_sections = "SELECT `idtrl_categoria`, `categoria`, `descripcion` FROM `trl_categoria` WHERE 1;";
    //echo $query_sections;
    $exec_query_sections = $conex->query($query_sections );
    
    if($exec_query_sections->num_rows>0){
    	$result['sections']=array();
    	while ($row = $exec_query_sections->fetch_assoc()) {
    		$sections=array();
            
            $sections['section_id']= $row['idtrl_categoria'];
            $sections['secction_title']= $row['categoria'];
            $sections['secction_description']= $row['descripcion'];
            
            //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
            
            $query_questions = "SELECT `idtrl_preguntas`, `pregunta` FROM `trl_preguntas` WHERE `trl_categoria_idtrl_categoria`='{$row['idtrl_categoria']}';";
            //echo $query_questions;
            $exec_query_questions = $conex->query($query_questions );
            
            if($exec_query_questions->num_rows>0){
            	$sections['section_questions']=array();
            	while ($row_questions = $exec_query_questions->fetch_assoc()) {
            		$questions=array();
                    $questions['id']=$row_questions['idtrl_preguntas'];
                    $questions['q']=$row_questions['pregunta']; 
            		array_push( $sections['section_questions'] , $questions);	
            	}
            	$result['msg'] = "-> preguntas cargadas";
            }else{
            	
                $result['error'] = true;
    	       $result['msg'] .= "-> Error: error al obtener preguntas de seccion {$row['idtrl_categoria']}";
            }
            
            
    		array_push( $result['sections'] , $sections);	
    	}
    	$result['msg'] .= "-> secciones obtenidas";
    }else{
    
    	$result['error'] = true;
    	$result['msg'] .= '-> Error: error al obtener las secciones ';

    }
	
	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
