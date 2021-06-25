<?php get_header(); ?>

<div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
        <div class="container">
            <h2 class="header center hide-on-med-and-down" itemprop='name headline'><br><span class="texto-roj-tbr white-text">BLOG TB&amp;R</span></h2>
            <h4 class="header center hide-on-large-only"><br><span class="texto-roj-tbr Fecha de publicación:">BLOG TB&amp;R</span></h4>
        </div>
    </div>
    <div class="parallax"><img itemprop="image" src="http://www.techbusiness.com.mx/img/bg/bg-cta.png" alt="fondo azul con olas hexagonales"></div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <?php 
            
    if(have_posts()): $i=0;
    
        while(have_posts()):

            the_post(); ?>

            <?php if(has_post_thumbnail()): ?>
            <div class="col s12">
                <div class="card grey darken-2">
                    <div class="card-content white-text">
                        <div class="row">
                            <div class="col s12 l6">
                                <img class="responsive-img" src="<?php the_post_thumbnail_url(); ?>" />
                            </div>
                            <div class="col s12 l6">
                                <a target="_blank" class="card-title negritas white-text" href="<?php echo get_permalink(); ?>"><?php the_title();?></a>
                                <sub>Publicado: <?php the_time('j F, Y'); ?> <?php the_time('g:i a'); ?>, en <?php the_category(); ?></sub>
                                <p class="justify"><?php the_excerpt();?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-action center">
                        <a class="red-text negritas" href="<?php echo get_permalink(); ?>">Leer más</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!has_post_thumbnail()): ?>
            <div class="col s12">
                <div class="card grey darken-2">
                    <div class="card-content white-text">
                        <a target="_blank" class="card-title negritas white-text" href="<?php echo get_permalink(); ?>"><?php the_title();?></a>
                        <sub>Publicado: <?php the_time('j F, Y'); ?> <?php the_time('g:i a'); ?>, en <?php the_category(); ?></sub>
                        <p class="justify"><?php the_excerpt();?></p>
                    </div>
                    <div class="card-action center">
                        <a class="red-text negritas" href="<?php echo get_permalink(); ?>">Leer más</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php $i++; endwhile;?>

            <div class="col s12">
                <ul class="pagination">
                    <li><?php next_posts_link()?></li>
                    <li><?php previous_posts_link()
                        ?></li>
                </ul>
            </div>

            <?php endif;
    ?>
        </div>
    </div>
</div>


<?php get_footer(); ?>
