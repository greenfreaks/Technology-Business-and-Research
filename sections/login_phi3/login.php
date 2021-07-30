<!doctype html>
<html>

<head>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/materialize.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@3.5.2/animate.min.css">
    <link rel="stylesheet" href="css/login.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Somos una empresa convencida de que el conocimiento científico y tecnológico y la suma de esfuerzos son la base del desarrollo social, económico y ambiental.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TB&amp;R - Technology Bussines and Research</title>

    <!------------------------------->
    <!--LLENADO DE ESTADOS----------->
    <!------------------------------->

    <!--<script type="text/javascript">
        function muestraEstado(str){
            var conexion;
            if(str == ""){
                document.getElementById("txtHint").innerHTML = "";
                return;
            }

            if(window.XMLHttpRequest){
                conexion = new XMLHttpRequest();
            }

            conexion.onreadystatechange = function (){
                if(conexion.readyState == 4 && conexion.status == 200){
                    document.getElementById("estado_container")innerHTML = conexion.responseText;
                }
            }
            conexion.open("GET", "php/estado.php?c=" + str, true);
            conexion.send();
        }
    </script>-->
</head>

<body>

    <nav id="indexnav" class="indexnav">
        <div class="nav-wrapper container">
            <a href="../index.html"><img class="header-logo hide-on-med-and-down" src="img/logos/logo-blanco-horizontal.png"></a>
            <a class="right logo-header-mobile orange-text hide-on-large-only" href="index.html"><img class="header-logo" src="img/logos/logo-blanco-horizontal.png"></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="nosotros.html">Nosotros</a></li>
                <li><a href="servicios.html">Servicios</a></li>
                <li><a href="blog.html">Blog</a></li>
                <!--<li><a target="_blank" href="#st-blog">Blog</a></li> -->
            </ul>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <ul id="nav-mobile" class="sidenav">
        <div class="nav-logo center"><a href="../index.html"><img class="header-logo" src="img/logo_p3.png"></a></div>
                <li><a href=nosotros.html">Nosotros</a></li>
                <li><a href="servicios.html">Servicios</a></li>
                <li><a href="blog.html">Blog</a></li>
                <!--<li><a target="_blank" href="#st-blog">Blog</a></li> -->
    </ul>

    <div class="fixed-action-btn">
        <a id="social-fab" class="btn-floating btn-large pulse">
            <i data-position="left" data-tooltip="Asistencia en línea" class="large material-icons box tooltipped">chat</i>
        </a>
        <ul>
            <li><a href="https://www.facebook.com/TechBusinessMx" target="_blank" class="btn-floating blue tooltipped" data-position="left" data-tooltip="Siguenos"> <img class="fab-img" src="img/social-networks/facebook-logo.png"> </a></li>
            <li><a href="https://m.me/TechBusinessMx/" target="_blank" class="btn-floating blue tooltipped" data-position="left" data-tooltip="Chatea on nosotros"> <img class="fab-img" src="img/social-networks/messenger.png"> </a></li>
            <!--            <li><a href="tel:017797966790" class="btn-floating green tooltipped" data-position="left" data-tooltip="Llamanos 7711530636"> <i class="large material-icons box">phone</i></a></li>-->
            <li><a href="mailto:blabla@bla.com" class="btn-floating purple tooltipped" data-position="left" data-tooltip="Email"> <i class="large material-icons box">mail</i></a></li>
            <!--            <li><a href="https://www.instagram.com/" target="_blank" class="btn-floating purple"> <img class="fab-img" src="img/instagram-logo.png"> </a></li>-->
            <!--            <li><a href="mailto:corptopsatelital@gmail.com" target="_top" class="btn-floating gray"><i class="material-icons">email</i></a></li>-->
        </ul>
    </div>

    <section class="banner">
        <img src="../img/talleres/bg-estrategia_comercial.jpg">
    </section>

    <form class="registro" id="registro" method="POST">
        <h6>Nombre(s)</h6>
        <input type="text" name="nombre">

        <h6>Apellido paterno</h6>
        <input type="text" name="apaterno">

        <h6>Apellido materno</h6>
        <input type="text" name="amaterno">

        <h6>Nivel de estudios</h6>
        <select name="nivel_estudios">
            <?php
                include "php/nivel_estudios.php"
            ?> 
        </select>

        <h6>Estado</h6>
        <select name="estado">
            <?php
                include "php/estado.php"
            ?>
        </select>

        </div>

        <h6>Soy</h6>
        <select name="tipo_usuario">
            <?php
                include "php/tipo_usuario.php";
            ?>
        </select>

        <h6>Correo electrónico</h6>
        <input type="email" name="email">

        <h6>Contraseña</h6>
        <input type="password" name="password">

        <h6>Repita contraseña (debe de ser igual a la anterior)</h6>
        <input type="password" name="v_password">

        <p>
            <label>
                <input id="ch_aec_capacitacion" name="aviso_privacidad" value="Cursos de Capacitación" type="checkbox" required/>
                <span>He leído y aceptado el <a href="#">aviso de privacidad</a></span>
            </label>
        </p>

        <input type="submit" name="register" value="¡Regístrate!">
    </form>
    <?php
        include "php/phi3_registro.php";
    ?>


    <footer class="page-footer custom-footer">
        <div class="container">
            <div class="row custom-row">
                <div class="col l5 s12">
                    <h6 class="white-text"><strong>Technology Bussines and Research</strong></h6>
                    <p class="grey-text text-lighten-4 texto-footer justify">Somos una empresa convencida de que el conocimiento científico y tecnológico y la suma de esfuerzos son la base del desarrollo social, económico y ambiental.</p>
                </div>
                <div class="col l4 s12 ">
                    <h6 class="white-text"><i class="material-icons">location_on</i>Ubícanos en:</h6>

                    <p class="texto-footer"><a class="white-text" target="_blank" href="https://goo.gl/maps/mRMefDf6c7U2">Álvaro Obregón 56, Col. Atempa, Tizayuca, Hidalgo. CP 43808</a><br> Teléfono:<a class="white-text " href="tel:017797966790">01 779 796 6790</a></p>
                </div>
                <div class="col l3 s12">
                    <h6 class="white-text">Más información</h6>
                    <ul>
                        <!--                        <li><a class="white-text btn-help" href="#!">Ayuda</a></li>-->
                        <li><a class="white-text" href="http://www.techbusiness.com.mx/politica_de_privacidad.html" target="_blank">Política de Privacidad</a></li>
                    </ul>
                    <div class="row center">
                        <div class="col s2"><a href="https://www.facebook.com/TechBusinessMx" target="_blank"><img class="social-footer" src="../img/social-networks/facebook-logo.png"></a> </div>
                        <div class="col s2"><a href="https://twitter.com/TechBusinessMx" target="_blank"><img class="social-footer" src="../img/social-networks/twitter-logo-silhouette.svg"></a> </div>
                        <div class="col s2"><a href="https://mx.linkedin.com/company/technology-business-and-research-sapi-de-cv" target="_blank"><img class="social-footer" src="../img/social-networks/linkedin-logo.svg"></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                <p class="right">Made by <a class="orange-text text-lighten-3">Mario Sandoval Velázquez</a> </p>
                <p>©Technology Bussines &amp; Research 2017</p>
            </div>
        </div>
    </footer>


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
    <!--    <script src="js/materialize.js"></script>-->
    <script src="js/init.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-137988434-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-137988434-1');
    </script>


</body></html>