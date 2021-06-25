<?php
    //echo "entro";
	require_once("../../../conex.php");
//    print_r($_POST);

    $result = array();
	$result['error'] = false;
	$result['msg'] = "INICIO";
    
    $radio_contribucion_cc = isset($_POST['radio_contribucion_cc']) ? $conex->real_escape_string ($_POST['radio_contribucion_cc'] ) : '0';
    $radio_patente = isset($_POST['radio_patente']) ? $conex->real_escape_string ($_POST['radio_patente'] ) : '0';
    $radio_estudio_mercado = isset($_POST['radio_estudio_mercado']) ? $conex->real_escape_string ($_POST['radio_estudio_mercado'] ) : '0';
    $radio_fuentes_em = isset($_POST['radio_fuentes_em']) ? $conex->real_escape_string ($_POST['radio_fuentes_em'] ) : '0';
    $radio_viable = isset($_POST['radio_viable']) ? $conex->real_escape_string ($_POST['radio_viable'] ) : '0';
    $radio_EstudioCompetitividad = isset($_POST['radio_EstudioCompetitividad']) ? $conex->real_escape_string ($_POST['radio_EstudioCompetitividad'] ) : '0';
    $radio_InfoCompetitividad = isset($_POST['radio_InfoCompetitividad']) ? $conex->real_escape_string ($_POST['radio_InfoCompetitividad'] ) : '0';

    $campo = isset($_POST['campo']) ? $conex->real_escape_string ($_POST['campo'] ) : null ;
    $objetivos_onu = isset($_POST['objetivos_onu']) ? $_POST['objetivos_onu'] : array();
    $sector_industrial = isset($_POST['sector_industrial']) ? $_POST['sector_industrial']  : array();

    $email = isset($_POST['email']) ? $conex->real_escape_string ($_POST['email'] ) : 'sin correo';



	$result_json = json_encode($result);
	echo $result_json;

	$conex->close();

?>
