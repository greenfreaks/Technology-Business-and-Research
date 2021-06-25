<?php
/*
 * Template Name: Comentarios Desactivados
 * Template Post Type: post
 */
  
 get_header();  ?>

<?php if(have_posts()):
    
    while(have_posts()):

        the_post(); ?>

<div itemscope itemtype='http://schema.org/Article'>

    <div class="section no-pad-bot">
        <div class="container">
            <h4 class="header center" itemprop='name headline'><br><span class="texto-azul-tbr"><?php the_title(); ?></span></h4>
            <br>
            <div class="row center">
                <div class="header col s12 l4">
                    Escrito por:

                    <span itemscope itemprop='author' itemtype='http://schema.org/Person'>
                        <span itemprop='name'>
                            <a itemprop='url' href='#!'><?php the_author(); ?></a>
                        </span>
                    </span>
                </div>
                <div class="header col s12 l4">
                    Publicado por:
                    <span itemscope itemprop='publisher' itemtype='http://schema.org/Organization'>
                        <span itemprop='name'><a itemprop='url' href='http://techbusiness.com.mx'>TB&amp;R</a></span>
                        <img class="hide" style="height: 50px;" itemprop='logo' src='http://www.techbusiness.com.mx/img/logos/logo%20Vectorizadopeque-02.png' />
                    </span>
                </div>
                <div class="header col s12 l4">
                    Fecha de publicación:
                    <time datetime='<?php the_time('d/m/Y'); ?>' itemprop='datePublished'><?php the_time('j F, Y'); ?></time>
                    <br>
                    Fecha de modificación:
                    <time datetime='<?php the_modified_time('d/m/Y'); ?>' itemprop='dateModified'><?php the_modified_time('d/m/Y'); ?></time>
                    <sub>Publicado en <?php the_category(); ?></sub>
                </div>
            </div>
        </div>
    </div>

    <div class="section center hide">
        <div class="container">
            <img itemprop="image" src="<?php the_post_thumbnail_url(); ?>" class="responsive-img" />
        </div>
    </div>

    <div class="section no-pad">
        <div class="container">
            <div class="row" itemprop='articleBody'>
                <div class="col s12">
                    <?php the_content() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h5>Comparte este articulo:</h5>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.techbusiness.com.mx%2F&quote=" title="Comparte en Facebook" target="_blank" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&quote=' + encodeURIComponent(document.URL)); return false;" style="background-color: rgba(0,0,0,0);" class="btn-floating btn-large"> <img class="fab-img" src="https://camo.githubusercontent.com/e6d2040c65e8c6f4da10db72436cf9a1196e43ae/68747470733a2f2f6564656e742e6769746875622e696f2f537570657254696e7949636f6e732f696d616765732f7376672f66616365626f6f6b2e737667"></a>

                    <a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fwww.techbusiness.com.mx%2F&text=:%20http%3A%2F%2Fwww.techbusiness.com.mx%2F" target="_blank" title="Comparte en Twitter" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20'  + encodeURIComponent(document.URL)); return false;" style="background-color: rgba(0,0,0,0);" class="btn-floating btn-large"> <img class="fab-img" src="https://camo.githubusercontent.com/9bbddae7e626bda73c943e06b4568a7a02e193b4/68747470733a2f2f6564656e742e6769746875622e696f2f537570657254696e7949636f6e732f696d616765732f7376672f747769747465722e737667"></a>

                    <a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fwww.techbusiness.com.mx%2F&title=&summary=&source=http%3A%2F%2Fwww.techbusiness.com.mx%2F" target="_blank" title="Comparte en LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;" style="background-color: rgba(0,0,0,0);" class="btn-floating btn-large"> <img class="fab-img" src="https://camo.githubusercontent.com/45e6bebceba49c2cf76b1b3770b1adbe24e6c454/68747470733a2f2f6564656e742e6769746875622e696f2f537570657254696e7949636f6e732f696d616765732f7376672f6c696e6b6564696e2e737667"></a>

                    <a href="mailto:?subject=&body=:%20http%3A%2F%2Fwww.techbusiness.com.mx%2F" target="_blank" title="Enviar por correo electrónico" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;" class="btn-floating btn-large"><i class="material-icons ">mail</i></a>

                </div>
            </div>
        </div>
    </div>

</div>

<?php endwhile;

endif;
?>


<?php get_footer(); ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<!--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-137988434-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-137988434-1');

    </script>-->
