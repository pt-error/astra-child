<?php

use ScssPhp\ScssPhp\Compiler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Require SCSS Interpreter
require_once('vendor/scssphp/scss.inc.php');
require_once('vendor/scssphp/example/Server.php');

function check_for_recompile($filename_scss,$import = false){
    $fullPath = __DIR__ . '/scss';
    $cachePath = $fullPath.'/scss_cache';
    $filename_css = 'theme-style.min.css';
    if (!file_exists($cachePath)) {
        mkdir($cachePath, 0644, true);
    }
    if( filemtime($fullPath.'/'.$filename_scss) >  filemtime($fullPath.'/../'.$filename_css) || filesize($fullPath.'/../'.$filename_css) == 0) {
        $directoryMain = $fullPath;

        if($import === true){
            compileCss($fullPath.'/'.'main.scss', $fullPath.'/../'.$filename_css);
        }else{
            compileCss($fullPath.'/'.$filename_scss, $fullPath.'/../'.$filename_css);
        }

        return true;
    }
    return false;
}


function compileCss($in, $out) {
    $compiler = new Compiler();

    $inContent = file_get_contents($in);

    $compiler->setImportPaths(__DIR__ . '/scss');

    $css = $compiler->compileString($inContent)->getCss();

    file_put_contents($out, $css);
} 

// Generiertes Stylesheet einfügen und veränderungen überwachen
add_action( 'wp_enqueue_scripts', 'pte_enqueue_styles' );
function pte_enqueue_styles() {
    check_for_recompile('main.scss',false);
    check_for_recompile('_projectvariables.scss',true);
    check_for_recompile('_project.scss',true);
    wp_enqueue_style( 'pte-styles', get_stylesheet_directory_uri() . '/' . 'theme-style.min.css' , array('astra-theme-css'), filemtime(get_stylesheet_directory() . '/theme-style.min.css') );
}

add_action( 'wp_enqueue_scripts', 'pte_enqueue_scripts' );
function pte_enqueue_scripts() {
    wp_enqueue_script( 'pte-scripts', get_stylesheet_directory_uri() . '/js/main.js', array(), filemtime(get_stylesheet_directory() . '/js/main.js'));
}


//deactivates AUTHOR-API-EXPOSING
add_filter( 'rest_endpoints', function( $endpoints ){
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});

add_filter('upload_mimes', 'pte_mime_types');
function pte_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}