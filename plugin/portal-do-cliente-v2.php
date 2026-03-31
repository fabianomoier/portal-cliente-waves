<?php
/**
 * Plugin Name: Portal do Cliente (V2)
 * Plugin URI: https://wavesgrowth.com.br
 * Description: Sistema de gestão de clientes com foco em organização, estratégia e geração de conteúdo assistida por IA (V2).
 * Version: 2.0.0
 * Author: Fabiano Moier (Senior WP Developer)
 * Author URI: https://wavesgrowth.com.br
 * Text Domain: portal-do-cliente-v2
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PDC_V2_VERSION', '2.0.0' );
define( 'PDC_V2_PATH', plugin_dir_path( __FILE__ ) );
define( 'PDC_V2_URL', plugin_dir_url( __FILE__ ) );

/**
 * Código executado na ativação do plugin.
 */
function activate_pdc_v2() {
	require_once PDC_V2_PATH . 'includes/class-pdc-v2-activator.php';
	PDC_V2_Activator::activate();
}

/**
 * Código executado na desativação do plugin.
 */
function deactivate_pdc_v2() {
	require_once PDC_V2_PATH . 'includes/class-pdc-v2-activator.php';
	PDC_V2_Activator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pdc_v2' );
register_deactivation_hook( __FILE__, 'deactivate_pdc_v2' );

/**
 * Classe principal do plugin que orquestra tudo.
 */
require_once PDC_V2_PATH . 'includes/class-pdc-v2.php';

function run_pdc_v2() {
	$plugin = new PDC_V2();
	$plugin->run();
}

run_pdc_v2();
