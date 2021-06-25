<?php get_header(); ?>

<div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
        <div class="container">
            <h2 class="header center hide-on-med-and-down" itemprop='name headline'><br><span class="texto-roj-tbr white-text">Resultados de Busqueda TB&amp;R</span></h2>
            <h4 class="header center hide-on-large-only"><br><span class="texto-roj-tbr Fecha de publicación:">Resultados de Busqueda</span></h4>
        </div>
    </div>
    <div class="parallax"><img itemprop="image" src="http://www.techbusiness.com.mx/img/bg/bg-cta-gray.jpg" alt="fondo azul con olas hexagonales"></div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <?php 
            
    if(have_posts()):
    
        while(have_posts()):

            the_post(); ?>

            <div class="col s12">
                <div class="card grey darken-2">
                    <div class="card-content white-text">
                        <a target="_blank" class="card-title negritas" href="<?php the_title(esc_url(get_permalink())); ?>"><?php the_title();?></a>
                        <sub>Publicado: <?php the_time('j F, Y'); ?> <?php the_time('g:i a'); ?>, en <?php the_category(); ?></sub>
                        <p class="justify"><?php the_excerpt();?></p>
                    </div>
                    <div class="card-action">
                        <a href="<?php the_title(esc_url(get_permalink())); ?>">Leer más</a>
                    </div>
                </div>
            </div>

            <?php endwhile;

    endif;
    ?>
        </div>
    </div>
</div>


<?php get_footer(); ?>
