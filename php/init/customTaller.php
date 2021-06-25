<?php
    //echo "entro";
	require_once("../conex.php");
//=============Get Variables===============
// scape values 
//	$ejemplo = isset($_POST["ejemplo"]) ? $conex->real_escape_string ($_POST["ejemplo"] ) : "sin ejemplo";

    $cargo = isset($_POST['cargo']) ? $conex->real_escape_string ($_POST['cargo'] ) : 'cargo no definido';
    $duracion = isset($_POST['duracion']) ? $conex->real_escape_string ($_POST['duracion'] ) : 'duracion no definido';
    $mail = isset($_POST['mail']) ? $conex->real_escape_string ($_POST['mail'] ) : 'mail no definido';
    $name = isset($_POST['name']) ? $conex->real_escape_string ($_POST['name'] ) : 'name no definido';
    $organizacion = isset($_POST['organizacion']) ? $conex->real_escape_string ($_POST['organizacion'] ) : 'organizacion no definido';
    $sector = isset($_POST['sector']) ? $conex->real_escape_string ($_POST['sector'] ) : 'sector no definido';
    $tel = isset($_POST['tel']) ? $conex->real_escape_string ($_POST['tel'] ) : 'tel no definido';
    $tos = isset($_POST['tos']) ? $conex->real_escape_string ($_POST['tos'] ) : 'tos no definido';
    $boletin = isset($_POST['boletin']) ? $conex->real_escape_string ($_POST['boletin'] ) : 'boletin no definido';

    $asistentes = isset($_POST['asistentes']) ? $_POST['asistentes']: array();
    $temas = isset($_POST['temas']) ? $_POST['temas']: array();

//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
    $to = "leocasdeveloper@gmail.com";
    $subject = "El cliente {$name} requiere de un taller personalizado";
    $txt = "La persona llamada {$name} con los siguientes datos:\r\n
            nombre: {$name}\r\n
            con el cargo de: {$cargo}\r\n
            de la organizacion: {$organizacion}\r\n
            del sector {$sector}\r\n
            \r\n
            Solicita un taller con las siguientes caracteristicas:\r\n
            duracion: {$duracion}\r\n
            para asistentes con cargos de:
            ";

    foreach ($asistentes as $asistente) {
        $txt.="{$asistente}\r\n";
    }

    $txt.="\r\nY que aborde los siguientes temas:\r\n";

    foreach ($temas as $tema) {
        $txt.="{$tema}\r\n";
    }

    $txt.="\r\nPor favor genere una propuesta y comuniqueselo a la brevedad al cliente {$name} en su correo {$mail} o de lo contrario comuniquese al telefono {$tel}";

    $headers = "From: ventas@techbr.com.mx" . "\r\n" .
    "CC: aruiz@techbr.com.mx";
    
    if (@mail($to,$subject,$txt,$headers))
    {
        $result['msg'] .= "->correo enviado";
    }
    else{
        $result['error'] = true;
	   $result['msg'] .= "->ocurrio un error al enviar el correo";
    }

	echo json_encode($result);

	$conex->close();

?>