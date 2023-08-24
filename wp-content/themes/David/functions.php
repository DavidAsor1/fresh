<?php
function custom_theme_enqueue_styles() {
    wp_enqueue_style('custom-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'custom_theme_enqueue_styles');

function test() {
   
	echo get_stylesheet_uri();
}
//add_action('init', 'test');
