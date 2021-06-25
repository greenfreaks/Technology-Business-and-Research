<?php
    //echo "entro";
    require_once("../../conex.php");

    function rndPass($length, $characters){
        $symbols = array(); 
        $passwords = array(); 
        $used_symbols ="";
        $pass = "";

        $symbols["lc"] = "abcdefghijklmnopqrstuvwxyz";
        $symbols["uc"] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $symbols["n"] = "1234567890";
        $symbols["ss"] = "!?~#%<>";

        $characters = explode(",", $characters);

        foreach ($characters as $key => $value) {
           $used_symbols .= $symbols[$value];
        }

        $symbols_length = strlen($used_symbols)-1;

           for ($j=0; $j < $length; $j++) { 
               $n = rand (0, $symbols_length);
               $pass .= $used_symbols[$n];
           }
       return $pass;
   }

//=============result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
//=============Get Variables===============

    $query_verified = "SELECT `idtrl_registro`, `first_name`, `last_name_p`, `last_name_m`, `email` FROM `trl_registro` WHERE `verified`='1';";
    //echo $query_verified;
    $exec_query_verified = $conex->query($query_verified );
    
    if($exec_query_verified->num_rows > 0){

    	while ($row_verified = $exec_query_verified->fetch_assoc() ) {
    		$result['msg'] .= "->data verified selected ";
            $genPass = rndPass(10, "lc,uc,ss,n");
            $email = $row_verified['email'];
            $nombre = $row_verified['first_name']." ".$row_verified['last_name_p']." ".$row_verified['last_name_p'];
            $id= $row_verified['idtrl_registro'];
            
            $query_register = "INSERT INTO `trl_login`( `email`, `pass`) VALUES ('{$email}',AES_ENCRYPT('{$genPass}','{$variable}'));";
            //echo $query_register;
            $exec_query_register = $conex->query($query_register);
        
            if($exec_query_register === TRUE)
            {
                $last_id_register = $conex->insert_id; 
                $result['msg'] .= "-> user created for {$email} ";

                //==============Inicio de envio de correo
                $to = $email;
                $from = 'contacto@techbusiness.com.mx';
                $subject = "Confirmación de registro plataforma TRL";
                $message = "
                <html>

                <head>
                    <title>Confirmación de registro plataforma TRL</title>
                </head>

                <body>
                    <div><span style='font-size: 20pt;'><strong>Confirmaci&oacute;n de registro plataforma TRL</strong></span></div>
                    <br>
                    <div><span style='font-size: 14pt;'>Saludos {$nombre} , bienvenido a la plataforma de evaluacion del TRL, tu informaci&oacute;n de inicio de sesion es:</span></div>
                    <div>&nbsp;</div>
                    <div style='text-align: center;'>
                        <table style='width: 100%; border-collapse: collapse;' border='0'>
                            <tbody>
                                <tr>
                                    <td style='width: 50%; text-align: right;'>Email:</td>
                                    <td style='width: 50%;'>{$email}</td>
                                </tr>
                                <tr>
                                    <td style='width: 50%; text-align: right;'>Contrase&ntilde;a</td>
                                    <td style='width: 50%;'>{$genPass}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style='text-align: left;'><span style='font-size: 14pt;'>Para ingresar a la plataforma ingresa al siguiente link:</span></p>
                        <p style='text-align: center;'><a class='button' style='background-color: #27699d; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;' href='http://techbusiness.com.mx/modulos/plataforma/trl.html' target='_blank'>Login</a></p>
                        <div>&nbsp;</div>
                    </div>
                </body>

                </html>
                ";
                // Always set content-type when sending HTML email add ."\r\n" to each header
                $headers = "MIME-Version: 1.0"."\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8";
                // More headers
                $headers .= "From: Contacto<{$from}>";
                if(@mail($to,$subject,$message,$headers))
                {
                    //echo 'Mail Sent Successfully';
                    $result['msg'] .= "->email sent to {$email}";
                    $query_update = "UPDATE `verified`=2 WHERE `idtrl_registro`= {$id};";
                    //echo $query_update;
                    $exec_query_update = $conex->query($query_update);
                }else{
                    //echo 'Mail Not Sent';
                    $result['error'] = true;
                    $result['msg'] .= "->ERROR email not sent to {$email}";
                }
                //==============Fin envio de correo
        
            }
            else
            {
                $result['error'] = true;
                $result['msg'] .= "->ERROR inserting register";
            }  
            
    	}
    	
    }else{
    
    $result['error'] = true;
        $result['msg'] .= "->ERROR selecting verified";
    }

	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>