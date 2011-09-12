<?php

/**
 * Gets blog and language information sort based on $criteri
 * @since 0.2
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
        $info[] = array(
            'blog_id' => $bloc['blog_id'],
            'domain' => $bloc['domain'],
            'path' => $bloc['path'],
            'actiu' => isset( $opcions['literal'] ),
            'language' => $opcions['literal'],
            'ordre' => isset( $opcions['ordre'] ) ? $opcions['ordre'] : 99,
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
    $info = array_filter($info, 'QuBicIdioma_es_bloc_actiu');
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
function QuBicIdioma_crearURL($domain,$path) {
    return 'http://'.$domain.'/'.$path;
}
?>
