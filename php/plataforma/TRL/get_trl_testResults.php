<?php
    //echo "entro";
	require_once("../../conex.php");
	
//print_r($_POST);

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
//=============Get Variables===============


    $query_proyects = "SELECT `idusuarioTRL`, `email`,  `titulo_proyecto`, `startTime`, `finishTime`, `loginID` FROM `trl_usuarios` WHERE `loginID`>15 AND `loginID`<43;";
    //echo $query_proyects;
    $exec_query_proyects = $conex->query($query_proyects );
    
    if($exec_query_proyects->num_rows>0){
    	$result['proyects']=array();
    	while ($row_proyects = $exec_query_proyects->fetch_assoc()) {
    		$proyect=array();
    		$proyect['idusuarioTRL'] = $row_proyects['idusuarioTRL'];
    		$proyect['email'] = $row_proyects['email'];
    		$proyect['titulo_proyecto'] = $row_proyects['titulo_proyecto'];
    		$proyect['startTime'] = $row_proyects['startTime'];
    		$proyect['finishTime'] = $row_proyects['finishTime'];
            
            $query_TRL_level = "SELECT * FROM (SELECT c1 as Nivel, ROUND(c4*100/c2) as porcentaje FROM (
					  SELECT niveles_idniveles AS c1, COUNT(niveles_idniveles) AS c2
						FROM trl_preguntas 
						group by niveles_idniveles) AS cp 
INNER JOIN (SELECT niveles_idniveles AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$row_proyects['idusuarioTRL']} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY niveles_idniveles) AS cr
ON (cp.c1= cr.c3)
ORDER BY porcentaje DESC, cp.c1 DESC) AS generalNivel
WHERE porcentaje >= 30
LIMIT 1;";
            //echo $query_TRL_level;
            $exec_query_TRL_level = $conex->query($query_TRL_level);
            
            if($exec_query_TRL_level->num_rows==1){
                $proyect['TRL_level']= $exec_query_TRL_level->fetch_assoc();
            }else{
            
                $result['error'] = true;
                $result['msg'] .= "->ERROR selecting TRL_level";
            }
    		array_push( $result['proyects'] , $proyect);
    	}
    	$result['msg'] .= "->data proyects selected ";
    }else{
    
    $result['error'] = true;
        $result['msg'] .= "->ERROR selecting proyects";
    }
	
	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
