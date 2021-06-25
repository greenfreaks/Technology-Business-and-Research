<?php
	require_once("../conex.php");
//=============Get Variables===============

    $result = array();
    $result['error'] = false;
    $result['msg'] = "INICIO";
	
	$email = isset($_POST["mail"]) ? $conex->real_escape_string ($_POST["mail"] ) : null;
    
    $query_mail = ";";
    //echo $query_mail;
    $exec_query_mail = $conex->query($query_mail);
    
    if($exec_query_mail === TRUE)
    {
        $last_id_mail = $conex->insert_id; 
        $result['msg'] .= "->data inserted on {$last_id_mail} ";
    
    }
    else
    {
        $result['error'] = true;
        $result['msg'] .= "->ERROR inserting mail";
    } 
	
	echo json_encode($result);

	$conex->close();

?>