<?php

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'vanlife-austria-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), null, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );
