<?php

/**
 * Gets blog and language information sort based on $criteri
 * @since 0.2
 * updated 0.4 added blog status control.
 * @param string $criteri order field used to sort
 * @return array contains a record for each blog with fields ID,name,address,order
 * @uses QuBicIdioma_obtenir_opcions_bloc
 * @uses get_blog_list
 */
function QuBicIdioma_obtenir_blocs( $criteri='ordre' )
{
    $info = array( );
    $blocs = get_blog_list();
    foreach ( $blocs as $bloc ):
        $opcions = QuBicIdioma_obtenir_opcions_bloc( $bloc['blog_id'] );
        $detalls = get_blog_details( $bloc['blog_id'] );
        $info[] = array(
            'blog_id' => $bloc['blog_id'],
            'domain' => $bloc['domain'],
            'path' => $bloc['path'],
            'actiu' => (isset( $opcions['literal'] ) && (1 == $detalls->public)),
            'language' => $opcions['literal'],
            'ordre' => isset( $opcions['ordre'] ) ? $opcions['ordre'] : 99,
            'details' => $detalls,
        );
    endforeach;
    $compare = makeSortFunction( $criteri );
    usort( $info, $compare );
    return $info;
}

/**
 * Determines than a blog is active
 * @param array $a
 * @return boolean
 */
function QuBicIdioma_es_bloc_actiu( $a )
{
    return $a['actiu'];
}

/**
 * Gets blog and language information with actiu true and order by $criteri
 * @since 0.2
 * @param string $criteri
 */
function QuBicIdioma_obtenir_blocs_actius( $criteri='ordre' )
{
    $info = QuBicIdioma_obtenir_blocs( $criteri );
    $info = array_filter( $info, 'QuBicIdioma_es_bloc_actiu' );
    return $info;
}

/**
 * Create a sort by field function
 * @param string $field the field used to sort
 * @return function
 * @since 0.2
 */
function makeSortFunction( $field )
{
    $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
    return create_function( '$a,$b', $code );
}

/**
 * Gets blog multiidioma options
 * @since 0.2
 * @param integer $blog_id
 * @return array a record for each of the multilanguage options of the blog 
 */
function QuBicIdioma_obtenir_opcions_bloc( $blog_id )
{
    return get_blog_option( $blog_id, QBC_IDIOMA_OPTIONS );
}

/**
 * Creates the URL that corresponds to a bloc based on its domain and path info
 * @param string $domain blog's domain
 * @param string $path blog's path
 * @return string
 */
function QuBicIdioma_crearURL( $domain, $path )
{
    return 'http://' . $domain . '/' . $path;
}

/**
 * Activate metafields used to store relationships between elements
 * @since 0.3
 * @uses QuBicIdioma_obtenir_blocs_actius
 */
function QuBicIdioma_activar_relacions()
{
    add_meta_box(
            "QuBicIdioma_relation", __( 'Translations', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_mb_relacio', 'post', 'normal', 'default' );
}

/**
 * Writes content for relation meta box
 * @since 0.3
 * @param post_id post identifier
 * @uses QuBicIdioma_obtenir_blocs_actius
 * @uses QuBicIdioma_campmb_nom
 */
function QuBicIdioma_mb_relacio( $post )
{
    global $blog_id;
    $llista = QuBicIdioma_obtenir_blocs_actius( $criteri = 'blog_id' );
    $output = '<p>' . __( 'Sets the relationship between the content and its translations', QBC_IDIOMA_TEXT_DOMAIN ) . '</p>';
    $output.='<table class="widefat"><tbody>';
    foreach ( $llista as $bloc ):
        $current = QuBicIdioma_mb_recuperar( $post->ID, QuBicIdioma_mb_camp_nom( $bloc['blog_id'] ) );
        $output.= '<tr><td>';
        $output.='<label for="' . QuBicIdioma_mb_camp_nom( $bloc['blog_id'] ) . '">' . $bloc['language'] . ':</label>';
        $output.='</td><td><select name="' . QuBicIdioma_mb_camp_nom( $bloc['blog_id'] ) . '">';
        $output.=QuBicIdioma_mb_select( $bloc['blog_id'], $current );
        $output.= '</select>';
        $output.= '</td></tr>';
    endforeach;
    $output.='</tbody></table>';
    echo $output;
}

/**
 * Generate field name for metabox field
 * @param integer $blog_id
 * @return string field name for data entry
 */
function QuBicIdioma_mb_camp_nom( $blog_id )
{
    return QBC_IDIOMA_PREFIX . $blog_id;
}

/**
 * Generates a select with all the titles of type especified
 * @since 0.3
 * @param integer $blog_id 
 * @param string $current current value
 * @param string $type post type considered
 * @return string
 */
function QuBicIdioma_mb_select( $blogid, $current, $type='post' )
{
    global $blog_id;
    if ( $blog_id == $blogid ):
        return '<option value="" selected="selected">' . __( 'N/A', QBC_IDIOMA_TEXT_DOMAIN ) . '</option>';
    endif;
    switch_to_blog( $blogid );
    $llista = get_posts(
            array(
                'numberposts' => -1,
                'post_type' => $type,
                'order_by' => 'post_title',
                'order' => 'ASC'
            )
    );
    $output = '<option value="">' . __( 'Select a content', QBC_IDIOMA_TEXT_DOMAIN ) . '</option>';
    foreach ( $llista as $post ):
        $output.='<option value="';
        $output.=$post->ID;
        $output.='"';
        $output.=selected( $post->ID, $current, false );
        $output.='>';
        $output.=$post->post_title;
        $output.='</option>';
    endforeach;
    restore_current_blog();
    return $output;
}

/**
 * Retrieves the meta data in $camp
 * @param integer $post_id
 * @param string $camp
 * @return string
 * @since 0.3
 * @uses get_post_meta
 */
function QuBicIdioma_mb_recuperar( $post_id, $camp )
{
    return get_post_meta( $post_id, $camp, true );
}

/**
 * Saves relationship information
 * @param integer $post_id 
 * @since 0.3
 */
function QuBicIdioma_relacions_save_meta( $post_id )
{
    $blocs = QuBicIdioma_obtenir_blocs_actius( $criteri = 'blog_id' );
    foreach ( $blocs as $bloc ):
        $camp = QuBicIdioma_mb_camp_nom( $bloc['blog_id'] );
        update_post_meta( $post_id, $camp, $_POST[$camp] );
    endforeach;
}

/**
 * Print the links in the content
 * @param string $content
 * @return string 
 */
function QuBicIdioma_print_links( $content )
{
    $links = QuBicIdioma_crearContingutLinks();
    $content = $links . $content;
    return $content;
}

/**
 * Prepares links related to the content in other languages in a div
 * @param string class class for the div
 * @return string
 */
function QuBicIdioma_crearContingutLinks( $class='QuBicIdioma-top' )
{
    global $post;
    $traduccions = QuBicIdioma_obtenir_traduccions( $post->ID );
    $links = '';
    foreach ( $traduccions as $traduccio )
    {
        $links.='<li>';
        $links.='<a href="';
        $links.=$traduccio['post_url'];
        $links.='" title="'; //TODO afegir-hi "Traducció al XXX de YYY
        $links.=$traduccio['post_title'];
        $links.='">';
        $links.=$traduccio['post_language'];
        $links.='</a>';
        $links.='</li>';
    }
    $content = '<ul class="' . $class . '">' . $links . '</ul>';
    return $content;
}

/**
 * Retrieves an array with all translations of post identified by post_id
 * @param integer $post_id
 * @return array 
 */
function QuBicIdioma_obtenir_traduccions( $post_id )
{
    $llista = QuBicIdioma_obtenir_altres_blocs_actius();
    $traduccions = array( );
    foreach ( $llista as $bloc ):
        $traduccio_id = QuBicIdioma_mb_recuperar( $post_id, QuBicIdioma_mb_camp_nom( $bloc['blog_id'] ) );
        if ( $traduccio_id != '' ):
            $nom = QuBicIdioma_obtenir_nom_idioma( $bloc['blog_id'] );
            switch_to_blog( $bloc['blog_id'] );
            $post = get_post( $traduccio_id );
            $titol = $post->post_title;
            $url = get_permalink( $traduccio_id );
            if ( 'publish' == $post->post_status ):
                $traduccions[] = array(
                    'post_id' => $traduccio_id,
                    'post_language' => $nom,
                    'post_title' => $titol,
                    'post_url' => $url,
                    'blog_id' => $bloc['blog_id'],
                    'post_id_original' => $post_id,
                    'camp_a_llegir' => QuBicIdioma_mb_camp_nom( $bloc['blog_id'] ),
                    'post' => $post,
                );
            endif;
            restore_current_blog();
        endif;
    endforeach;
    return $traduccions;
}

/**
 * Gets language name used in the blog
 * @param integer $blog_id 
 * @return string
 */
function QuBicIdioma_obtenir_nom_idioma( $blog_id )
{
    $opcions = QuBicIdioma_obtenir_opcions_bloc( $blog_id );
    return $opcions['literal'];
}

/**
 * Retrieves all active blocs except the current one
 * @return array
 */
function QuBicIdioma_obtenir_altres_blocs_actius()
{
    global $blog_id;
    $tots = QuBicIdioma_obtenir_blocs_actius();
    $blocs = array( );
    foreach ( $tots as $bloc ):
        if ( $bloc['blog_id'] != $blog_id ):
            $blocs[] = $bloc;
        endif;
    endforeach;
    return $blocs;
}

/**
 * Torna els post types susceptibles de ser traduïts
 * @return array
 */
function QuBicIdioma_obtenir_posts_types_traduibles()
{
    $types = get_post_types( array( 'public' => 1 ), 'objects' );
    $result = $types;
    return $result;
}
/**
 * Generates the option name based on post_type
 * @since 0.4
 * @param string $post_type
 * @return string 
 */
function QuBicIdioma_opcio_tipus_nom( $post_type )
{
    return 'post_type_' . $post_type;
}
/**
 * Retrieves QuBicIdioma option
 * @since 0.4
 * @param type $nom 
 */
function QuBicIdioma_obtenir_posttype_a_traduir($post_type) {
    $opcions=get_option(QBC_IDIOMA_OPTIONS);
    $nom_opcio=QuBicIdioma_opcio_tipus_nom($post_type);
    $result= ('on'==$opcions[$nom_opcio]);
    return $result;
}
?>
