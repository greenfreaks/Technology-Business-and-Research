<!doctype html>
<html <?php language_attributes(); ?>>

<head>

    <?php wp_head(); ?>

    <meta charset="<?php bloginfo("charset"); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php bloginfo("name"); ?> -
        <?php $categories = get_the_category();
 
        if ( ! empty( $categories ) ) {
            echo esc_html( $categories[0]->name );   
        }?>
        <?php wp_title("-"); ?></title>
    <!--    <meta name="description" content="<?php bloginfo("description"); ?>">-->
    <meta name="description" content="<?php $myExcerpt = strip_tags(get_the_excerpt()); echo $myExcerpt; ?>">
    <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' ); ?>
    <!--    <meta name="twitter:image" content="<?php echo $url ?>" />-->

    <meta property="og:title" content="<?php wp_title(""); ?>">
    <meta property="og:description" content="<?php $myExcerpt = strip_tags(get_the_excerpt()); echo $myExcerpt; ?>">
    <meta property="og:image" content="<?php echo $url ?>">

</head>

<body>

    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2&appId=2617482334946631&autoLogAppEvents=1"></script>

    <nav>
        <div class="nav-wrapper container">
            <a href="http://www.techbusiness.com.mx/index.html"><img class="header-logo hide-on-med-and-down" src="http://www.techbusiness.com.mx/img/logos/logo-blanco-horizontal.png"></a>
            <a class="right logo-header-mobile orange-text hide-on-large-only" href="http://www.techbusiness.com.mx/index.html"><img class="header-logo" src="http://www.techbusiness.com.mx/img/logos/logo-blanco-horizontal.png"></a>
            <ul class="right hide-on-med-and-down">

                <li><a href="http://www.techbusiness.com.mx/modulos/nosotros/nosotros.html">Nosotros</a></li>
                <li><a href="http://www.techbusiness.com.mx/modulos/catalogo/talleres.html">Talleres</a></li>
                <li><a href="http://www.techbusiness.com.mx/modulos/catalogo/servicios.html">Servicios</a></li>
                <li class="active"><a target="_blank" href="http://blog.tecnotransfer.com.mx/">Blog</a></li>
                <!--                <li><a href="modulos/plataforma/registro.html" class="waves-effect waves-light btn">Registro</a></li>-->
            </ul>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <ul id="nav-mobile" class="sidenav">
        <div class="nav-logo center"><a href="http://www.techbusiness.com.mx/index.html"><img class="header-logo" src="http://www.techbusiness.com.mx/img/logo_p3.png"></a></div>
        <li><a href="http://www.techbusiness.com.mx/modulos/nosotros/nosotros.html">Nosotros</a></li>
        <li><a href="http://www.techbusiness.com.mx/modulos/catalogo/talleres.html">Talleres</a></li>
        <li><a href="http://www.techbusiness.com.mx/modulos/catalogo/servicios.html">Servicios</a></li>
        <li class="active"><a target="_blank" href="http://blog.tecnotransfer.com.mx/">Blog</a></li>
        <!--        <li><a href="" class="waves-effect waves-light btn">Registro/Login</a></li>-->
    </ul>

    <nav>
        <div class="nav-wrapper">
            <?php get_search_form();?>
        </div>
    </nav>
