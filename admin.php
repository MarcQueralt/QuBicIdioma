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
            'QuBicIdioma_main', __( 'Idioma', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_section_main_text', 'QuBicIdioma'
    );
    add_settings_section(
            'QuBicIdioma_configuracio', __( 'Confirguració actual', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_section_config_text', 'QuBicIdioma'
    );
    add_settings_field(
            'QuBicIdioma_literal', __( "Literal d'idioma", QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_literal_input', 'QuBicIdioma', 'QuBicIdioma_main'
    );
    add_settings_field(
            'QuBicIdioma_ordre', __( "Ordre", QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_ordre_input', 'QuBicIdioma', 'QuBicIdioma_main'
    );
    add_options_page( __( 'Multi-idioma', QBC_IDIOMA_TEXT_DOMAIN ), __( 'Multi-idioma', QBC_IDIOMA_TEXT_DOMAIN ), 'manage_options', 'QuBicIdioma', 'QuBicIdioma_admin_optionspage' );
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
    echo '<h2>' . __( 'Multi-idioma', QBC_IDIOMA_TEXT_DOMAIN ) . '</h2>';
    echo '<form action="options.php" method="post">';
    settings_fields( 'QuBicIdioma_options' );
    do_settings_sections( 'QuBicIdioma' );
    echo '<input name="Submit" type="submit" value="' . __( 'Desar canvis', QBC_IDIOMA_TEXT_DOMAIN ) . '"/>';
    echo '</form>';
    echo '</div>';
}

/**
 * Writes the main section text
 * @since 0.1
 */
function QuBicIdioma_admin_section_main_text()
{
    echo '<p>' . __( "S'estableixen els paràmetres de visualització d'idioma vinculats al bloc actual", QBC_IDIOMA_TEXT_DOMAIN ) . '</p>';
}

/**
 * Writes the configuration text
 * @since 0.2
 */
function QuBicIdioma_admin_section_config_text()
{
    echo "<p>";
    if ( is_multisite() ):
        _e( 'Configuració multisite activa.', QBC_IDIOMA_TEXT_DOMAIN );
    else:
        _e( 'Configuració multisite no activa.', QBC_IDIOMA_TEXT_DOMAIN );
        echo '<br/>';
        _e( 'Activa-la per a que l\'extensió funcioni correctament.', QBC_IDIOMA_TEXT_DOMAIN );
    endif;
    echo "</p>";
    echo "<p><pre>";
    print_r( QuBicIdioma_obtenir_blocs() );
    echo "</pre></p>";
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

?>