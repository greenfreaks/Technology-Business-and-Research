<?php
    //echo "entro";
	require_once("../../conex.php");

//=============result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
//=============Get Variables===============

    $fname = isset($_POST['fname']) ? $conex->real_escape_string ($_POST['fname'] ) : false;
    $lnameP = isset($_POST['lnameP']) ? $conex->real_escape_string ($_POST['lnameP'] ) : false;
    $lnameM = isset($_POST['lnameM']) ? $conex->real_escape_string ($_POST['lnameM'] ) : false;
    $email = isset($_POST['email']) ? $conex->real_escape_string ($_POST['email'] ) : false;
    $estudios = isset($_POST['estudios']) ? $conex->real_escape_string ($_POST['estudios'] ) : false;
    $proyecto = isset($_POST['proyecto']) ? $conex->real_escape_string ($_POST['proyecto'] ) : false;
    $rol = isset($_POST['rol']) ? $conex->real_escape_string ($_POST['rol'] ) : false;
    $equipo = isset($_POST['equipo']) ? $conex->real_escape_string ($_POST['equipo'] ) : false;

    if($fname and $lnameP and $lnameM and $email and $estudios and $proyecto and $rol  and $equipo){
            
        //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
        
        $query_duplicado = "SELECT `idtrl_registro` FROM `trl_registro` WHERE `email`='{$email}';";
        //echo $query_duplicado;
        $exec_query_duplicado = $conex->query($query_duplicado );
        
        if($exec_query_duplicado->num_rows > 0){
            $result['error'] = true;
            $result['msg'] .= "->ERROR email duplicado";
            $result["duplicado"] = true;
        }else{
            
            $query_insert_registro = "INSERT INTO `trl_registro`( `first_name`, `last_name_p`, `last_name_m`, `email`, `study_level`, `project_name`, `research_role`,`team_number`) VALUES ('{$fname}','{$lnameP}','{$lnameM}','{$email}','{$estudios}','{$proyecto}','{$rol}','{$equipo}');";
            //echo $query_insert_registro;
            $exec_query_insert_registro = $conex->query($query_insert_registro);

            if($exec_query_insert_registro === TRUE)
            {
                $last_id_insert_registro = $conex->insert_id; 
                $result['msg'] .= "->data inserted on {$last_id_insert_registro} ";

            }
            else
            {
                $result['error'] = true;
                $result['msg'] .= "->ERROR inserting insert_registro";
            } 
        }

    }
    else{
        
       $result['error'] = true;
	   $result['msg'] .= "->ERROR: Missing variable (n: {$fname}/ LP: {$lnameP}/ LM: {$lnameM}/ em: {$email}/ st: {$estudios}/ pr: {$proyecto}/ rl: {$rol}/ eq: {$equipo})";

    }

	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
