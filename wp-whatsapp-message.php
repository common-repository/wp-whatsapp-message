<?php
/*
Plugin Name: Chat Message Button
Plugin URI: https://danielesparza.studio/chat-message-button/
Description: Chat Message Button (Antes WhatsApp Message) es un plugin de WordPress que sirve para agregar un botón en una página a través de un shortcode y permite a los usuarios enviar un mensaje de texto directamente a número de WhatsApp.
Version: 1.0
Author: Daniel Esparza
Author URI: https://danielesparza.studio/
License: GPL v3

Chat Message Button
©2020 Daniel Esparza, inspirado por #openliveit #dannydshore | Consultoría en servicios y soluciones de entorno web -  https://danielesparza.studio/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(function_exists('admin_menu_desparza')) { 
    //menu exist
} else {
	add_action('admin_menu', 'admin_menu_desparza');
	function admin_menu_desparza(){
		add_menu_page('DE Plugins', 'DE Plugins', 'manage_options', 'desparza-menu', 'wp_desparza_function', 'dashicons-editor-code', 90 );
		add_submenu_page('desparza-menu', 'Sobre Daniel Esparza', 'Sobre Daniel Esparza', 'manage_options', 'desparza-menu' );
	
    function wp_desparza_function(){  	
	?>
		<div class="wrap">
            <h2>Daniel Esparza</h2>
            <p>Consultoría en servicios y soluciones de entorno web.<br>¿Qué tipo de servicio o solución necesita tu negocio?</p>
            <h4>Contact info:</h4>
            <p>
                Sitio web: <a href="https://danielesparza.studio/" target="_blank">https://danielesparza.studio/</a><br>
                Contacto: <a href="mailto:hi@danielesparza.studio" target="_blank">hi@danielesparza.studio</a><br>
                Messenger: <a href="https://www.messenger.com/t/danielesparza.studio" target="_blank">enviar mensaje</a><br>
                Información acerca del plugin: <a href="https://danielesparza.studio/wp-whatsapp-message/" target="_blank">sitio web del plugin</a><br>
                Daniel Esparza | Consultoría en servicios y soluciones de entorno web.<br>
                ©2020 Daniel Esparza, inspirado por #openliveit #dannydshore
            </p>
		</div>
	<?php }
        
    }	
    
    add_action( 'admin_enqueue_scripts', 'wpscm_register_adminstyle' );
    function wpscm_register_adminstyle() {
        wp_register_style( 'wpscm_register_adminstyle_css', plugin_dir_url( __FILE__ ) . 'css/cmb_style_admin.css', array(), '1.0' );
        wp_enqueue_style( 'wpscm_register_adminstyle_css' );
    }
    
}


if ( ! function_exists( 'cmb_message_add' ) ) {

add_action( 'admin_menu', 'cmb_message_add' );
function cmb_message_add() {
    add_submenu_page('desparza-menu', 'Chat Message Button', 'Chat Message Button', 'manage_options', 'cmb-message-settings', 'cmb_how_to_use' );
}

function cmb_how_to_use(){ 
    echo '
    <div class="wrap">
        <h2>Chat Message Button, ¿Como usar el shortcode?</h2>
        <ul>
            <li>[cmb-message phone="0000000000"] // Configruación por defecto (cambiar número: código de área + teléfono).</li>
            <li>[cmb-message phone="0000000000" class="nombre de la clase"] // Agrgando una clase para cambiar los estilos CSS.</li>
            <li>[cmb-message phone="0000000000" icon="url de la imagen"] // Cambiando la imagen por defecto.</li>
			<li>[cmb-message phone="0000000000" usr_text="lorem ipsum dolor"] // Cambiando el texto del mensaje.</li>
            <li>[cmb-message phone="0000000000" text="lorem ipsum dolor"] // Cambiando el texto del enlace por defecto.</li>
        </ul>
        <h4>Chat Message Button, Otras funciones:</h4>
        <ul>
            <li>[cmb-message class=" "] // Deshabilitar solo los estilos CSS.</li>
        </ul>
    </div>';
}

// Add Style
add_action('wp_enqueue_scripts', 'cmb_shortcode_style');
function cmb_shortcode_style() {
    wp_register_style( 'cmb_css', plugin_dir_url( __FILE__ ) . 'css/cmb-message.css' );
    wp_enqueue_style( 'cmb_css' );
}

// Add Shortcode
add_shortcode( 'cmb-message', 'cmb_message_shortcode' );
function cmb_message_shortcode($atts, $content = null) {
ob_start();
extract( shortcode_atts( array(
      'icon' =>  plugin_dir_url( __FILE__ ) . 'img/cmb-message.svg',
      'phone' => '0000000000',
      'usr_text' => 'Hola necesito más información sobre sus servicios',
      'text' => 'Consultas via WhatsApp',
      'class' => 'cmb-message'
      ), $atts ) );
        ?>
            <a class="<?php echo esc_attr($class) ?>" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr($phone) ?>&text=<?php echo esc_attr($usr_text) ?>" target="_blank"><img src="<?php echo $icon; ?>"><?php echo ' ' . $text; ?></a>
		<?php
 			$output_string = ob_get_contents();
    		ob_end_clean();
			return $output_string;
}
}