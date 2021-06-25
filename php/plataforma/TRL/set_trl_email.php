<?php
    //echo "entro";
	require_once("../../conex.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../composer/vendor/autoload.php';

    $mail = new PHPMailer(TRUE);

//=============result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
//=============Get Variables===============

	$nombre_usuario = isset($_POST["name"]) ? $conex->real_escape_string ($_POST["name"]) : false;
	$user_email = isset($_POST["mail"]) ? $conex->real_escape_string ($_POST["mail"]) : false;
    $projectID = isset($_POST["projectID"]) ? $conex->real_escape_string ($_POST["projectID"]) : false;

    if($nombre_usuario and $user_email and $projectID){
        
        //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
        
        $query_insert_email = "UPDATE `trl_usuarios` SET llave= AES_ENCRYPT('{$projectID}','{$user_email}'), `email`='{$user_email}' WHERE `idusuarioTRL`='{$projectID}';";
        //echo $query_insert_email;
        $exec_query_insert_email = $conex->query($query_insert_email);
        
        if($exec_query_insert_email === TRUE)
        {
            $result['msg'] .= "->data updated for {$projectID}";
			
	$query_get_email = "SELECT HEX(AES_ENCRYPT('{$projectID}','{$user_email}')) as llave"; 
	$result_query_get_email =$conex->query($query_get_email);
	while($row = $result_query_get_email->fetch_assoc())
	{
		$codigo = $row['llave'];
	}	
//======================================================================================================
        
        try {
           $mail->setFrom('trl@tecnotransfer.com.mx', 'Technology Business & Research');
           $mail->addAddress($user_email, $nombre_usuario);
           $mail->Subject = 'Reporte de madurez tecnologica';
            
           $mail->isHTML(TRUE);
			$enlace="http://www.techbusiness.com.mx/php/plataforma/TRL/get_trl_report.php?key={$codigo}&email={$user_email}";
            $mail->Body = "<html><body><div class='section>
							<div class='container'>
							<div class='row'>
							<div class='col s12'>
							<h1><span style='font-family: 'book antiqua', palatino;'>Estimado Usuario</span></h1>
							<p style='text-align: justify;'><span style='font-family: verdana, geneva; font-size: 12pt;'>Su proyecto ha sido evaluado mediante la escala Technology Readiness Level y se le ha asignado un nivel de madurez.</span></p>
							<p style='text-align: justify;'><span style='font-family: verdana, geneva;'><span style='font-size: 12pt;'>Si desea conocer m&aacute;s detalles sobre el an&aacute;lisis realizado, lo invitamos a descargar el reporte TRL de su proyecto; en donde adem&aacute;s de dar una perspectiva general de la metodolog&iacute;a, se explican de manera gr&aacute;fica las ponderaciones que dan lugar al nivel obtenido, as&iacute; como un plan de acci&oacute;n para obtener niveles superiores</span>.</span></p>
							<p>&nbsp;</p>
							<p style='text-align: center;'><a class='button' style='background-color: #27699d; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;' href='{$enlace}' target='_blank'>Reporte TRL by TB&amp;R</a></p>
							<p>&nbsp;</p>
							<p><span style='font-size: 12pt;'>S&oacute;lo cuenta con un m&aacute;ximo de 5 intentos para acceder al reporte en la plataforma por lo que se recomienda descargar el documento para no perder la evidencia que sustenta su evaluaci&oacute;n.</span></p>
							<h3 style='text-align: justify;'><em><strong>Si tiene alguna duda o comentario comp&aacute;rtalo  con nuestro equipo al correo <a href='mailto:contacto@techbr.com.mx?subject=reporte TRL&amp;body=Buenas tardes, tengo dudas o comentarios sobre mi reporte TRL'>contacto@techbr.com.mx</a> o v&iacute;a whatsapp al <a href='https://api.whatsapp.com/send?phone=527797966790&amp;text='Buenas tardes, tengo dudas o comentarios sobre mi reporte TRL' target='_blank'>779796790</a></strong></em></h3>
							<p>&nbsp;</p>
							<p style='text-align: center;'>Atentamente</p>
							<p style='text-align: center;'><span style='font-family: impact, chicago; font-size: 16pt;'>Equipo TB&amp;R</span></p>
							</div>
							<div class='col s12'>
							<p>&nbsp;</p>
							</div>
							</div>
							</div>
							</div>
							<p>&nbsp;</p>
							</body></html>";
            //$mail->AltBody = 'There is a great disturbance in the Force.';

           $mail->send();
        }
        catch (Exception $e)
        {
           /* PHPMailer exception. */
            $result['error'] = true;
           $result['msg'] .= "->ERROR: {$e->errorMessage()}";
        }
        catch (\Exception $e)
        {
           /* PHP exception (note the backslash to select the global namespace Exception class). */
            $result['error'] = true;
           $result['msg'] .= "->ERROR: {$e->getMessage()}";
        }
                        
//======================================================================================================
        
        }
        else
        {
            $result['error'] = true;
            $result['msg'] .= "->ERROR: updating email";
        } 
        
    }
    else{
        
       $result['error'] = false;
	   $result['msg'] .= "->ERROR: Missing variable (n: {$nombre_usuario}/ em: {$user_email}/ pi: {$projectID})";

    }

	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
