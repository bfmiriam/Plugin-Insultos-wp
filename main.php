<?php
/*
Plugin Name: Plugin insultos
Plugin URI:  http://link to your plugin homepage
Description: This plugin replaces words with your own choice of words.
Version:     1.2
Author:      Miriam
Author URI:  http://link to your website
License:     GPL2 etc
License URI: https://link to your plugin license
*/

function cambiar_palabra($content){
    
    
     global $wpdb;
     
    //hacemos una consulta a la tabla de la base de datos para recoger la palabras censurables y cambiarlas
     $texto = $wpdb->get_results( "SELECT text FROM wp5_insultosbd");

     $opcion = $wpdb->get_results( "SELECT opcion FROM wp5_insultosbd");
     
	
    //recorremos los post y suplimos los insultos si es que los hay
     for ($i =0; $i < sizeOf($texto); $i++) {

        $search =$texto[$i]->text;
      
        $replace =$opcion[$i]-> opcion;
     
        $text =str_replace($search,$replace,$content);
     
    }

    return $text;


}
add_filter( 'the_content', 'cambiar_palabra' );

 
function insultos_table_init()
{
    //creamos la tabla para recoger los insultos y sus variantes y añadimos valores
    function insultos_shortcode($atts = [], $content = null)
    {
        // do something to $content
        $content = "<div id='insultos'><script>alert('Hi3');</script></div>";
        
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'insultosbd';

        $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL,
          text text NOT NULL,
          opcion text NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        
        $wpdb->insert( $table_name, array( 
       'id' => '1',
       'text' => 'idiota',
       'opcion'=> 'cortito'
          )
         );
         
        $wpdb->insert( $table_name, array( 
       'id' => '2',
       'text' => 'subnormal',
       'opcion'=> 'no muy listo'
          )
         );
        // always return
        return $content;
       
    }
    add_shortcode('insultos', 'insultos_shortcode');
}
add_action('init', 'insultos_table_init');

?>