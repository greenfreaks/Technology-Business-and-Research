<?php

set_time_limit(300);
    //echo "entro";
//	require_once("../conex.php");
//    print_r($_POST);
//=============Get Variables===============


//result object instancing
    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";

    class Destinatario
    {
        public $nombre;
        public $email;

        public function __construct($n, $e) {

            $this->nombre= $n;
            $this->email= $e;

        }
    }
    
    $destinatarios = array();

//==================================DESTINATARIOS=============================================


//======================================================================================================
//CORREOS DE CONTROL
    //array_push($destinatarios,new Destinatario('Claudia','klawmx@hotmail.com'));
//    array_push($destinatarios,new Destinatario('Lorena','blorena1402@gmail.com'));
//    array_push($destinatarios,new Destinatario('Alejandro','aruiz@techbr.com.mx'));
//    array_push($destinatarios,new Destinatario('Adrian','adrian.carrillo@gmail.com'));
array_push($destinatarios,new Destinatario('Control-CITNOVA','leocasdeveloper@gmail.com'));


//==================================Mensaje=============================================

$subject = 'Taller Programa de Estímulos Fiscales a la I+DT 2019 de CONACYT.';
        
            $message = "
                <html>

                <head>
                    <title>{$subject}</title>
                </head>

                <body>

                    <div>Estimado empresario:</div>
                    <div>&nbsp;</div>
                    <div>CITNOVA y TB&amp;R le invitan a asistir al&nbsp;<strong>Taller de Est&iacute;mulos Fiscales a la I+DT (EFIDT) 2019</strong>, el pr&oacute;ximo&nbsp;<strong>16 de abril&nbsp;</strong>en las instalaciones del Parque Cient&iacute;fico y Tecnol&oacute;gico de Hidalgo (CITNOVA) en un horario de&nbsp;<strong><span style='color: #000000;'>10&nbsp;a 14 h.</span></strong></div>
                    <div>
                    <div>&nbsp;</div>
                    <div>
                    <div>
                    <div><span style='color: #000000; font-family: verdana, sans-serif;'><strong>&iexcl;Aproveche los beneficios que le brinda el primer programa lanzado por la nueva administraci&oacute;n del CONACYT para estimular la innovaci&oacute;n en las empresas, en donde podr&aacute; obtener un cr&eacute;dito fiscal de hasta $50,000,000.00!</strong></span></div>
                    </div>
                    <div>&nbsp;</div>
                    &nbsp;&nbsp;</div>
                    </div>
                    <div>
                    <div><strong><span style='color: #000000; font-family: verdana, sans-serif;'>Beneficios del taller:</span></strong></div>
                    <div>
                    <ol>
                    <li><span style='color: #000000; font-family: verdana, sans-serif;'>Podr&aacute; conocer las bases de operaci&oacute;n de programa, as&iacute; como los requisitos y el proceso de participaci&oacute;n.</span></li>
                    <li><span style='color: #000000; font-family: verdana, sans-serif;'>Sabr&aacute; realizar la estimaci&oacute;n del monto del cr&eacute;dito fiscal que su empresa podr&iacute;a obtener por sus inversiones en I+D.<br /></span></li>
                    <li><span lang='ES'><span style='color: #000000; font-family: verdana, sans-serif;'>Obtendr&aacute; las recomendaciones de dise&ntilde;o de proyectos que han permitido a otras empresas obtener est&iacute;mulos fiscales por m&aacute;s de 50 mdp en las ediciones anteriores del Programa EFIDT.</span></span></li>
                    </ol>
                    </div>
                    <div>
                    <div dir='ltr'>
                    <div><span style='font-size: large;'><strong><span style='color: #990000;'>Realice&nbsp;</span><a href='http://techbusiness.com.mx/modulos/catalogo/talleres/EFIDT_2019_CITNOVA.html' target='_blank' rel='noreferrer' data-saferedirecturl='https://www.google.com/url?q=http://techbusiness.com.mx/modulos/catalogo/talleres/EFIDT_2019_CITNOVA.html&amp;source=gmail&amp;ust=1554932109573000&amp;usg=AFQjCNFUCZDdqumUQkxSHK0C9vdK1VDxDQ'><span style='color: #0000ff;'>aqu&iacute;</span></a><span style='color: #990000;'>&nbsp;su registro para obtener el SUBSIDIO DEL 75%&nbsp; por parte del CITNOVA y pague solo&nbsp;</span><span style='color: #0000ff;'>$500.00,&nbsp;</span><span style='color: #990000;'>por cuota de recuperaci&oacute;n.</span></strong></span></div>
                    <div>&nbsp;</div>
                    <div><span style='font-family: verdana, sans-serif;'>Para mayor informaci&oacute;n, le podremos resolver sus dudas v&iacute;a telef&oacute;nica, whatsapp o correo electr&oacute;nico:</span></div>
                    <div><span style='font-family: verdana, sans-serif;'>&nbsp;</span></div>
                    <div><strong><span style='color: #000000; font-family: verdana, sans-serif;'>TB&amp;R</span></strong></div>
                    <div><span style='color: #000000; font-family: verdana, sans-serif;'>Claudia Ramos -&nbsp;<a href='mailto:proyectos@techbr.com.mx' target='_blank' rel='noreferrer'>proyectos@techbr.com.mx</a></span></div>
                    <div><span style='color: #000000; font-family: verdana, sans-serif;'>771 712 0961</span></div>
                    <div><strong><span style='color: #000000; font-family: verdana, sans-serif;'>&nbsp;</span></strong></div>
                    </div>
                    <div>
                    <div><strong><span style='font-family: verdana, sans-serif;'>CITNOVA</span></strong></div>
                    <div><span style='font-family: verdana, sans-serif;'>Victor Leonel P&eacute;rez -&nbsp;<a href='mailto:leonel.perez@hidalgo.gob.mx' target='_blank' rel='noreferrer'>leonel.perez@hidalgo.gob.mx</a></span></div>
                    <div><span style='font-family: verdana, sans-serif;'>771&nbsp;778 0806</span></div>
                    <div><span style='font-family: verdana, sans-serif;'>&nbsp;</span></div>
                    <div>&iexcl;Gracias y que tenga un excelente d&iacute;a!</div>
                    </div>
                    </div>
                    </div>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: Contacto TB&R <contacto@techbusiness.com.mx>' . "\r\n";
//            $headers .= 'Bcc: contacto@techbr.com.mx' . "\r\n";
            $headers .= 'Disposition-Notification-To: contacto@techbusiness.com.mx' . "\r\n";

//            $result['email'] = $message;

//==================================Fin Mensaje=============================================

    foreach ($destinatarios as $d) {
//        echo 'This car is a ' . $d->nombre . ' ' . $d->email . "\n";
            $to = $d->email;
        
            $read_link = $message."<img src='http://www.techbusiness.com.mx/php/mailing/email_read.php?email=$d->email&nombre=$d->nombre&asunto=$subject' style='width:1px;height:1px'>
                </body>

                </html>
            ";

            if (!@mail($to,$subject,$read_link,$headers)) {
               // Reschedule for later try or panic appropriately!
                $result['error'] = true;
                $result['msg'] .= "->ERROR al enviar correo para $d->nombre ({$d->email})";
            }
            else{
                $result['msg'] .= "->Correo enviado a $d->nombre ({$d->email})";
            }
        
    }


            

	echo json_encode($result);

//	$conex->close();

?>
