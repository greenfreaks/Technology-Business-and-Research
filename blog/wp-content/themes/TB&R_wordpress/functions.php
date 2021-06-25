<?php
	
function awesome_script_enqueue() {
	
	wp_enqueue_style('MaterializeIcons', "https://fonts.googleapis.com/icon?family=Material+Icons", array(), '1.0.0', 'all');
	wp_enqueue_style('MaterializeCSS', "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css", array(), '1.0.0', 'all');
	wp_enqueue_style('customstyle', get_template_directory_uri() . '/css/tbr.css', array(), '1.0.0', 'all');
    
    
	wp_enqueue_script('jquery',  "https://code.jquery.com/jquery-3.2.1.min.js", array(), '3.2.1', true);
	wp_enqueue_script('MaterializeJS',  "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js", array(), '3.2.1', true);
	wp_enqueue_script('customjs', get_template_directory_uri() . '/js/tbr.js', array(), '1.0.0', true);

}

add_action( 'wp_enqueue_scripts', 'awesome_script_enqueue');

function tbr_theme_setup(){
    
    add_theme_support("menus");
    register_nav_menu("primary", "Barra de menu principal");
    register_nav_menu("secondary", "Menu en el footer");
    
}

add_action("init", "tbr_theme_setup");

add_theme_support("post-thumbnails");
add_theme_support("post-formats", array("aside","image","video"));
add_post_type_support( 'post', 'excerpt' );
add_theme_support("html5", array("search-form"));

/*
====================
Custom Post Type
====================
*/

/* function tbr_custom_post_type(){
    $labels = array(
        "name" => "EFIDT2019",
        "singular_name" => "EFIDT2019",
        "add_new" => "Nuevo Post del EFIDT 2019",
        "all_items" => "Todo EFIDT 2019",
        "edit_item" => "editar EFIDT",
        "new_item" =>"nuevo EFIDT",
        "view_item"=>"ver EFIDT",
        "search_item"=>"buscar EFIDT",
        "not_found"=>"no se encontro EFIDT",
        "not_found_in_trash"=>"EFIDT No se encontro en la papelera ",
        "parent_item_colon"=>"parent item"
    );

    $args = array(
        "labels" => $labels,
        "public" => true,
        "has_archive" => true,
        "publicly_queryable" => true,
        "query_var" => true,
        "rewrite" => true,
        "capability_type" => "post",
        "hierarchical" => false,
        "support" => array
    );
    
} */
