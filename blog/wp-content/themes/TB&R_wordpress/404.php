<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>

<div class="parallax-container">
    <div class="section no-pad-bot">
        <div class="container">
            <h1 class="header center teal-text text-lighten-2"><br><span class="red-text">ERROR 404</span></h1>
            <div class="row center">
                <h5 class="header col s12">La página que estas intentanto acceder no existe</h5>
            </div>
        </div>
    </div>
    <div class="parallax"><img src="http://www.techbusiness.com.mx/img/bg/bg-cta.png" alt="fondo azul con olas hexagonales"></div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12 center">
                <h5>Para encontrar una solucion a este error te sugerimos las siguientes opciones:</h5>
            </div>
        </div>
        <div class="row">
            <div class="col s12 l6">
                <div class="card">
                    <div class="card-content center">
                        <i class="material-icons medium">contact_phone</i>
                        <h5><strong>Home</strong></h5>
                        <p class="justify">Regresa a nuestra <a class="negritas" href="http://www.techbusiness.com.mx/index.html">pagina de inicio</a> y sigue los menus y links para encontrar la pagina que intentas acceder.</p>
                    </div>
                </div>
            </div>
            <div class="col s12 l6">
                <div class="card">
                    <div class="card-content  center">
                        <i class="material-icons medium ">help</i>
                        <h5 class="center"><strong>Contactanos</strong></h5>

                        <p class="justify">Contacta con nosotros, haciendo clic en el botón pulsante en la esquina inferior derecha. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
