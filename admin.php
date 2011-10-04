<?php

/**
 * Setup up the settings menu
 * @since 0.1
 * @update 0.2 add field ordre, add configuracio section
 * @uses register_setting
 * @uses add_settings_section
 * @uses add_settings_field
 * @uses add_options_page
 */
function QuBicIdioma_admin()
{
    register_setting(
            'QuBicIdioma_options', 'QuBicIdioma_options', 'QuBicIdioma_admin_validate_options'
    );
    add_settings_section(
            'QuBicIdioma_main', __( 'Language', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_section_main_text', 'QuBicIdioma'
    );
    add_settings_section(
            'QuBicIdioma_types', __( 'Post Types', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_section_types_text', 'QuBicIdioma'
    );
    QuBicIdioma_admin_camps_tipus();
    add_settings_section(
            'QuBicIdioma_configuracio', __( 'Current Settings', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_section_config_text', 'QuBicIdioma'
    );
    add_settings_field(
            'QuBicIdioma_literal', __( 'Language text', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_literal_input', 'QuBicIdioma', 'QuBicIdioma_main'
    );
    add_settings_field(
            'QuBicIdioma_ordre', __( 'Order', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_ordre_input', 'QuBicIdioma', 'QuBicIdioma_main'
    );
    add_options_page( __( 'MultiLanguage', QBC_IDIOMA_TEXT_DOMAIN ), __( 'MultiLanguage', QBC_IDIOMA_TEXT_DOMAIN ), 'manage_options', 'QuBicIdioma', 'QuBicIdioma_admin_optionspage' );
}

/**
 * Admin page creation
 * since: @0.1
 * @uses screen_icon
 * @uses settings_field
 * @uses do_settings_sections
 */
function QuBicIdioma_admin_optionspage()
{
    echo '<div class="wrap">';
    screen_icon();
    echo '<h2>' . __( 'MultiLanguage', QBC_IDIOMA_TEXT_DOMAIN ) . '</h2>';
    echo '<form action="options.php" method="post">';
    settings_fields( 'QuBicIdioma_options' );
    do_settings_sections( 'QuBicIdioma' );
    echo '<input name="Submit" type="submit" value="' . __( 'Save Changes', QBC_IDIOMA_TEXT_DOMAIN ) . '"/>';
    echo '</form>';
    echo '</div>';
}

/**
 * Writes the main section text
 * @since 0.1
 */
function QuBicIdioma_admin_section_main_text()
{
    echo '<p>' . __( "Language settings for current blog.", QBC_IDIOMA_TEXT_DOMAIN ) . '</p>';
    //echo '<pre>' . print_r( QuBicIdioma_obtenir_posts_types_traduibles(), true ) . '</pre>';
}

/**
 * Writes the configuration text
 * @since 0.2
 * @updated 0.5 simplify output
 */
function QuBicIdioma_admin_section_config_text()
{
    echo "<p>";
    if ( is_multisite() ):
        _e( 'Multisite set.', QBC_IDIOMA_TEXT_DOMAIN );
    else:
        _e( 'Multisite not set.', QBC_IDIOMA_TEXT_DOMAIN );
        echo '<br/>';
        _e( 'This plugin needs a network set in order to work.', QBC_IDIOMA_TEXT_DOMAIN );
    endif;
    echo "</p>";
//    echo "<p><pre>";
//    print_r( QuBicIdioma_obtenir_blocs() );
//    echo "</pre></p>";
}

/**
 * Prints literal form field
 */
function QuBicIdioma_admin_literal_input()
{
    $options = get_option( QBC_IDIOMA_OPTIONS );
    $literal = $options['literal'];
    echo '<input id="literal" name="QuBicIdioma_options[literal]" type="text" size="50" value="' . $literal . '"/>';
}

/**
 * Prints ordre form field
 * @since 0.2
 */
function QuBicIdioma_admin_ordre_input()
{
    $options = get_option( QBC_IDIOMA_OPTIONS );
    $ordre = $options['ordre'];
    echo '<input id="ordre" name="QuBicIdioma_options[ordre]" type="text" size="5" value="' . $ordre . '"/>';
}

/**
 * Plugin settings validation
 * @param array $input options introduced by the user
 * @since 0.1
 */
function QuBicIdioma_admin_validate_options( $input )
{
    $valid = array( );
    $valid = $input;
    return $valid;
}

/**
 * @since 0.4
 */
function QuBicIdioma_admin_section_types_text()
{
    echo '<p>' . __( "Post types that can be translated.", QBC_IDIOMA_TEXT_DOMAIN ) . '</p>';
}

/**
 * @since 0.4
 * @updated 0.5 Adaptation to custom_types
 */
function QuBicIdioma_admin_camps_tipus()
{
    $array = QuBicIdioma_obtenir_posts_types_traduibles();
    foreach ( $array as $key => $value ):
        $etiqueta = $value->labels->name;
        $nom = QuBicIdioma_opcio_tipus_nom( $key );
        $valor = QuBicIdioma_obtenir_posttype_a_traduir( $key );
        $args = array(
            'nom' => $nom,
            'valor' => $valor,
        );
        add_settings_field(
                'QuBicIdioma_type_' . $key, $etiqueta, 'QuBicIdioma_admin_type_input', 'QuBicIdioma', 'QuBicIdioma_types', $args
        );
    endforeach;
    {
        
    }
}

/**
 * Outputs the HTML for the checkbox
 * @since 0.4
 * @updated 0.5 Change on parameter structure
 * @uses QuBicIdioma_admin_check
 */
function QuBicIdioma_admin_type_input( $args )
{
    if ( isset( $args['nom'] ) ):
        if(!isset($args['valor'])):
            $args['valor']=0;
        endif;
        QuBicIdioma_admin_ckeckbox( $args['nom'], $args['valor'] );
    endif;
}

/**
 * Prints a checkbox for option typepost field
 * since 0.4
 */
function QuBicIdioma_admin_ckeckbox( $field_name, $field_value )
{
    echo '<input type="checkbox" name="';
    echo QBC_IDIOMA_OPTIONS . '[' . $field_name . ']';
    echo '" ';
    $checked=checked( $field_value, 1, false);
    echo $checked;
    echo '>';
}

?>
