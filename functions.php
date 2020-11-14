<?php

// Enqueue style
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );
function child_enqueue_styles() {
	wp_enqueue_style( 'mikecodesastrachild-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), null, 'all' );
}
