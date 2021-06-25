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
    $idcalendario= isset($_POST['idcalendario']) ? $conex->real_escape_string ($_POST['idcalendario'] ) : 'idcalendario no proporcionado';


    //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
    
    $query_registro = "INSERT INTO `registro_taller`( `nombre`, `cargo`, `telefono`, `email`, `sector`, `organizacion`,  `calendario_idcalendario`) VALUES ('{$nombre}','{$cargo}','{$tel}','{$mail}','{$sector}','{$organizacion}','{$idcalendario}');";

    $exec_query_registro = $conex->query($query_registro );
    
    if($exec_query_registro === TRUE)
    {
        $last_id_registro = $conex->insert_id; 
        $result['msg'] .= "->data inserted on {$last_id_registro} ";
        $result['inscrito'] = $last_id_registro;
        
        //$ejemplo = isset($_GET["ejemplo"]) ? $conex->real_escape_string ($_GET["ejemplo"] ) : "sin ejemplo";
        
        $query_datos_taller = "SELECT `idcalendario`, `createdAt`, `evento`, `lugar`, `fecha_inicio`, `fecha_fin`, `link_info`, `impartido_por`, `sede`, `descripcion`, `publico_objetivo`, `precio`, `link_pagar` FROM `calendario` WHERE `idcalendario`={$idcalendario};";
        //echo $query_datos_taller;
        $exec_query_datos_taller = $conex->query($query_datos_taller );
        
        if($exec_query_datos_taller->num_rows==1){
            
        	$datos_taller = $exec_query_datos_taller->fetch_assoc();

            $to = $mail;
            $subject = "Confirmación de registro - {$datos_taller['evento']}";

            $message = "
            <html>

            <body>

                <div class='section'>
                    <div class='container'>
                        <div class='row'>
                            <div class='col s12'>
                                <h1>Registro Correcto</h1>
                                <h2>Saludos {$nombre}</h2>
                                <p> Por medio de este correo le confirmamos que hemos recibido sus datos y confirmamos tu registro para el taller: <strong>{$datos_taller['evento']}</strong> </p>
                                <p> El siguiente paso es hacer el pago correspondiente a la inscripción a su pago. </p>
                                <p>Puede hacer el pago en linea haciendo clic en el siguiente botón:</p>
                                <a style='background-color: #27699D;
                    border: none;
                    color: white;
                    padding: 15px 32px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;' target='_blank' href='{$datos_taller['link_pagar']}' class='button'>Pagar</a>
                                <p style='display: block;
                                          width: 178px;
                                          height: 23px;
                                          background: url(https://secure.mlstatic.com/mptools/assets/MP-payButton-logos-mex.png) 0px -29px;
                                          background-position: 0px -29px;'>
                            </div>
                            <div class='col s12'>
                                <p>Si desea hacer su pago mediante un deposito bancario deberá hacerlo en la siguiente cuenta</p>
                                <table style=''>
                                    <tr>
                                        <td colspan='2'><strong>TRANSFERENCIA ELECTRÓNICA</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Clabe Interbancaria:</strong></td>
                                        <td>036 180 500 358 895 953</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. de Cuenta:</strong></td>
                                        <td>50035889595</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Referencia/Descripción:</strong></td>
                                        <td>Taller{$idcalendario}-{$last_id_registro}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Banco:</strong></td>
                                        <td>INBURSA</td>
                                    </tr>
                                </table>
                                <br>
                                <p>O de igual manera puede hacer su pago en tiendas OXXO</p>
                                <table>
                                    <tr>
                                        <td colspan='2'><strong>Deposito en tienda OXXO</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. de Tarjeta:</strong></td>
                                        <td>4004-4305-0006-9486</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Banco:</strong></td>
                                        <td>INBURSA</td>
                                    </tr>
                                </table>
                                <h3>Por favor, envíenos su comprobante de pago para validar el depósito al correo <a href='mailto:contacto@techbr.com.mx?subject=pago Taller {$idcalendario}-{$last_id_registro}&body=Buenas tardes, le envió mi comprobante de pago referencia Taller{$idcalendario}-{$last_id_registro}'>contacto@techbr.com.mx</a> o vía whatsapp al <a href='https://api.whatsapp.com/send?phone=527797966790&text=Buenas tardes, le envio mi comprobante de pago referencia Taller{$idcalendario}-{$last_id_registro}' target='_blank'>779796790</a></h3>
                                <p>Si requiere factura, envíenos por favor los siguientes datos:</p>
                                <ol>
                                    <li>Razón Social</li>
                                    <li>RFC</li>
                                    <li>Domicilio</li>
                                    <li>Uso de CFDI</li>
                                </ol>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <img src='http://www.techbusiness.com.mx/php/mailing/email_read.php?email={$mail}&nombre={$nombre}&asunto=$subject' style='width:1px;height:1px'>
            </body>

            </html>

            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: Contacto TB&R <contacto@techbusiness.com.mx>' . "\r\n";
            $headers .= 'Bcc: contacto@techbr.com.mx' . "\r\n";

//            $result['email'] = $message;
            
            mail($to,$subject,$message,$headers);
        }
        
       

    }
    else
    {
        $result['error'] = true;
        $result['msg'] .= "->ERROR insertando registro";
        $result['msg'] .= "->Query: {$query_registro}";
        
    } 

	echo json_encode($result);

	$conex->close();

?>
