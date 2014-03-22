<?php
/*
 * Plugin Name: Paulund Syntax Highlighter
 * Plugin URI: http://www.paulund.co.uk
 * Description: A widget allows you to display code on your Website
 * Version: 1.1
 * Author: Paul Underwood
 * Author URI: http://www.paulund.co.uk
 * License: GPL2
 * Stable tag: trunk

    Copyright 2012  Paul Underwood

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License,
    version 2, as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

add_action('wp_enqueue_scripts', 'pu_load_styles');
/**
 * [pu_load_styles description]
 * @return [type] [description]
 */
function pu_load_styles()
{
    wp_enqueue_script( 'prism_js', plugins_url( '/js/prism.js' , __FILE__ ) , array( 'jquery' ), NULL, true );
    wp_enqueue_style( 'prism_css', plugins_url( '/css/prism.css' , __FILE__ ) );
}

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );
remove_filter('the_content', 'wptexturize');

remove_filter('comment_text', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');

add_shortcode( 'markup' , 'paulund_hightlight_html' );
add_shortcode( 'css' , 'paulund_hightlight_css' );
add_shortcode( 'javascript' , 'paulund_hightlight_javascript' );
add_shortcode( 'php' , 'paulund_hightlight_php' );

function paulund_hightlight_html($atts, $content = null)
{
    return pu_encode_content('markup', $content);
}

function paulund_hightlight_css($atts, $content = null)
{
    return pu_encode_content('css', $content);
}

function paulund_hightlight_javascript($atts, $content = null)
{
    return pu_encode_content('javascript', $content);
}

function paulund_hightlight_php($atts, $content = null)
{
    return pu_encode_content('php', $content);
}

function pu_encode_content($lang, $content)
{
    $find_array = array( '&#91;', '&#93;' );
    $replace_array = array( '[', ']' );

    $content = preg_replace_callback( '|(.*)|isU', 'pu_pre_entities', trim( str_replace( $find_array, $replace_array, $content ) ) );

    $content = str_replace('#038;', '', $content);

    return sprintf('<pre class="language-%s"><code>%s</code></pre>', $lang, $content);
}

function pu_pre_entities( $matches ) {
    return str_replace( $matches[1], htmlspecialchars( $matches[1]), $matches[0] );
}

?>