<?php

/**
 * @package QuBic_Idioma
 */
/*
  Plugin Name: QuBic_Idioma
  Plugin URI: http://evasans.net/
  Description: QuBic_Idioma allows to have different instances of a blog using different languages on a network installation.
  Version: 0.1
  Author: Marc Queralt
  Author URI: http://evasans.net
  License: GPLv2 or later
 */

/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

define( 'QBC_IDIOMA_VERSION', '0.1' );
define( 'QBC_IDIOMA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'QBC_IDIOMA_TEXT_DOMAIN', 'QuBic_Idioma' );

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) )
{
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}

load_plugin_textdomain( QBC_IDIOMA_TEXT_DOMAIN, false, QBC_IDIOMA_PLUGIN_URL . '/languages' );
add_action( 'init', 'QuBicIdioma_Register' );
if ( is_admin() ):
    add_action( 'admin_menu', 'QuBicIdioma_admin' );
endif;

/**
 * Plugin registration
 * @since 0.1
 */
function QuBicIdioma_Register()
{
    
}

/**
 * Setup up the settings menu
 * @since 0.1
 */
function QuBicIdioma_admin()
{
    register_setting(
            'QuBicIdioma_options', 'QuBicIdioma_options', 'QuBicIdioma_admin_validate_options'
    );
    add_settings_section(
            'QuBicIdioma_main', __( 'Idioma', QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_section_main_text', 'QuBicIdioma'
    );
    add_settings_field(
            'QuBicIdioma_literal', __( "Literal d'idioma", QBC_IDIOMA_TEXT_DOMAIN ), 'QuBicIdioma_admin_literal_input', 'QuBicIdioma', 'QuBicIdioma_main'
    );
    add_options_page( __( 'Multi-idioma', QBC_IDIOMA_TEXT_DOMAIN ), __( 'Multi-idioma', QBC_IDIOMA_TEXT_DOMAIN ), 'manage_options', 'QuBicIdioma', 'QuBicIdioma_admin_optionspage' );
}

/**
 * Admin page creation
 * since: @0.1
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
 * 
 */
function QuBicIdioma_admin_literal_input()
{
    $options = get_option('QuBicIdioma_options');
    $literal = $options['literal'];
    echo '<input id="literal" name="QuBicIdioma_options[literal]" type="text" size="50" value="' . $literal . '"/>';    
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
