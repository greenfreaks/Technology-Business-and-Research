<?php
    //echo "entro";
	require_once("../conex.php");
//    print_r($_POST);
//=============Get Variables===============


//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";

    $nombre= isset($_POST['name']) ? $conex->real_escape_string ($_POST['name'] ) : 'nombre no proporcionado';
    $cargo = isset($_POST['cargo']) ? $conex->real_escape_string ($_POST['cargo'] ) : 'cargo  no proporcionado';
    $tel= isset($_POST['tel']) ? $conex->real_escape_string ($_POST['tel'] ) : 'tel no proporcionado';
    $mail= isset($_POST['mail']) ? $conex->real_escape_string ($_POST['mail'] ) : 'mail no proporcionado';
    $sector= isset($_POST['sector']) ? $conex->real_escape_string ($_POST['sector'] ) : 'sector no proporcionado';
    $organizacion= isset($_POST['organizacion']) ? $conex->real_escape_string ($_POST['organizacion'] ) : 'organizacion no proporcionado';
    $tos= isset($_POST['tos']) ? $conex->real_escape_string ($_POST['tos'] ) : 'tos no proporcionado';
    $boletin= isset($_POST['boletin']) ? $conex->real_escape_string ($_POST['boletin'] ) : 'boletin no proporcionado';
    $servicio= isset($_POST['servicio']) ? $conex->real_escape_string ($_POST['servicio'] ) : 'servicio no proporcionado';


        $to = "aruiz@techbr.com.mx";
        $subject = "Solicitud de Servicio";

            $message = "
            <html>

            <body>
            
                Hay una persona interesada en los servicios de {$servicio}
                
                <p><strong>Nombre:</strong>{$nombre}</p>
                <p><strong>Cargo:</strong>{$cargo}</p>
                <p><strong>Telefono:</strong>{$tel}</p>
                <p><strong>Mail:</strong>{$mail}</p>
                <p><strong>Sector:</strong>{$sector}</p>
                <p><strong>Organizacion:</strong>{$organizacion}</p>

            </body>

            </html>

            ";

//            echo $message;

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: Contacto TB&R <contacto@techbusiness.com.mx>' . "\r\n";
            $headers .= 'Bcc: contacto@techbr.com.mx' . "\r\n";

//            $result['email'] = $message;
        if (@mail($to,$subject,$message,$headers))
        {
            
            $result['msg'] .= "->registro de datos correcto";
        }
        else{
            $result['error'] = true;
           $result['msg'] .= "->ocurrio un error al enviar el correo";
        }
                    

	echo json_encode($result);

	$conex->close();

?>
