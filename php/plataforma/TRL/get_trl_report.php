<?php
//archivos requeridos
include 'fpdf/plantilla.php';//encabezado y pie de pagina del pdf
require '../../conex.php'; // conexion con BD
require_once ('jpgraph/src/jpgraph.php'); //  generar gráficas
require_once ('jpgraph/src/jpgraph_line.php');// generar gráficas de lineas
require_once ('jpgraph/src/jpgraph_bar.php'); // generar gráficas de barras



//constantes
define("NCERO",0);
define("ANCHORENGLON",6); // ancho estandar para las celdas del pfd

//dimensiones


//datos entrada
$codigo = $_GET["key"]; //identificador encriptado
$llave =  $_GET["email"]; // llave para desencriptar el identificador

//se obtiene el usuario del que se debe generar el reporte
	$queryE = "SELECT AES_DECRYPT(UNHEX('{$codigo}'),'{$llave}') as id"; 
	$resultadoE =$conex->query($queryE);
	while($rowE = $resultadoE->fetch_assoc())
	{
		$usuario = $rowE['id'];
	}		


////////////////////////////////consultas a la BD - previas a la generación del reporte////////////////////////////////////////////////////////////////////////////
//consulta para saber si se esta solicitando un reporte valido
$query6 = "SELECT * FROM trl_usuarios WHERE idusuarioTRL={$usuario};";

$resultado6 =$conex->query($query6);
$filasCon = mysqli_num_rows($resultado6);

//consulta para obtener el número actual de generaciones del reporte
$query7 = "SELECT rep_leido FROM trl_usuarios WHERE idusuarioTRL={$usuario};"; 
$resultado7 =$conex->query($query7);
while($row7 = $resultado7->fetch_assoc())
{
$num_rep_gener = $row7['rep_leido'];
}

//comprueba si el reporte solicitado existe
if($filasCon>0){
	//comprueba si el reporte solicitado se ha generado menos de 5 veces----------------------------------------------------------------------------------------------------------
	if($num_rep_gener<5){
		
// variables
$indiceNiveles = 0; // hace referencia a una posicion del arreglo $descricionNivels
$nivelAlcanzado = 0; // indica que ningun nivel a sido alcanzado
$nivelActual = 0; // indica el nivel para el que se estan imprimiendo las recomendaciones
$numTablas = 1; // indica en número de las tablas
$numGraficas = 1;// indica en número de las graficas
$numFiguras = 1; // indica en número de las figuras
$aspectosTotalesNivel = array(); // arreglo para guardar el número total de aspectos por nivel
$nivelesConPocoAvance = array(); // arreglo para guardar el avance de los niveles, cuando este es menor al 50%
$numNivelesPocoAvance = 0; // numero de niveles con poco avance 
$posicionNivelActual = 0; // guarda la posicion en la grafica cumplimiento total de un nivel especifico 
$porcentajeNivelAlzanzado = 0; // porcetnaje de avance del nivel obtenido
$numCursosBasicos = 2; // numero de cursos obrecido a proyectos sin nigun nivel TRL
$numSeviciosBasicos = 2;  // numero de servicos obrecido a proyectos sin nigun nivel TRL
$numMaximoCS = 5; // son el numero maximo de servicos o cursos que se van a ofertar en el reporte.
$apareceCS = array(); // establece si un curso / servicos se mostrara o no.

//variables para gráficas
$datos = array(); // arreglo para guardar los datos de las graficas
$datosX =  array(0,1,2,3,4,5,6,7,8,8,7.5); // arreglo para guardar los datos en X de la grafica total de cumplimiento
$totalAspectosTRL = 0; //contador numero de preguntas del TRL
$posNivelesMadurez = array(0,0.5,1,2,3.5,5,6,7.5,9.5); // posicion  (eje x) de los niveles para la gráfica del ciclo de vida
$datos21=[0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5,5.5,6,6.5,7,7.5,8,8.5,9,9.5,10]; // valores en x para la grafica de ciclo de vida
$datos22=array(); // valores en y para la grafica del ciclo de vida

//Textos para la descripciónn detallada de los niveles
$descricionNivels = array("Es el nivel más bajo de madurez tecnológica. La investigación científica comienza a traducirse en I + D aplicada. Por ejemplo, puede incluir estudios en papel de las propiedades básicas de una tecnología o trabajo experimental que consiste principalmente en observaciones del mundo físico, así como publicaciones u otras referencias que identifican los principios de la tecnología.",
						"Una vez observados los principios básicos, se pueden inventar aplicaciones prácticas. Las aplicaciones son especulativas y puede que no haya pruebas o análisis detallados que respalden las suposiciones. Todavía se limitan a estudios analíticos. Incluye publicaciones u otras referencias que describen la aplicación que se está considerando y que respalden el concepto. Es una transición de investigación pura (TRL 1) a aplicada (TRL 2). El trabajo experimental está diseñado para corroborar las observaciones científicas básicas realizadas durante el trabajo de TRL 1. El resultado de este nivel es la formulación del concepto de la tecnología.",
						"En TRL 3, el trabajo ha pasado de la fase en papel al trabajo experimental que verifica que el concepto funciona como se esperaba. Esto incluye estudios analíticos y estudios a escala de laboratorio para validar físicamente las predicciones analíticas de elementos separados de la tecnología. Se cuenta con componentes que aún no están integrados y los resultados de las pruebas de laboratorio para sustentar los componentes básicos de la tecnología. Las contribuciones de la tecnología ya deben esta diagnosticadas. El resultado de este nivel es el concepto comprobado o por lo menos el diseño de la primera prueba de concepto.",
						"Los componentes tecnológicos básicos han sido todos identificados, están integrados, y se ha probado que trabajan juntos a nivel de laboratorio (ambiente controlado). TRL 4 es el primer paso para determinar si los componentes trabajarán como un sistema. Los niveles 4-6 representan el paso de la investigación científica a la ingeniería. La integración no abarca aspectos estéticos o de portabilidad más bien el funcionamiento en conjunto de los componentes de la tecnología. Se identifican los indicadores que validan las predicciones sobre los componentes. Con la integración de los componentes el enfoque de la tecnología puede ser fundamentado, es decir, la consolidación de la tecnología es creíble.",
						"Los componentes integrados de la tecnología son similares (coinciden) al desarrollo final en casi todos los aspectos y funcional. Considera pruebas de la tecnología en un entorno relevante (similar al real). Se analizan las diferencias en el desempeño de la tecnología en el laboratorio y en el entorno relevante. La principal diferencia entre TRL 4 y TRL 5 es el aumento en la fidelidad de la tecnología y del entorno con las condiciones reales. El sistema probado es casi prototípico. Las interacciones de los componentes esta bien identificadas y argumentadas	. El potencial de adopción es regular, sin embargo, el riesgo en aspectos de ingeniería y desarrollo es alto.",
						"Los modelos o prototipos a escala de ingeniería (completa) se prueban en un entorno relevante. Esto representa un gran avance en la demostración de la madurez de una tecnología. En TRL 6 comienza el verdadero desarrollo de la tecnología como un sistema operativo. La principal diferencia entre TRL 5 y 6 es un aumento de la escala de laboratorio a la escala de ingeniería y la determinación de los factores de escala que permitirán el diseño del sistema operativo. El prototipo debe ser capaz de realizar todas las funciones definidas y tener un rendimiento justificado. El entorno operativo para las pruebas debe representar estrechamente el entorno operativo real. Los datos de las pruebas muestran que el potencial de adopción es mayor, sin embargo, el riesgo en aspectos de ingeniería y desarrollo va de medio a alto.",
						"Se ha demostrado que la tecnología funciona y opera a escala pre-comercial. Representa un gran avance desde TRL 6, que requiere la demostración de un prototipo de sistema real en un entorno relevante para pruebas a gran escala en campo. Las pruebas se realizan con clientes potenciales, y se consideran aspectos como satisfacción y experiencia de usuarios. En algunos casos representa el comienzo de los trabajos de mercadotecnia. El diseño final está virtualmente completo, por lo que se pueden abordar algunos aspectos de manufactura.",
						"Se ha demostrado que la tecnología funciona en condiciones reales, se ha definido el diseño final y se cuenta con el paquete tecnológico. Todas las cuestiones operativas y de fabricación han sido resueltas. Se han abordado los inconvenientes observados en las pruebas, se cuenta con certificaciones, así como permisos y se cumple con las regulaciones de la industria. Se consolidan los procesos de ventas y servicio al cliente. Se evalúa la madurez operativa de la organización.",
						"En este nivel se contempla un producto completamente desarrollado y disponible para el mercado. Y se abordan aspectos de producción en serie, sistemas de gestión de calidad, búsqueda de nuevos clientes y oportunidades de innovación.");




						
/////////////// Consultas bases de datos - para generar el reporte/////////////////////////////////////////////////////////////////////////////////////////////////////////////						
//Consulta para obtener el titulo Proyecto
$query0 = "SELECT titulo_proyecto, trl_tipos_usuarios_idtrl_tipos_usuarios FROM trl_usuarios WHERE idusuarioTRL={$usuario};"; 
$resultado0 =$conex->query($query0);
while($row0 = $resultado0->fetch_assoc())
{
$tituloProyecto = $row0['titulo_proyecto'];
$tipoUsuario = $row0['trl_tipos_usuarios_idtrl_tipos_usuarios'];
}

//consulta para obtener la descripcion de los niveles
$query4 = "SELECT idniveles,descripcion FROM trl_niveles;"; 
$resultado4 =$conex->query($query4);

//consulta para obtener los avances por nivel
$query = "SELECT c1 as nivel,c2 as aspNivel,c4 as aspLogrados,ROUND(c4*100/c2) as porcentaje FROM (
					  SELECT niveles_idniveles AS c1, COUNT(niveles_idniveles) AS c2
						FROM trl_preguntas 
						group by niveles_idniveles) AS cp INNER JOIN
(SELECT niveles_idniveles AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$usuario} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY niveles_idniveles) AS cr
ON (cp.c1= cr.c3);"; 
$resultado =$conex->query($query);

// consulta para obtener los avances por categoria
$query2 = "SELECT  c6 as categoria ,c2 as aspCat,c4 as aspLogrados,ROUND(c4*100/c2) as porcentaje FROM (

					  SELECT trl_categoria_idtrl_categoria AS c1, COUNT(trl_categoria_idtrl_categoria) AS c2
						FROM trl_preguntas 
						group by trl_categoria_idtrl_categoria) AS cp INNER JOIN
(SELECT trl_categoria_idtrl_categoria AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$usuario} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY trl_categoria_idtrl_categoria) AS cr INNER JOIN 
    (SELECT idtrl_categoria as c5, categoria as c6 from trl_categoria) as cc
ON (cp.c1= cr.c3 and cr.c3 = cc.c5) order by c5;"; 
$resultado2 =$conex->query($query2);

//consulta definir nivel
$query3 = "SELECT * FROM (SELECT c1 as Nivel, ROUND(c4*100/c2) as porcentaje FROM (
					  SELECT niveles_idniveles AS c1, COUNT(niveles_idniveles) AS c2
						FROM trl_preguntas 
						group by niveles_idniveles) AS cp 
INNER JOIN (SELECT niveles_idniveles AS c3, COUNT(idrespuestas) AS c4 
	FROM trl_preguntas as t1 left join trl_respuestas AS t2
	ON (usuarioTRL_idusuarioTRL={$usuario} AND t1.idtrl_preguntas= t2.trl_preguntas_idtrl_preguntas) 
	GROUP BY niveles_idniveles) AS cr
ON (cp.c1= cr.c3)
ORDER BY porcentaje DESC, cp.c1 DESC) AS generalNivel
WHERE porcentaje >= 30
LIMIT 1;"; 
$resultado3 =$conex->query($query3);
while($row = $resultado3->fetch_assoc())
{
	$nivelAlcanzado = $row["Nivel"];
	$porcentajeNivelAlzanzado = $row["porcentaje"];
}

//consulta para mostrar recomendaciones
$nivelNoAlcanzado = $nivelAlcanzado+1;
$query5 = "(SELECT trl_niveles_idniveles, descripcion as descr, NULL as orden FROM trl_evidencias 
			WHERE trl_niveles_idniveles BETWEEN 1 AND {$nivelAlcanzado}) "; 
if($nivelAlcanzado<9){
	$query5 = $query5."UNION
						(SELECT trl_niveles_idniveles, texto as descr, orden FROM trl_recomendaciones 
						WHERE 	trl_niveles_idniveles BETWEEN {$nivelNoAlcanzado} AND 9)
						ORDER BY trl_niveles_idniveles ASC,orden ASC;"; 
}else{
	$query5 = $query5."ORDER BY trl_niveles_idniveles ASC,orden ASC;"; 
}
$resultado5 =$conex->query($query5);

//consulta para mostrar cursos y talleres en la seccion de conclusiones
if($nivelAlcanzado<9){
$query10 = "SELECT trl_tipos_producto_idtrl_tipos_producto,trl_nominacion_producto.nombre, enlace FROM  trl_nominacion_producto 
INNER JOIN trl_productos  ON idtrl_nominacion_producto = trl_nominacion_producto_idtrl_nominacion_producto
INNER JOIN trl_productos_has_trl_niveles ON  idtrl_productos = trl_productos_idtrl_productos
INNER JOIN trl_tipos_usuarios ON idtrl_tipos_usuarios = trl_tipos_usuarios_idtrl_tipos_usuarios
INNER JOIN trl_niveles ON trl_niveles_idniveles = idniveles
WHERE idniveles = {$nivelNoAlcanzado} 
AND (trl_tipos_producto_idtrl_tipos_producto = 1 
OR trl_tipos_producto_idtrl_tipos_producto = 2) 
AND idtrl_tipos_usuarios = {$tipoUsuario};"; 
$resultado10 =$conex->query($query10);
}
//consulta consulta para obtener los servicios y cursos basico para proyectos que no alcanzaron nivel
if($nivelAlcanzado==0){
	$query11 = "SELECT trl_tipos_producto_idtrl_tipos_producto, nombre FROM trl_nominacion_producto 
INNER JOIN trl_productos ON trl_nominacion_producto_idtrl_nominacion_producto=idtrl_nominacion_producto
WHERE idtrl_productos BETWEEN 93 AND 100 AND trl_tipos_usuarios_idtrl_tipos_usuarios= {$tipoUsuario};"; 
	$resultado11 =$conex->query($query11);
}
 
/////////////////////////////////////////Contenido pdf/////////////////////////////////////////////////
//creación PDF
$pdf= new PDF();
$pdf->AliasNbPages(); // permite obtener el número de paginas

//pagina inicial
$pdf->AddPage();

// título  / encabezado del reporte
$pdf-> SetY(35);
$pdf->ln(30);
$pdf->Cell(0,10, utf8_decode('Reporte Nivel de Madurez de la Tecnología'),0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,ANCHORENGLON, utf8_decode("Título del proyecto: {$tituloProyecto}"),0,1,'C');
$pdf-> SetX(70); // centrar la celda del nivel alcanzado

if($porcentajeNivelAlzanzado>30){
	$pdf->Cell(60,ANCHORENGLON, "Nivel de Madurez (TRL) alcanzado:",0,0,'C');
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(10,ANCHORENGLON,$nivelAlcanzado,0,1,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,ANCHORENGLON, "Con un porcentaje de avance de: ".$porcentajeNivelAlzanzado." %",0,1,'C');
}
$pdf->ln(2);
if(($porcentajeNivelAlzanzado>30)&&($porcentajeNivelAlzanzado<=50)){
	$pdf-> SetX(20);
	$pdf->SetFont('Times','B', 10);
	//leyenda 1 porcentaje de avance bajo en el nivel alcanzado
	$pdf->multicell(170,ANCHORENGLON, '"El nivel de avance alcanzado no es significativo por lo que se recomienda atender las observaciones expresadas en el presente reporte"',0,'C',0);
}else if ($porcentajeNivelAlzanzado<30){
	$pdf-> SetX(20);
	$pdf->SetFont('Times','B', 10);
	// Leyenda 2 no se alcanzo nivel
	$pdf->multicell(170,ANCHORENGLON, utf8_decode('"Desafortunadamente con el avance actual del proyecto no se puede asignar una posición en la escala TRL por tal motivo se exhorta a atender a la brevedad las observaciones expresadas en el presente reporte"'),0,"C",0);	
}
//-----------Sección 0---------------
$pdf->Ln(7);
//texto 0
darFormatoTexto('El presente reporte es generado por la plataforma digital de la empresa TB&R y es el resultado de analizar el proyecto titulado "'.$tituloProyecto.'", haciendo uso de la metodología de evaluación de madurez tecnológica. Donde se asigna un nivel de madurez tecnológica (TRL, del inglés Technology Readiness Level) a cada proyecto dependiendo de su avance en las diversas etapas de su desarrollo. 

Este documento comienza con una introducción a la metodología TRL; luego se presenta el desglose de resultados por categoría (mercado, desarrollo, manufactura, etc.); se contrapone el TRL alcanzado con las etapas del ciclo de vida de una tecnología; después se hace el desglose de los resultados por nivel; y por ultimo se muestra un resumen sobre la evaluación, las recomendaciones y las áreas de oportunidad para elevar el TRL de la tecnología.
');

//-----------Sección 1---------------
$pdf->AddPage();
//Subtítulo sección 
darFormatoSubtitulos('INTRODUCCIÓN');

//texto 1 introducción TRL
darFormatoTexto('Durante los últimos años han surgido clasificaciones diferentes y cada vez más complejas para toda clase de tecnología. Una de las clasificaciones más utilizada para determinar la factibilidad de implementar una tecnología está basada en los niveles de madurez [1].

Los TRL son un sistema métrico aceptado para medir la madurez de la tecnología utilizado por diversas organizaciones estadounidenses como: lógicamente la NASA, el AFRL y la DUSD S&T [1]. En México, varios apoyos financieros e instituciones como el Consejo Nacional de Ciencia y Tecnología (CONACYT) establecen como requisito básico el análisis TRL de las tecnologías candidatas de sus programas. 

La Administración Nacional de Aeronáutica y del Espacio (NASA) instituyó la métrica TRL para evaluar el riesgo asociado con el desarrollo de cierta tecnología [2]. La madurez tecnológica es crítica y una técnica estándar para la integración de la tecnología con el sistema del que va a formar parte y para sustentar la inversión en su proceso de desarrollo [1]. 

La evaluación de la madurez tecnológica también proporciona la base para la evaluación técnica del riesgo y la cuantificación de la incertidumbre [3]. Permite hacer una comparación consistente de la madurez de diferentes tipos de tecnologías [2]. Concretamente mide el grado en que una tecnología es adecuada para su implementación en un ambiente operativo real [3].

Por lo tanto, si consideramos una tecnología concreta y tenemos el nivel en el que se encuentra podremos hacernos una idea de la etapa del ciclo de vida en el que se encuentra la tecnología.

Esta metodología ha sufrido diversos cambios y a partir de ella han surgido nuevos modelos, o variantes de ella que se especializan en ciertos sectores. Actualmente el análisis TRL otorga uno de los 9 niveles de madurez tecnológica que conforma esta escala. 
');

//TABLA DESCRIPCIÓN DE NIVELES
$pdf->addPage();
darFormatoSubtitulos('DESCRIPCIÓN DE LOS NIVELES DE MADUREZ TECNOLÓGICA');
//texto 1.1 descripción niveles
darFormatoTexto('A continuación con base en [5], [6] y [7] se muestra el nombre, una breve descripción de cada uno de los niveles, y sus actividades relacionadas. Esto como preámbulo para entender los resultados presentado en este reporte.
');
$anchoColumnasPorMulticell= 0;
while($row = $resultado4->fetch_assoc())
{
	if ($indiceNiveles==3||$indiceNiveles==6){
		$pdf->addPage();
	}
	$numRenglones = numeroRenglonesTexto(130,$descricionNivels[$indiceNiveles]);
	$anchoColumnasPorMulticell= $numRenglones*ANCHORENGLON;
	//obtiene el tamaño de los logos, tanto el ancho como el alto serán iguales
	$tamaño = obtenerTamañoLogosNiveles($numRenglones);
	darFormatoTextoEncabezadoTabla();
	$pdf-> SetX(20);
	$pdf->Cell(170,ANCHORENGLON,"Nivel ".$row['idniveles'],0,1,'C',1); //número del nivel
	$pdf->SetFont('Arial','B',11);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(99,102,103);
	//$pdf->SetFillColor(255, 255, 255);
	$pdf-> SetX(20);
	//$pdf->Cell(40,ANCHORENGLON,"",0,0,'C',1);
	$pdf->Cell(170,8,utf8_decode($row['descripcion']),'B',1,'C',0); // nombre del nivel
	$pdf-> SetX(20);
	//obtiene la posicion de los logos de cada nivel con respecto a las dimenciones de la celda que las contendra y a su tamaño
	$x = $pdf-> GetX()+((40-$tamaño)/2);
	$y = $pdf-> GetY()+(($anchoColumnasPorMulticell-$tamaño)/2);
	darFormatoTextoContenidoTabla();
	$pdf->Cell(40,$anchoColumnasPorMulticell,'',0,0,'C',0);
	$pdf->Image('images/logos_niveles/nivel'.$row['idniveles'].'.png',$x,$y,$tamaño,$tamaño,'PNG'); // logotipo
	$pdf-> MultiCell(130,ANCHORENGLON,utf8_decode($descricionNivels[$indiceNiveles]),0,'J',0); // descripción
	$indiceNiveles++; //incrmenta para obtener la descripción del siguentes nivel en la proxima iteración		
}
 //-----------Sección 2---------------
$pdf->addPage();

//encabezado tabla complimiento por categoría
darFormatoSubtitulos('DESGLOSE DE RESULTADOS POR CATEGORÍA');

//texto 2 introducción categorías
darFormatoTexto('Los primeros resultados se analizan con base en las 6 categorías definida dentro de este estudio. La primera categoría contempla las características generales de la tecnología; la segunda categoría abarca aspectos relacionados al conocimiento del mercado donde que se lanzará la tecnología; el comienzo del desarrollo se engloba en la tercera categoría y el desarrollo avanzado es la cuarta categoría; si se cuenta con una tecnología probada y validada será muy sencillo abordar la quinta categoría; la sexta y última categoría se relaciona con la producción de producto final.
');

//TABLA CUMPLIMIENTO POR CATEGORÍA

//encabezados tabla complimiento por categoría
darFormatoTextoEncabezadoTabla();
$pdf->Ln(3);
$pdf->SetX(20);
$pdf->Cell(45,ANCHORENGLON,utf8_decode('CATEGORÍA'),0,0,'C',1);
$pdf->Cell(40,ANCHORENGLON,'CUMPLIMIENTO',0,0,'C',1);
$pdf->Cell(40,ANCHORENGLON,'PORCENTAJE',0,0,'C',1);
$pdf->Cell(45,ANCHORENGLON,utf8_decode('AVANCE CATEGORÍA'),0,1,'C',1);

//contenido tabla tabla complimiento por categoría
darFormatoTextoContenidoTabla();
while($row = $resultado2->fetch_assoc())
{
	$pdf-> SetX(20);
	$pdf->Cell(45,ANCHORENGLON,utf8_decode($row['categoria']),0,0,'C',0);
	$pdf->Cell(40,ANCHORENGLON,$row['aspLogrados'].' de '.$row['aspCat'],0,0,'C',0);
	$pdf->Cell(40,ANCHORENGLON,($row['aspLogrados']>0?$row['porcentaje']:NCERO)."%",0,0,'C',0);
	
	//generar imagenes para dibujar barras de progreso
	$imImage = @imagecreate( 162, 10 );
	$imFondo  = imagecolorallocate( $imImage, 234, 84, 86 );
	$imRelleno = imagecolorallocate( $imImage, 76, 222, 119 );
	// generar rectangulo de progreso acorde al porcentaje obtenido
	$tamBar=round(162*$row['porcentaje']/100);//tamaño de la barra
	imagefilledrectangle( $imImage, 0, 0, $tamBar , 10, $imRelleno );
	imagepng($imImage, "images/barras_progreso/porcentajeC{$row['categoria']}.png");
	imagedestroy( $imImage );
	
	//obtener coordenadas para ubicar barra de progreso
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	
	//agregar barra de pogreso al pdf en forma de imagen
	$pdf->Cell(45,ANCHORENGLON,"",0,1,'C',0);
	$pdf->Image("images/barras_progreso/porcentajeC{$row['categoria']}.png", $x+1.5, $y+2);
}

//titulo tabla descripción niveles
darFormatoTitulosFiguras ('Avance TRL por Categoría','t');
$pdf->ln(2);
//texto 3 segunda parte resultados por categoría
darFormatoTexto('Si se observa la Tabla 1 se puede apreciar cada una de las categorías mencionadas, en la columna CUMPLIMIENTO se especifican cuantos reactivos conforma cada categoría y el número de reactivos obtenidos en la evaluación del proyecto. En las últimas dos columnas se presenta el porcentaje de avance junto con la barra de progreso.

Cada una de las categorías puede representar una etapa del proceso de evolución de la tecnología y son piezas clave para avanzar en los niveles de madurez, por tal motivo es importante trabajar las categorías con bajo porcentaje. Por otro lado, si se cuentan con varias categorías completas o terminadas es posible que se logre obtener un nivel TRL alto.
');

//GRÁFICA CICLO DE VIDA VS TRL
$pdf -> addPage();
if($nivelAlcanzado==0){
	darFormatoSubtitulos('ETAPA DE MADUREZ: CICLO DE VIDA VS TRL');
	darFormatoTexto("Toda tecnología requiere de un proceso de desarrollo, conforme este proceso va evolucionando la tecnología tendrá un mejor desempeño o utilidad, es decir, estará más cercana a ser un producto comercializable. Cada desarrollo puede pasar de una etapa a otra del ciclo de vida al transcurrir diferente cantidad de tiempo, sin embargo, si se conoce el TRL, se puede definir si es una tecnología naciente, en desarrollo o consolidad.
	Para tecnologías con un nivel de desarrollo asignados en esta sección el análisis TRL-Ciclo de Vida se muestra de manera gráfica. Se debe proseguir en la maduración de la tecnología para que esta tenga una evaluación más detallada. Visita la pagina de TB&R para saber más sobre las etapas del ciclo de vida: 'http://www.techbusiness.com.mx");
	//agregar imagen de la grafica de ciclo de vida borrosa
	$pdf-> ln(10);
	$pdf->Image("images/graficas/graficaCicloVida-TRL-borroso.png", 43, $pdf->getY()-10);
	$pdf-> ln(84);
	$pdf->SetFont('Arial','B',11);
	$pdf-> SetX(20);
	$pdf->multicell(170,ANCHORENGLON, utf8_decode('**No se identificará la etapa del ciclo de vida de la tecnología del presente proyecto, ni se visualizará su gráfica correspondiente hasta que se alcance un nivel TRL**'),0,'C',0);
	$pdf-> ln(3);
	
}else{
	// obtiene los valores en y con base en una función para obtener la  curva S, con un desplazamiento de 6 para que su origen sea 0 
	foreach ($datos21 as $valor)
		$datos22[] = 1/(1+pow(M_E,-(1.1*$valor-6)));
		
	$graph = new Graph(620,355);

	$graph->SetScale("linlin");//definir la escala de X y Y (lin-decimal, int-enteros, log-logaritmica o text-texto)
	$theme_class=new UniversalTheme;
	$graph->SetTheme($theme_class);
	$graph->SetBox(false);

	$graph->legend->SetPos(0.05,0.50);//considerando la esquina superior derecha como origen (valores de 0-1)
	$graph->legend->SetFrameWeight(1);//grosor del contorno de la leyenda

	//$graph->title->Set('Etapa del ciclo de vida con base en TRL');
	//$graph->title->SetFont(FF_ARIAL, FS_BOLD, 10);

	$graph->img->SetAntiAliasing(false);

	$graph->ygrid->Show(false,false);//lineas grid

	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle("solid");
	$graph->xgrid->SetColor('white');

	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideLabels();
	$graph->yaxis->HideTicks(true,true);
	$graph->yaxis->SetColor('white','#177785');
	$graph->yaxis->SetTitle(utf8_decode("DESEMPEÑO DE LA TECNOLOGÍA"),"center");
	$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD,9);
	$graph->yaxis->title->SetColor('#177785');
	$graph->yaxis->SetTitlemargin(4);

	$graph->xaxis->SetLabelSide(SIDE_DOWN);//ubica las etiquetas abajo o arriba de los ejes de la tabla
	//ubicar los niveles de acuerdo a la etapa del ciclo de vida
	$graph->xaxis->SetMajTickPositions(array(0,0.5,1,2,3.5,5,6,7.5,9.5),array('1','2','3','4', '5', '6', '7', '8','9'));
	$graph->xaxis->HideTicks(true,false);
	$graph->xaxis->SetColor('white','#177785');
	$graph->xaxis->SetTitle(utf8_decode("NIVELES DE MADUREZ DE LA TECNOLOGÍA"),"center");
	$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD,9);
	$graph->xaxis->title->SetColor('#177785');

	// agregar datos
	$p1 = new LinePlot($datos22,$datos21);
	//$p1->SetCenter(); no centra la grafica en este momento porque ya se definiero los tikers
	$graph->Add($p1);
	$p1->SetWeight(3);
	$p1->SetColor("#DE4D4C");
	$p1->SetStyle('solid');



	//marca en el nivel alcanzado por el proyecto
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("#D9797F");
	$p1->mark->SetColor('#D9797F');
	$p1->mark->SetCallbackYX("FCallback"); 

	//agregar las barras para definir las etapas del ciclo de vida
	dibujarBarras("#5180C3","min",0.03);//etapa nacimiento
	dibujarBarras("#55A6CD",0.03,0.5);//etapa infancia
	dibujarBarras("#51C38A",0.5,0.92);//etapa adolecencia 
	dibujarBarras("#55CDB3",0.92,0.97);//etapa adultez
	dibujarBarras("#55B0B6",0.97,"max");//etapa madurez

	$graph->img->SetTransparent('white');//fondo tranparente


	$graph->Stroke(_IMG_HANDLER);

	$fileName = "images/graficas/graficaCicloVida-TRL.png";
	$graph->img->Stream($fileName);

	darFormatoSubtitulos('ETAPA DE MADUREZ: CICLO DE VIDA VS TRL');

	//texto 4 OPC1 inicio gráfica ciclo de vida
	darFormatoTexto("En la segunda sección del reporte se describieron adecuadamente cada uno de los niveles, lo que permite tener un mayor entendimiento sobre lo que significa que el proyecto analizado haya obtenido un TRL {$nivelAlcanzado}, sin embargo, ahora se contrasta este nivel con la etapa del ciclo de vida en la que la tecnología se encuentra.
	");

	//agregar imagen de la grafica de ciclo de vida al pdf
	$pdf-> SetX(21);
	$pdf->Image("images/graficas/graficaCicloVida-TRL.png", $pdf->getX(), $pdf->getY()-20);
	$pdf-> ln(70);
	darFormatoTitulosFiguras('Etapa del ciclo de vida con base en TRL','g');
	$pdf-> ln(3);

	//texto 5 OPC1 descripcin gráfica ciclo de vida
	darFormatoTexto('Toda tecnología requiere de un proceso de desarrollo, conforme este proceso va evolucionando la tecnología tendrá un mejor desempeño o utilidad, es decir, estará más cercana a ser un producto comercializable. Cada desarrollo puede pasar de una etapa a otra del ciclo de vida al transcurrir diferente cantidad de tiempo, sin embargo, si se conoce el TRL, se puede definir si es una tecnología naciente, en desarrollo o consolidad.

	La Gráfica 1 se basa en [4] y muestra la primera sección del ciclo de vida de una tecnología. La cual se conforma de las etapas de: nacimiento, infancia, adolescencia, adultez y madurez (ver la siguiente imagen para identificar cada etapa). En dicha gráfica se puede observar como la utilidad de una tecnología crece de acuerdo con la etapa, y como el nivel TRL obtenido en este proyecto entra en alguna de las etapas definidas.
	');
	$pdf-> SetXY(20,$pdf->getY()-5);
	//continuación texto 5 OPC1 descripcin gráfica ciclo de vida
	$pdf->multicell(100,ANCHORENGLON, utf8_decode('Esto corrobora la premisa de los niveles TRL, puesto que a mayor nivel TRL mayor facilidad de adopción de la tecnología, integración con otros sistemas, comercialización del producto y por último la utilidad del producto para el mercado.'),0,'J',0);
	$pdf->Image("images/graficas/coloresCicloVida.png", 135, $pdf->getY()-25, 40,30);
	$pdf-> ln(84);
}

//-----------Sección 3---------------
$pdf->addPage();

//TABLA CUMPLIMIENTO POR NIVEL	
darFormatoSubtitulos('DESGLOSE DE RESULTADOS POR NIVEL');

//texto 6 tabla complimiento por nivel
darFormatoTexto('Ahora se observa el avance obtenido por la tecnología desde otra perspectiva, si se observa la Tabla 2 por cada nivel se indica el número de aspectos evaluados, los aspectos CUMPLIDOS y el avance alcanzado en cada nivel.
');
//encabezado tabla cumplimiento por nivel
darFormatoTextoEncabezadoTabla();
$pdf->Ln(3);
$pdf->SetX(20);
$pdf->Cell(30,ANCHORENGLON,'NIVEL',0,0,'C',1);
$pdf->Cell(40,ANCHORENGLON,'CUMPLIMIENTO',0,0,'C',1);
$pdf->Cell(40,ANCHORENGLON,'PORCENTAJE',0,0,'C',1);
$pdf->Cell(60,ANCHORENGLON,'AVANCE POR NIVEL',0,1,'C',1);

//contenido tabla cumplimiento por nivel
darFormatoTextoContenidoTabla();
while($row = $resultado->fetch_assoc())
{
	$pdf-> SetX(20);
	$pdf->Cell(30,ANCHORENGLON,$row['nivel'],0,0,'C',0);
	$pdf->Cell(40,ANCHORENGLON,$row['aspLogrados'].' de '.$row['aspNivel'],0,0,'C',0);
	$pdf->Cell(40,ANCHORENGLON,($row['aspLogrados']>0?$row['porcentaje']:NCERO)."%",0,0,'C',0);
	
	
	//generar imagenes para dibujar barras de progreso
	$imImage = @imagecreate( 216, 10 );
	$imFondo  = imagecolorallocate( $imImage, 234, 84, 86  );
	$imRelleno = imagecolorallocate( $imImage, 76, 222, 119 );
	$tamBar=round(216*$row['porcentaje']/100);//tamaño de la barra
	imagefilledrectangle( $imImage, 0, 0, $tamBar , 10, $imRelleno );
	imagepng($imImage, "images/barras_progreso/porcentajeN{$row['nivel']}.png");
	imagedestroy( $imImage );
	// generar rectangulo de progreso acorde al porcentaje obtenido
	$x=$pdf->GetX();//obtener coordenadas para ubicar barra de progreso
	$y=$pdf->GetY();
	$pdf->Cell(60,ANCHORENGLON,"",0,1,'C',0);
	$pdf->Image("images/barras_progreso/porcentajeN{$row['nivel']}.png", $x+1.5, $y+2);
	//guardar valores para la grafica de cumplimento de TRL
	$datos[]=$row['aspLogrados'];
	$aspectosTotalesNivel[]=$row['aspNivel'];
	$totalAspectosTRL += $row['aspNivel'];
}

//encontrar los niveles con menor avance
//los niveles que tiene menos del 50 
//solo cuando se obtiene por lo menos un TRL=2 ($nivelAlcanzado=2)
	for($index=0;$index<9;$index++){
			//la segunda condicion del if permite que solo se identifique los nivel con poco avance que estan por debajo del nivel obtenido
			if($datos[$index]<(50*$aspectosTotalesNivel[$index])/100){
				$nivelesConPocoAvance[] = 1;
				$numNivelesPocoAvance ++;
			}else {
				$nivelesConPocoAvance[] = 0;
			}
	}

//consultas para obtener los servicios y cursos para los niveles con poco avance
if($nivelAlcanzado==0){
	
}
if ($numNivelesPocoAvance>0){
	$query8 = "SELECT trl_nominacion_producto.nombre, enlace FROM  trl_nominacion_producto INNER JOIN trl_productos  ON idtrl_nominacion_producto = trl_nominacion_producto_idtrl_nominacion_producto
	INNER JOIN trl_productos_has_trl_niveles ON  idtrl_productos = trl_productos_idtrl_productos
	INNER JOIN trl_tipos_usuarios ON idtrl_tipos_usuarios = trl_tipos_usuarios_idtrl_tipos_usuarios
	INNER JOIN trl_niveles ON trl_niveles_idniveles = idniveles
	WHERE (";
	$query9 = $query8;
	$banderaPrimerNivelConPocoAvance = true;
	for($index=0;$index<9;$index++){
		if($nivelesConPocoAvance[$index]==1){
			if($banderaPrimerNivelConPocoAvance){
				$query8 = $query8."idniveles = ".($index+1);
				if ($numNivelesPocoAvance==1){
					break;
				}
				$banderaPrimerNivelConPocoAvance= false;
			}else{
				$query8 = $query8." OR idniveles = ".($index+1);
			}
		}
			
	}
	$query9 = $query8.") AND trl_tipos_producto_idtrl_tipos_producto = 2 AND idtrl_tipos_usuarios = {$tipoUsuario}
	GROUP BY trl_nominacion_producto.nombre,enlace;";
	$query8 = $query8.") AND trl_tipos_producto_idtrl_tipos_producto = 1 AND idtrl_tipos_usuarios = {$tipoUsuario}
	GROUP BY trl_nominacion_producto.nombre,enlace;";

	$resultadoCur =$conex->query($query8);
	//echo $query8;
	$resultadoSer =$conex->query($query9);
	
	$numCursos = mysqli_num_rows($resultadoCur);
	$numServicios = mysqli_num_rows($resultadoSer);
	


}





//normalizar datos para la grafica de cumplimento de TRL
//considerando que el 100% es cumplir con todas las afirmaciones del TRL
for($index=0;$index<9;$index++){
	$datos[$index] = $datos[$index]*100/$totalAspectosTRL;
	if($index!=0){
	$datos[$index] = $datos[$index-1]+$datos[$index];
	}
}


$datos[] = '';
$datos[] = 100;

$pdf->Ln(3);
darFormatoTitulosFiguras ('Avance TRL por Nivel','t');
$pdf->Ln(3);

//texto 7 resultados por nivel segunda parte
darFormatoTexto('Dentro de la tabla 2 es importante notar que un desarrollo congruente es aquel que cuenta con un avance significativo en todos los niveles previos al nivel obtenido, es decir, no es conveniente dejar niveles previos con un avance mínimo, ya que esto supondría que existen aspectos de la tecnología que no han sido sustentados o debidamente probados.
');
$pdf->addPage();
$pdf->Ln(3);
darFormatoSubtitulos('PROMEDIO DE ASPECTOS CUMPLIDOS');


//GRÁFICA CUMPLIMIENTO TRL

//texto 8 descriptivo gráfica cumplimiento TRL
darFormatoTexto("Cada análisis TRL tiene diversas consideraciones, aspectos o requisitos para alcanzar cada uno de los TRL (como se observa en la Tabla 1 y 2).  Entonces en esta sección se presenta de manera gráfica el avance en el cumplimiento de estos aspectos marcando el porcentaje aportado por cada nivel, para así obtener el porcentaje de cumplimiento total de la evaluación.

En la Gráfica 2 se otorga una calificación en escala del 1 al 100, que corresponde al porcentaje de complimiento de todos los aspectos abordado en esta evaluación TRL. El incremento en la gráfica de un nivel a otro representa la aportación de cada nivel a esta calificación global.
");

$graph = new Graph(560,440);
$graph->SetScale("intlin");
#$graph->SetScale("textlin");


$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);
//$graph->clearTheme();
//$graph->img->SetMargin(40,40,30,40);

$graph->img->SetAntiAliasing(false);// permite cambiar el grosor de las lineas

$graph->SetBox(false);

$graph->legend->SetPos(0.6,0.20);//considerando la esquina superior derecha como origen (valores de 0-1)	
$graph->legend->SetFillColor('#FFFFFF');
$graph->legend->SetFrameWeight(1);
//$graph->legend->SetMarkAbsVSize(0); //quitar la marca en la leyenda

$graph->title->Set('El Proyecto Cumple con el '.round($datos[8]).'% de los Aspectos Requeridos');// muestra en la table el porcentaje de aspectos de TRL con los que se cumple
$graph->title->SetFont(FF_FONT2, FS_BOLD, 14);
 
//$graph->xaxis->SetMajTickPositions(array(0,1,2,3,4,5,6,7,8),array('1','2','3','4', '5', '6', '7', '8', '9'));
$graph->xaxis->SetTickLabels(array('1','2','3','4', '5', '6', '7', '8', '9'));
$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD,7);
$graph->xaxis->title->SetColor('#177785');
$graph->xaxis->SetTitle(utf8_decode("NIVELES DE MADUREZ DE LA TECNOLOGÍA"),"center");
$graph->xaxis->SetColor('#177785','#177785');
$graph->xaxis->HideTicks(true,false);

//$graph->yaxis->SetMajTickPositions(array(10,20,30,40,50,60,70,80,90,100),array('10','20','30','40','50','60','70','80','90','100')); //provoca problemas con el grid
$graph->yaxis->HideTicks(true,false);
$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD,7);
$graph->yaxis->title->SetColor('#177785');
$graph->yaxis->SetTitle("PORCENTAJE DE CUMPLIMIENTO","center");
$graph->yaxis->SetColor('#177785','#177785');
$graph->yaxis->HideZeroLabel();

$graph->ygrid->Show(true,false);
//$graph->ygrid->SetLineStyle('solid');
//$graph->ygrid->SetColor('red');
//$graph->ygrid->SetWeight(10);
//$graph->ygrid->SetFill(true,'gray@0.2','gray@0.5');

$graph->xgrid->Show(true,false);
//$graph->xgrid->SetLineStyle('solid');
//$gxraph->xgrid->SetColor('red');
//$graph->xgrid->SetWeight(10);

// agregar datos a la gráfica cumplimiento TRL
// porcentaje de cumplimiento
$p1 = new LinePlot($datos, $datosX);
$graph->Add($p1);
$p1->SetWeight(3);
$p1->SetColor("#177785");
$p1->SetLegend(utf8_decode("Aportación por nivel"));
$p1->SetStyle('solid');

//linea recta para marcar el procentaje de avance
$p2 = new LinePlot(array(round($datos[8]),round($datos[8])), array(0,8));
$graph->Add($p2);
$p2->SetWeight(3);
$p2->SetColor("#DE4D4C");
$p2->SetStyle('solid');

//modificar las marcas de la grafica
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("#177785");
$p1->mark->SetColor('#177785');
$p1->mark->SetCallbackYX("FCallback1");

$graph->img->SetTransparent('white');//fondo transparente
$graph->Stroke(_IMG_HANDLER);
$fileName = "images/graficas/graficaCumplimientoTRL.png";
$graph->img->Stream($fileName);

$pdf-> SetX(33);
$pdf->Image("images/graficas/graficaCumplimientoTRL.png", $pdf->getX(), $pdf->getY());
$pdf->Ln(100);
darFormatoTitulosFiguras ('Avance TRL por Nivel','g');

$pdf->ln(3);

if($numNivelesPocoAvance==0){
	if($nivelAlcanzado==9){
		if(round($datos[8])==100){
			//texto 9 OPC0 mostrar niveles con poco avance (ningún nivel con poco avance y nivel 9 y 100% de cumplimiento)
			darFormatoTexto('Como se observa en la Grafica 2, la tecnología es viable ya que no tiene niveles con avances mínimos, por lo que se exhorta al equipo de desarrollo a realizar el acopio de las evidencias solicitadas en la sección de recomendaciones, a identificar nuevas aplicaciones de la tecnología (o modelo desarrollado) con el fin de ampliar su mercado y generar nuevos proyectos con lo aprendido con el producto o proceso propuesto.
			');
		}else{
			//texto 9 OPC1 mostrar niveles con poco avance (ningún nivel con poco avance y nivel 9)
			darFormatoTexto('Como se observa en la Grafica 2, la tecnología es viable ya que no se identifican niveles con avances bajos, sin embargo, si el porcentaje de evaluación no fue del 100% se sugiere ir a la tabla de descripción de niveles para entender con claridad a que se refiere cada nivel e inmediatamente después trabajar en las evidencias solicitas en la sección de recomendaciones.
			');			
		}
	}else{
		//texto 9 OPCX1 mostrar niveles con poco avance (ningún nivel con poco avance y nivel 1-8)
		darFormatoTexto('Como se observa en la Grafica 2, la tecnología es viable ya que no se identifican niveles con avances bajos, sin embargo, si el porcentaje de evaluación no fue del 100% se sugiere ir a la tabla de descripción de niveles para entender con claridad a que se refiere cada nivel e inmediatamente después desarrollar las evidencias y actividades sugeridas en la sección de recomendaciones.
		');
	}
}else{
	
	$contNivelesPocoAvance=0;
	$cadNiveles = "";
	//permite enlistar los niveles con poco avance
	for($index=0;$index<9;$index++){
		if($nivelesConPocoAvance[$index]==1){
			if($contNivelesPocoAvance==($numNivelesPocoAvance-1)){
				//agrega el ultimo nivel a la lista
				$cadNiveles = $cadNiveles.($index+1);
				break;
			}if($contNivelesPocoAvance==($numNivelesPocoAvance-2)){
				//agregar el penultimo nivel de la lista y el separado "y"
				$cadNiveles = $cadNiveles.($index+1)." y ";
				$contNivelesPocoAvance++;
			}else{
				//agrega los niveles intermedios si los hay
				$cadNiveles = $cadNiveles.($index+1).", ";
				$contNivelesPocoAvance++;
			}
		}	
	}
	$cadCursos = "";
	$cadServicios = "";
	$contCur = 0;
	$contSer = 0;
	if($nivelAlcanzado==0){
		//genera cadena de cursos y servicios cuando no se alcanzo un nivel
		while($row = $resultado11->fetch_assoc())
		{
			if($row['trl_tipos_producto_idtrl_tipos_producto']==1){
				if($contCur==($numCursosBasicos-1)){
					if(substr ( $row['nombre'] , 0 , 1) == "i"||substr ( $row['nombre'] , 0 , 1) == "I"){
						$cadCursos = substr ( $cadCursos , 0 , (strlen($cadCursos)-2))."e ".$row['nombre'];
					}else{
						$cadCursos = $cadCursos.$row['nombre'];
					}
				}else if($contCur==($numCursosBasicos-2))	{
					$cadCursos = $cadCursos.$row['nombre']." y ";
					$contCur ++;
				}else {
					$cadCursos = $cadCursos.$row['nombre'].", ";
					$contCur ++;
				}
			}else{
				if($contSer==($numSeviciosBasicos-1)){
					if(substr ( $row['nombre'] , 0 , 1) == "i"||substr ( $row['nombre'] , 0 , 1) == "I"){
						$cadServicios = substr ( $cadServicios , 0 , (strlen($cadServicios)-2))."e ".$row['nombre'];
					}else{
						$cadServicios = $cadServicios.$row['nombre'];
					}
				}else if($contSer==($numSeviciosBasicos-2))	{
					$cadServicios = $cadServicios.$row['nombre']." y ";
					$contSer ++;
				}else {
					$cadServicios = $cadServicios.$row['nombre'].", ";
					$contSer ++;
				}
			}
		}
	}else{
		if($numCursos>$numMaximoCS){
			$numCursosProvisional = $numCursos; //numero de cursos obtenidos en la consulta 
			#echo("total Cursos:".$numCursosProvisional);
			$numCursos = 0; //ahora el numero de cursos no dependera de los obtenidos en la consulta sino de los que se decidan incluir
			//inicialmente ningun curso aparecera
			for($i=0; $i< $numCursosProvisional;$i++){
				$apareceCS[$i] = false;
			}
			//se elegiran solo el numero de cursos que se establezca en la variable numMaximoCS
			while($numCursos<$numMaximoCS){
				do{
					//se elige un numero aleatorio que hace referencia a alguno de los cursos obtenidos en la consulta
					$numAleatorio = mt_rand(0,($numCursosProvisional-1));
				//si el curso ya fue elegido se escoje otro	
				}while ($apareceCS[$numAleatorio]==true); 
				// se establece cual es el curso elegido
				$apareceCS[$numAleatorio]=true;
				#echo("Curso:".$numAleatorio);
				// se incrementa el numero de cursos elejidos
				$numCursos++;
			}
			#echo("Cursos mostrados:".$numCursos);
			$contProductos = 0;
			$contProductosMostrados = 0;
			//genrar cadena con cursos de los niveles con poco avance
			while($row8 = $resultadoCur->fetch_assoc())
			{
				//solo apareceran algunos cursos de la consulta
				if($apareceCS[$contProductos]==true){
					if($contProductosMostrados==($numCursos-1)){
						if(substr ( $row8['nombre'] , 0 , 1) == "i"||substr ( $row8['nombre'] , 0 , 1) == "I"){
							$cadCursos = substr ( $cadCursos , 0 , (strlen($cadCursos)-2))."e ".$row8['nombre'];
						}else{
							$cadCursos = $cadCursos.$row8['nombre'];
						}
						$contProductosMostrados ++;
					}else if($contProductosMostrados==($numCursos-2))	{
						$cadCursos = $cadCursos.$row8['nombre']." y ";
						$contProductosMostrados ++;
					}else {
						$cadCursos = $cadCursos.$row8['nombre'].", ";
						$contProductosMostrados ++;
					}
				}
				$contProductos ++;
			}
			$numServicosProvisional = $numServicios; //numero de cursos obtenidos en la consulta 
			if($numServicios>$numMaximoCS){
				#echo("total servicios:".$numServicosProvisional);
				$numServicios = 0; //ahora el numero de cursos no dependera de los obtenidos en la consulta sino de los que se decidan incluir
				//inicialmente ningun curso aparecera
				for($i=0; $i< $numServicosProvisional;$i++){
					$apareceCS[$i] = false;
				}
				//se elegiran solo el numero de cursos que se establezca en la variable numMaximoCS
				while($numServicios<$numMaximoCS){
					do{
						//se elige un numero aleatorio que hace referencia a alguno de los cursos obtenidos en la consulta
						$numAleatorio = mt_rand(0,($numServicosProvisional-1));
					//si el curso ya fue elegido se escoje otro	
					}while ($apareceCS[$numAleatorio]==true); 
					// se establece cual es el curso elegido
					$apareceCS[$numAleatorio]=true;
					#echo("Servicio:".$numAleatorio);
					// se incrementa el numero de cursos elejidos
					$numServicios++;
				}
			}else{
				for($i=0; $i< $numServicosProvisional;$i++){
					$apareceCS[$i] = true;
				}
			}
			#echo ("servicios mostrados".$numServicios);
				$contProductos = 0;
				$contProductosMostrados = 0;
				//genrar cadena con cursos de los niveles con poco avance
				while($row9 = $resultadoSer->fetch_assoc())
				{
					if($apareceCS[$contProductos]==true){
						if($contProductosMostrados==($numServicios-1)){
							if(substr ( $row9['nombre'] , 0 , 1) == "i"||substr ( $row9['nombre'] , 0 , 1) == "I"){
								$cadServicios = substr ( $cadServicios , 0 , (strlen($cadServicios)-2))."e ".$row9['nombre'];
							}else{
								$cadServicios = $cadServicios.$row9['nombre'];
							}
							$contProductosMostrados ++;
						}else if($contProductosMostrados==($numServicios-2))	{
							$cadServicios = $cadServicios.$row9['nombre']." y ";
							$contProductosMostrados ++;
						}else {
							$cadServicios = $cadServicios.$row9['nombre'].", ";
							$contProductosMostrados ++;
						}
					}
					$contProductos ++;
				}
		}else {
			$contProductos = 0;
			//genrar cadena con cursos de los niveles con poco avance
			while($row8 = $resultadoCur->fetch_assoc())
			{
				if($contProductos==($numCursos-1)){
					$cadCursos = $cadCursos.$row8['nombre'];
				}else if($contProductos==($numCursos-2))	{
					$cadCursos = $cadCursos.$row8['nombre']." y ";
					$contProductos ++;
				}else {
					$cadCursos = $cadCursos.$row8['nombre'].", ";
					$contProductos ++;
				}
			}
			
			$contProductos = 0;
			//genrar cadena con cursos de los niveles con poco avance
			while($row9 = $resultadoSer->fetch_assoc())
			{
				if($contProductos==($numServicios-1)){
					$cadServicios = $cadServicios.$row9['nombre'];
				}else if($contProductos==($numServicios-2))	{
					$cadServicios = $cadServicios.$row9['nombre']." y ";
					$contProductos ++;
				}else {
					$cadServicios = $cadServicios.$row9['nombre'].", ";
					$contProductos ++;
				}
			}
		}
	}
	//Agregar texto cuando se identifica solo un nivel con poco avance 
	if($numNivelesPocoAvance==1){
		//texto 9 OPC2 mostrar niveles con poco avance (1 solo nivel bajo)
		darFormatoTexto("En la Gráfica 2 además del promedio general del proyecto también se observa el nivel (punto rojo) con áreas de oportunidad. Es notorio el pobre desarrollo en los aspectos relacionados con el nivel: {$cadNiveles}. Si se desea atender esta deficiencia y la tecnología sea viable se recomienda consultar los siguientes cursos: ".$cadCursos.". 
");
		$pdf->addPage();
		darFormatoTexto("Por otra parte si el perfil profesional del equipo de trabajo no está relacionado con los aspectos abordados en el nivel que presenta deficiencias, se siguiere acudir con expertos para consultar servicios concernientes con: ".$cadServicios.".
		");
	//Agregar texto cuando se identifican varios niveles con poco avance
	}else{
		
		if($nivelAlcanzado==0 && $numNivelesPocoAvance==9){
			//texto 9 OPC4 mostrar niveles con poco avance (todos los niveles son bajos, solo se muestran cursos y servicos basicos)
			darFormatoTexto("En la Gráfica 2 además del promedio general del proyecto también se observan los niveles (puntos rojos) con áreas de oportunidad. Como es lógico el presente proyecto tiene áreas de oportunidad en todos los niveles, por tal motivo se propone dirigir los esfuerzos en dar forma a proyectos tecnológicos y estudiar las generalidades de los primeros niveles TRL. Lo cursos ".$cadCursos.", pueden ser de ayuda para lograr estos objetivos.
			");
			//agrega un salto de linea solo cuando la hoja tenga suficiente texto
			if($pdf->getY()<138){
				darFormatoTexto("Por otra parte si el perfil profesional del equipo de trabajo no está relacionado con la planeación formal de un proyecto tecnológicos, se sugiere acudir con expertos para consultar servicios especializados como: ".$cadServicios.".
				");		
			}else{
				$pdf->addPage();
				darFormatoTexto("Por otra parte si el perfil profesional del equipo de trabajo no está relacionado con la planeación formal de un proyecto tecnológicos, se sugiere acudir con expertos para consultar servicios especializados como: ".$cadServicios.".
				");		
			}
			}else{
			//texto 9 OPC3 mostrar niveles con poco avance (algunos niveles son bajos)
			darFormatoTexto("En la Gráfica 2 además del promedio general del proyecto también se observan los niveles (puntos rojos) con áreas de oportunidad. Es notorio el pobre desarrollo en los aspectos relacionados con los niveles: {$cadNiveles}. Si se desea atender esta deficiencia y la tecnología sea viable se recomienda consultar los siguientes cursos: ".$cadCursos.". 
			");//agrega un salto de linea solo cuando la hoja tenga suficiente texto
			if($pdf->getY()<138){
				darFormatoTexto("Por otra parte si el perfil profesional del equipo de trabajo no está relacionado con la planeación formal de un proyecto tecnológicos, se sugiere acudir con expertos para consultar servicios especializados como: ".$cadServicios.".
				");
			}else{
			$pdf->addPage();
			darFormatoTexto("Por otra parte si el perfil profesional del equipo de trabajo no está relacionado con la planeación formal de un proyecto tecnológicos, se sugiere acudir con expertos para consultar servicios especializados como: ".$cadServicios.".
			");
			}
		}

	}
	
}


//-----------Sección 4---------------
$pdf->addPage();

//tabla 4
darFormatoSubtitulos('RECOMENDACIONES');

if($nivelAlcanzado==0){
	//texto 10 OPC1 descripcion seccion de recomendaciones (ningun nivel)
	darFormatoTexto('Para que la presente evaluación aporte al desarrollo del proyecto se muestran de manera ordenada las actividades generales a realizar en cada etapa de la tecnología, las cuales tienen como objetivo la obtención de los niveles no alcanzados.');	
}else if($nivelAlcanzado==9){
	//texto 10 OPC2 descripcion seccion de recomendaciones (nivel 9)
	darFormatoTexto('Para que la presente evaluación tenga validez es necesario presentar las evidencias que justifican el cumplimiento o avance de los niveles superados, por tal motivo en esta sección se enlistan los documentos que validan la madurez del proyecto evaluado. Dichas evidencias se basan en lo solicitado por diversas organizaciones de ciencia y tecnología en México.
	');
}else{
	//texto 10 OPC3 descripcion seccion de recomendaciones (niveles 1-8)
	darFormatoTexto('Para que la presente evaluación tenga validez es necesario presentar las evidencias que justifican el cumplimiento o avance de los niveles superados, por tal motivo en esta sección se enlistan los documentos que validan la madurez del proyecto evaluado. Dichas evidencias se basan en lo solicitado por diversas organizaciones de ciencia y tecnología en México.

	Por otra parte, se muestran de manera ordenada las actividades generales a realizar, que tienen como objetivo la obtención de los niveles no alcanzados por el proyecto "'.$tituloProyecto.'".
	');
}
//estilo y texto en viñetas para las recomendaciones
$column_width = $pdf->w-30;
$test1 = array();
$test1['bullet'] = chr(149);
$test1['margin'] = ' ';
$test1['indent'] = '     ';
$test1['spacer'] = 0;
$test1['text'] = array();
$test1['numRow'] = array();

//lectura de evidencias/recomendaciones 
$texto = "";

//bandera para definir si se cambia de evidencias a recomendaciones 
$seEnlistanRecomendaciones = false;
while($row = $resultado5->fetch_assoc())
{
		if($row['trl_niveles_idniveles'] != $nivelActual){
			//se agrega un encabezado cada que aparezcan evidencias/recomendaciones de otro nivel
			darFormatoTextoContenidoTabla();
			$pdf-> SetX(20);
			//permite agregar las evidencias/recomendaciones en forma de viñetas	
			$pdf->MultiCellBltArray(170,ANCHORENGLON,$test1,1,1);
			unset($test1['text']);
			unset($test1['numRow']);
			
			$test1['bullet'] = chr(149);
			$seAgregoPaginas = false;
			// se agrega una nueva pagina cuando no haya espacio suficiente para agregar las evidencias/ recomendaciones del siguente nivel 
			if($pdf->getY()>200){
				$pdf->addPage();
				//indica que se agrego una nueva pagina
				$seAgregoPaginas = true;
			}
			
			// solo se va a imprimir el encabezado de "documento" (evidencias) o "acciones" (recomendaciones) 
			//cuando sea el inicio de la tabla, cuando se aborde el nivel despues del alcanzado, cuando se agregue una nueva pagina
			if(($row['trl_niveles_idniveles']==1)||($row['trl_niveles_idniveles']==($nivelAlcanzado+1))||($seAgregoPaginas)){
				//si el nivel a evaluar ($row['trl_niveles_idniveles']) es mayor al nivel alcanzado se imprimiran el encabezado de recomendaciones,
				//si no se imprimira el encabezado de evidencias
				if($row['trl_niveles_idniveles']>$nivelAlcanzado){
					//encabezado para recomendaciones
					darFormatoSubtitulos("Acciones para alcanzar los niveles superiores:");
					$pdf->Ln(3);
					//se afirma que se comenzaran a mostrar recomendaciones en lugar de evidencias
					$seEnlistanRecomendaciones = true;
				}else{
					// encabezado para evidencias
					darFormatoSubtitulos("Documentación para justificar el nivel TRL obtenido y los niveles previos:");
					$pdf->Ln(3);
				}
			}
			darFormatoTextoEncabezadoTabla();
			$pdf-> SetX(20);
			$pdf->Cell(170,ANCHORENGLON,"Nivel ".$row['trl_niveles_idniveles'],1,1,'C',1);
			
		}
		$texto = $row['descr'];
		$test1['numRow'][] = numeroRenglonesTexto(191,$texto);
		$test1['text'][] = utf8_decode($row['descr']);
		
		// si se muestran recomendaciones no se usaran viñetas si no numeros
		if($seEnlistanRecomendaciones){
			$test1['bullet'] = 1;
		}
		
		$nivelActual = $row['trl_niveles_idniveles'];
}

			darFormatoTextoContenidoTabla();
			$pdf-> SetX(20);
			// se agregan las recomendaciones o evidencias del ultimo nivel	
			$pdf->MultiCellBltArray(170,ANCHORENGLON,$test1,1,1);


			
			
			
			

			
//-----------Sección 5---------------
$pdf->addPage();

darFormatoSubtitulos('CONCLUSIONES');

//texto descriptivo
darFormatoTexto('Gracias por ser usuario de esta plataforma de análisis de madurez de tecnología, la cual hemos diseñado para proporcionar un análisis puntual sobre el potencial de comercialización de una tecnología. Esperamos que esta información sea de alto valor para su proyecto, y ayudemos a optimizar sus esfuerzos para hacer llegar una tecnología al mercado en forma de una innovación.

Con este análisis, encontramos que su proyecto "'.$tituloProyecto.'" se basa en una tecnología con un nivel de madurez en TRL '. $nivelAlcanzado.', y cuenta con fortalezas claras, así como algunas áreas de oportunidad que podrá consultar para saber cómo optimizar su camino hacia el mercado.

Si desea recibir la retroalimentación de especialistas en las áreas tecnológica, comercial y de negocios, puede solicitar una sesión de revisión "uno a uno" sin costo en innovacion@techbr.com.mx, para profundizar en los detalles de su interés. También puede enviarnos sus dudas y recomendaciones para poder ofrecerle un análisis más robusto y personalizado.

Además de esta información, TB&R le ofrece otras aplicaciones en su plataforma para complementar este análisis, así como una oferta de capacitación y servicios con especialistas en toda la cadena de valor de la innovación, desde el laboratorio hasta el mercado, incluyendo métodos y estrategias de marketing tecnológico para asegurar que su propuesta de valor llegue a los usuarios indicados.

Consulté estas aplicaciones y cursos en www.techbusiness.com.mx y también puede visitar nuestras redes sociales para interactuar con nosotros o consultar contenidos de valor.

¡Gracias!
');

//---------sección 6-----------
$pdf->addPage();

darFormatoSubtitulos('BIBLIOGRAFÍA');

//estilo y texto en viñetas para las recomendaciones
$column_width = $pdf->w-30;
$test1 = array();
$test1['bullet'] = 1;
$test1['margin'] = ' ';
$test1['indent'] = '     ';
$test1['spacer'] = 0;
$test1['text'] = array();
$test1['numRow'] = array();

//mostrar lista de bibliografía en forma de viñetas
		darFormatoTextoContenidoTabla();
		$pdf-> SetX(20);
		$test1['text'][] = 'Altunok, T., & Cakmak, T. (2010). A technology readiness levels (TRLs) calculator software for systems engineering and technology management tool. Advances in Engineering Software, 41(5), 769-778.';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][0]);
		$test1['text'][] = 'Sauser, B., Verma, D., Ramirez-Marquez, J., & Gove, R. (2006, April). From TRL to SRL: The concept of systems readiness levels. In Conference on Systems Engineering Research, Los Angeles, CA (pp. 1-10).';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][1]);
		$test1['text'][] = 'Engel, D. W., Dalton, A. C., Anderson, K. K., Sivaramakrishnan, C., & Lansing, C. (2012). Development of technology readiness level (TRL) metrics and risk measures (No. PNNL-21737). Pacific Northwest National Lab.(PNNL), Richland, WA (United States).';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][2]);
		$test1['text'][] = 'Tugurlan, C., Kirkham, H., & Chassin, D. (2011, October). Software Technology Readiness for the Smart Grid. In Proceedings of the 29th Annual Pacific Northwest Software Quality Conference (PNSQC 2011) (pp. 203-212). (Tambien para descripciones)';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][3]);
		$test1['text'][] = 'Bakke, K. (2017). Technology Readiness leve use and understanding. Master thesis. University College South-East Norway.';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][4]);
		$test1['text'][] = 'NYSERDA, 2015. Technology and Comercialization Readiness calculator. DOE, Washington, USA.';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][5]);
		$test1['text'][] = 'Moorhouse, D. J. (2002). Detailed definitions and guidance for application of technology readiness levels. Journal of Aircraft, 39(1), 190-192.';
		$test1['numRow'][] = numeroRenglonesTexto(185,$test1['text'][6]);
		$pdf->MultiCellBltArray(170,ANCHORENGLON,$test1,0,1);
		
///////////////////////////////
//sintaxis para actualizar el número de reportes generados en la BD
$num_rep_gener++;
$query7 = "UPDATE trl_usuarios SET rep_leido='{$num_rep_gener}' WHERE idusuarioTRL={$usuario};";
$conex->query($query7);
// se cierra la conexion con la BD
$conex->close();


//mostrar el PDF en el navegador
$pdf->Output(); 
#$pdf->Output('Reporte.pdf'); 


	}else{
		echo "se ha excedido el número de generaciones de este reporte";
	}
}
else{
	echo "No es posible generar el reporte";
}
//------------funciones creada----------------------

//calcula el número de lineas que ocupará una multicell de acuerdo al largo y el texto
function numeroRenglonesTexto($w,$txt){
global $pdf;
$cw=&$pdf->CurrentFont['cw'];
if($w==0)
$w=$pdf->w-$pdf->rMargin-$pdf->x;
$wmax=($w-2*$pdf->cMargin)*1000/$pdf->FontSize;
$s=str_replace("\r",'',$txt);
$nb=strlen($s);
if($nb>0 and $s[$nb-1]=="\n")
$nb--;
$sep=-1;
$i=0;
$j=0;
$l=0;
$nl=1;
while($i<$nb)
{
$c=$s[$i];
if($c=="\n")
{
$i++;
$sep=-1;
$j=$i;
$l=0;
$nl++;
continue;
}
if($c==' ')
$sep=$i;
$l+=$cw[$c];
if($l>$wmax)
{
if($sep==-1)
{ 
if($i==$j)
$i++;
}
else
$i=$sep+1;
$sep=-1;
$j=$i;
$l=0;
$nl++;
}
else
$i++;
}
return $nl;
	}

//funcion para modificar marca de la grafica ciclo de vida
//llamado para crear las markers de una grafica
// retorna un arreglo como sigue array(width,border_color,fill_color,filename,imgscale)
// si en algun parametro se envian '', se ocupara el valor por defecto de dicho parametro
function FCallback($aYVal,$aXVal) {
	global 	$nivelAlcanzado, $posNivelesMadurez;
	global $format;
	if($nivelAlcanzado!=0){
    if( $aXVal == $posNivelesMadurez[$nivelAlcanzado-1]	){
		$c="#DE4D4C";
		$t=4;		
	}
    else {
		$c="#DE4D4C";
		#$c=$format[0][0][0];
		$t=0.5;
	}	
	}else{
		$c="#DE4D4C";
		$t=0.5;
	}	
	//$c='#FFFFFF';
    return array($t,$c,$c,'','');
}


//funcion para modificar la marca en el punto final de la grafica cumplimiento total que permite que el eje Y vaya de 0 a 100
//y para marcar los niveles con poco avance en la misma grafica
function FCallback1($aYVal, $aXVal) {
	global $nivelesConPocoAvance;
	global $numNivelesPocoAvance;
	$c="#177785";
	$t=3;
		//permite marcar de rojo los niveles con poco avance (ya identificados en el arreglo $nivelesConPocoAvance) en la grafica
		if($numNivelesPocoAvance>0){
			if(($aXVal<9)){
				if($nivelesConPocoAvance[$aXVal]==1){
					$c="#DE4D4C";
					$t=5;	
				}
			}			
		}

		//permite identificar el ultimo punto de la grafica y pintarlo del color del marco de la grafica y hacerlo pequeño para que no se destinga
	    if( ($aYVal == 100) && ($aXVal == 7.5)){
			$c="gray";
			$t=0.5;		
		}		
    return array($t,$c,$c,'','');
}

function darFormatoTextoEncabezadoTabla(){
	global $pdf;
	$pdf->SetFillColor(23,119,133);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial','B',11);
	
}

function darFormatoTextoContenidoTabla(){
	global $pdf;
	$pdf->SetFont('Arial','',10);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	
}

function darFormatoSubtitulos($subtitulo){
	global $pdf;
	$pdf->SetFont('Arial','B',11);
	$pdf-> SetX(20);
	$pdf->Cell(0,10, utf8_decode($subtitulo),0,0,'L');
	$pdf->Ln(10);
	
}

function darFormatoTexto($texto){
	global $pdf;
	$pdf->SetFont('Arial','',10);
	$pdf-> SetX(20);
	$pdf->MultiCell(170,ANCHORENGLON, utf8_decode($texto),0,'J',0);
	$pdf->Ln(3);
	
}

function dibujarBarras($color,$limInferior,$limSuperior){
	global $graph;
	$band=new PlotBand(HORIZONTAL,BAND_SOLID,$limInferior,$limSuperior,$color);
	$band->ShowFrame(false);
	$graph->Add($band);
}

//tipo= t, Tabla. tipo=g,Grafica. tipo = f , Figura
function darFormatoTitulosFiguras ($nombre, $tipo){
	global $pdf;
	global $numTablas;
	global $numGraficas;
	global $numFiguras;
	$pdf->SetFont('Arial','B',10);
	$pdf->Ln(2);
	$tituloCompleto='';
	
	if ($tipo=='t'){
		$tituloCompleto='Tabla '.$numTablas.': '.UTF8_DECODE($nombre);
		$numTablas++;
	}
	if ($tipo=='g'){
		$tituloCompleto=UTF8_DECODE('Gráfica '.$numGraficas.': '.$nombre);
		$numGraficas++;
	}
	if ($tipo=='f'){
		$tituloCompleto='Figura '.$numFiguras.': '.UTF8_DECODE($nombre);
		$numFiguras++;
	}
	$pdf->Cell(0,ANCHORENGLON,$tituloCompleto,0,1,'C',0);
}
//define el tamaño de los logos de cada nivel con respecto al número de renglones que tiene la descripcion de cada nivel
function obtenerTamañoLogosNiveles ($renglones){
	switch ($renglones) {
    case 1:
        return 5;
        break;
    case 2:
        return 10;
        break;
    case 3:
        return 12;
        break;
    case 4:
        return 14;
        break;
    case 5:
        return 16;
        break;
    case 6:
        return 18;
        break;
    case 7:
        return 20;
        break;
    case 8:
        return 22;
        break;
    case 9:
        return 24;
        break;
    case 10:
        return 26;
        break;
    case 11:
        return 28;
        break;
    case 12:
        return 30;
        break;
    case 13:
        return 32;
        break;
    case 14:
        return 34;
        break;
    case 15:
        return 36;
        break;	
    default:
       return 38;
}
	
}
function obtenerCadenaCurOSer( $numFilas){
	$cad ="";
	global $resultado;
	$contProductos = 0;
	while($row = $resultado->fetch_assoc())
	{
		if($contProductos==($numFilas-1)){
			$cad = $cad.$row['nombre'];
		}else if($contProductos==($numFilas-2))	{
			$cad = $cad.$row['nombre']." y ";
			$contProductos ++;
		}else {
			$cad = $cad.$row['nombre'].", ";
			$contProductos ++;
		}
	}
	return $cad;
}





?> 