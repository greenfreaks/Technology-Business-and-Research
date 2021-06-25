<?php
    //echo "entro";
	require_once("../../conex.php");
	
//print_r($_POST);

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
//=============Get Variables===============

	$proyecto = isset($_POST["project"]) ? $conex->real_escape_string ($_POST["project"]) : "proyecto anonimo";
	$tipo = isset($_POST["tipo"]) ? $conex->real_escape_string ($_POST["tipo"]) : "1";
	$userID = isset($_POST["userID"]) ? $conex->real_escape_string ($_POST["userID"]) : "0";
	$startTime = isset($_POST["startTime"]) ? $conex->real_escape_string ($_POST["startTime"]) : time();
	$finishTime = isset($_POST["finishTime"]) ? $conex->real_escape_string ($_POST["finishTime"]) : time();
	$respuestas = isset($_POST["answers"]) ? $_POST["answers"] : [];

    $query_proyecto = "INSERT INTO `trl_usuarios`(`titulo_proyecto`, `trl_tipos_usuarios_idtrl_tipos_usuarios`, `startTime`, `finishTime`, `loginID`) VALUES ('{$proyecto}','{$tipo}','{$startTime}','{$finishTime}','{$userID}');";
    //echo $query_proyecto;
    $exec_query_proyecto = $conex->query($query_proyecto);

    if ($exec_query_proyecto === TRUE) {
        
        $last_id_proyecto = $conex->insert_id; 
        $result['projectID'] = $last_id_proyecto;
        $result['msg'] .= "->[Project {$last_id_proyecto} received]";
        $inserts=0;
        $total=count($respuestas);
        
        foreach($respuestas as $r){
            $query_answer = "INSERT INTO `trl_respuestas`( `usuarioTRL_idusuarioTRL`, `trl_preguntas_idtrl_preguntas`) VALUES ('{$last_id_proyecto}','{$conex->real_escape_string ($r)}');";
            //echo $query_answer;
            $exec_query_answer = $conex->query($query_answer);
            
            if($exec_query_answer === TRUE)
            {
                $inserts+=1;        
            }
            else
            {
                $result['error'] = true;
                $result['msg'] .= "->[ERROR inserting answer {$r}]";
                break;
            } 
        }
        
        $result['msg'] .= "->[{$inserts} of {$total} answers inserted]";
        
        if ($inserts == $total){
                        
/*            $query_nivelObtenido = "SELECT c1 as Nivel, ROUND(c4*100/c2) as porcentaje FROM (
					  SELECT niveles_idniveles AS c1, COUNT(niveles_idniveles) AS c2
						FROM trl_preguntas 
						group by niveles_idniveles) AS cp INNER JOIN
(SELECT niveles_idniveles AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$last_id_proyecto} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY niveles_idniveles) AS cr
ON (cp.c1= cr.c3)
ORDER BY porcentaje DESC, cp.c1 DESC
LIMIT 1;";*/
            
            $query_nivelObtenido = "SELECT * FROM (SELECT c1 as Nivel, ROUND(c4*100/c2) as porcentaje FROM (
					  SELECT niveles_idniveles AS c1, COUNT(niveles_idniveles) AS c2
						FROM trl_preguntas 
						group by niveles_idniveles) AS cp 
INNER JOIN (SELECT niveles_idniveles AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$last_id_proyecto} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY niveles_idniveles) AS cr
ON (cp.c1= cr.c3)
ORDER BY porcentaje DESC, cp.c1 DESC) AS generalNivel
WHERE porcentaje >= 30
LIMIT 1;";
            //echo $query_nivelObtenido;
            $exec_query_nivelObtenido = $conex->query($query_nivelObtenido );
            
            if($exec_query_nivelObtenido->num_rows==1){
                $result['resultado']= $exec_query_nivelObtenido->fetch_assoc();
            	$result['msg'] .= "->[data nivelObtenido selected]";
            }else{
            	
            	$result['resultado']= array('Nivel' => '0', 'pocentaje' => '0');
            	$result['msg'] .= "->[data nivel no suficiente ]";
            }
            
            //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
            
            $query_desglose = "SELECT c6 as categoria ,c2 as aspCat,c4 as aspLogrados,ROUND(c4*100/c2) as porcentaje FROM (
					  SELECT trl_categoria_idtrl_categoria AS c1, COUNT(trl_categoria_idtrl_categoria) AS c2
						FROM trl_preguntas 
						group by trl_categoria_idtrl_categoria) AS cp INNER JOIN
(SELECT trl_categoria_idtrl_categoria AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$last_id_proyecto} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY trl_categoria_idtrl_categoria) AS cr INNER JOIN 
    (SELECT idtrl_categoria as c5, categoria as c6 from trl_categoria) as cc
ON (cp.c1= cr.c3 and cr.c3 = cc.c5);";
            
            //echo $query_desglose;
            $exec_query_desglose = $conex->query($query_desglose);
            
            if($exec_query_desglose->num_rows>0){
            	$result['desglose']=array();
            	while ($row = $exec_query_desglose->fetch_assoc()) {
            		//$desglose=array();
            		array_push( $result['desglose'] , $row);	
            	}
            	$result['msg'] .= "->[data desglose selected]";
            }else{
            	
            	$result['error'] = true;
                $result['msg'] .= "->[ERROR selecting desglose]";
            }
            
        }
                
    } else {

        $result['error'] = true;
        $result['msg'] .= "->[ERROR receiving project]";
    }    
	
	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
