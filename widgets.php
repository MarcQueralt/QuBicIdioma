<?php

/**
 * Inits all widgets
 * @since 0.2
 */
function QuBicIdioma_widgets_init()
{
    return register_widget( 'QuBicIdioma_Chooser_Widget' );
}

class QuBicIdioma_Chooser_Widget extends WP_Widget
{

    /**
     * processes the widget
     */
    function QuBicIdioma_Chooser_Widget()
    {
        $widget_ops = array(
            'classname' => 'QuBic_Idioma',
            'description' => __( 'Shows a language selector as an accessible selectbox based in jQuery', QBC_IDIOMA_TEXT_DOMAIN )
        );
        $this->WP_Widget( 'QuBic_Idioma', __( 'Language chooser', QBC_IDIOMA_TEXT_DOMAIN ), $widget_ops );
    }

    function form( $instance )
    {
        parent::form( $instance );
    }

    function update( $new_instance, $old_instance )
    {
        parent::update( $new_instance, $old_instance );
    }

    function widget( $args, $instance )
    {
        global $blog_id;
        $llista = QuBicIdioma_obtenir_blocs_actius();
        $output = '<div class="QuBic_Idioma_selector_container">';
        $output.='<form action="#">';
        $output.='<select id="QuBic_Idioma_selector" onchange="QuBic_Idioma_selectorOnChange(this.options[this.selectedIndex].value);">';
        foreach ( $llista as $linia ):
            $output.='<option ';
            if ( $linia['blog_id'] == $blog_id ):
                $output.='selected ';
            endif;
            $output.='value="';
            $output.=QuBicIdioma_crearURL($linia['domain'],$linia['path']);
            $output.='">';
            $output.=$linia['language'];
            $output.='</option>';
        endforeach;
        $output.='</select>';
        $output.='</form>';
        $output.='</div>';
        echo $output;
    }

}
?>
<?php
/**
* Devuelve el sufijo para la selección del idioma por defecto
* @param mixed $idioma objeto de idioma que devuelve xili-language
* @param mixed $prefix string que precederá al sufijo
* @since 20110728
*/
/*
private function sufixIdioma( $idioma, $prefix='?' )
{
return $prefix . 'lang=' . $idioma->slug;
}

function widget( $args, $instance )
{
global $wp_query;

if ( class_exists( 'xili_language' ) ):
global $xili_language;
$idiomaActual = get_cur_language( get_queried_object_id() );
if ( !isset( $idiomaActual ) || !$idiomaActual ):
$idiomaActual = $xili_language->curlang;
endif;
$idiomes = xili_get_listlanguages();
$linksPosts = xiliml_the_other_posts( get_queried_object_id(), '', '', 'array' );
if ( !$linksPosts || !isset( $linksPosts ) ):
$linksPosts = array( );
endif;
$menu = array( );
foreach ( $idiomes as $idioma ):
if ( $idioma->slug == $idiomaActual ):
$menu[] = array(
'name' => $idioma->description,
'title' => sprintf( __( 'Quedar-se a la pàgina actual que ja és en %s', 'selectorIdioma' ), $idioma->description ),
'link' => '#',
'slug' => $idioma->slug
);
elseif ( array_key_exists( $idioma->slug, $linksPosts ) ):
$post_id = $linksPosts[$idioma->slug];
$post = get_post( $post_id );
$link = get_permalink( $post_id );
$menu[] = array(
'name' => $idioma->description,
'title' => sprintf( __( 'Accés a la versió en %s de %s', 'selectorIdioma' ), $idioma->description, $post->post_title ),
'link' => $link . $this->sufixIdioma( $idioma ),
'slug' => $idioma->slug
);
else:
$menu[] = array(
'name' => $idioma->description,
'title' => sprintf( __( 'Accés a la pàgina principal amb idioma %s', 'selectorIdioma' ), $idioma->description ),
'link' => get_home_url() . $this->sufixIdioma( $idioma ),
'slug' => $idioma->slug
);
endif;
endforeach;
endif;

}

}

if ( !is_admin() ):
wp_enqueue_script( 'selectorIdioma-combobox', plugin_dir_url( __FILE__ ) . 'jquery.ui.selectmenu.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget' ), '', true );
wp_enqueue_script( 'selectorIdioma', plugin_dir_url( __FILE__ ) . 'selectorIdioma.js', array( 'selectorIdioma-combobox' ), '', true );
wp_enqueue_style( 'selectorIdioma-UI', plugin_dir_url( __FILE__ ) . 'jqueryUI.css', '', '' );
wp_enqueue_style( 'selectorIdioma', plugin_dir_url( __FILE__ ) . 'selectmenu.css', '', '' );
endif;
add_action( 'widgets_init', create_function( '', "return register_widget('selectorIdioma');" ) );
*/