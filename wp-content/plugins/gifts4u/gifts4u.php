<?php
/**
 * Plugin Name: Gifts4U
 * Description: Code voor eindwerk.
 * Version: 1.3
 * Author: Bosschem Indy
 */


function my_scripts() {
  wp_register_style('Gifts4U_styling', '/wp-content/plugins/gifts4u/assets/css/style.css', array(), rand(111,9999), 'all' );
  wp_enqueue_style( 'Gifts4U_styling');
}
add_action( 'wp_enqueue_scripts', 'my_scripts' );


$dir = 'wp-content/plugins/gifts4u/';
foreach (glob($dir . "includes/*.php") as $filename) {
  require_once($filename);
}
?>
