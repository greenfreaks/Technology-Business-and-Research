<?php
    //echo "entro";
	require_once("../conex.php");
//    print_r($_POST);
//=============Get Variables===============
    $nombre= isset($_GET['nombre']) ? $conex->real_escape_string ($_GET['nombre'] ) : 'nombre no proporcionado';
    $email = isset($_GET['email']) ? $conex->real_escape_string ($_GET['email']) : 'email  no proporcionado';
    $asunto= isset($_GET['asunto']) ? $conex->real_escape_string ($_GET['asunto'] ) : 'asunto no proporcionado';

    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_read = "SELECT `idemail_log` FROM `email_log` WHERE `nombre`='{$nombre}' AND `email`='{$email}' AND `asunto`='{$asunto}';";
    //echo $query_read;
    $exec_query_read = $conex->query($query_read );
    
    if($exec_query_read->num_rows==1){
    	$row= $exec_query_read->fetch_assoc();
            
        $query_autoemail = "UPDATE `email_log` SET `timesRead`=`timesRead`+1 WHERE `nombre`='{$nombre}' AND `email`='{$email}' AND `asunto`='{$asunto}';";
        //echo $query_autoemail;
        $exec_query_autoemail = $conex->query($query_autoemail);
        
    }else{
    	
    	$query_autoemail = "INSERT INTO `email_log`( `nombre`, `email`, `asunto`) VALUES ('{$nombre}','{$email}','{$asunto}');";
        //echo $query_autoemail;
        $exec_query_autoemail = $conex->query($query_autoemail);
    }

    

	$conex->close();

?>
