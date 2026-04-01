<?php
/**
 * Define a internacionalização para o plugin Portal do Cliente V2.
 */

class PDC_V2_i18n {

	/**
	 * Carrega o domínio de texto para as traduções do plugin.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'portal-do-cliente-v2',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}
