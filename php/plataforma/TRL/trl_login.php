<?php
    //echo "entro";
	require_once("../../conex.php");
	
//print_r($_POST);
//=============Get Variables===============

	$email = isset($_POST["mail"]) ? $conex->real_escape_string ($_POST["mail"]) : "proyecto anonimo";
	$pass = isset($_POST["pass"]) ? $conex->real_escape_string ($_POST["pass"]) : "1";


//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";

    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_id = "SELECT `id_trl_login` as 'id' FROM `trl_login` WHERE `email`= '{$email}' AND `pass`= AES_ENCRYPT('{$pass}','{$variable}');";
    //echo $query_id;
    $exec_query_id = $conex->query($query_id);
    
    if($exec_query_id->num_rows==1){
        $result['data']= $exec_query_id->fetch_assoc();
        $result['msg'] .= "->data id selected ";
        
        $query_update ="UPDATE `trl_login` SET `logins`=`logins`+1 WHERE `id_trl_login`= '{$result['data']['id']}';";
        $exec_query_update = $conex->query($query_update);
        
        if(mysqli_affected_rows($conex) >0){
            $result['msg'] .= "->login recorded ";
        } 
    }else{
    
        $result['error'] = true;
        $result['msg'] .= "->ERROR selecting id {$query_id}";
    }
 
	
	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
