<?php
/**
 * Gerencia as funcionalidades administrativas do plugin Portal do Cliente V2.
 */

class PDC_V2_Admin {

		/**
		 * Adiciona uma página de menu customizada.
		 *
		 * @param string $page_title O título da página que aparece na tag <title>.
		 * @param string $menu_title O texto do menu.
		 * @param string $capability A capacidade necessária para acessar a página.
		 * @param string $menu_slug O slug único para a página de menu.
		 * @param callable $callback A função que renderiza o conteúdo da página.
		 * @param string $icon_url O URL para o ícone a ser usado para o item de menu.
		 * @param int $position A posição do item de menu.
		 */
		private function add_custom_admin_page( $page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url = '', $position = null ) {
			add_menu_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback,
				$icon_url,
				$position
			);
		}

		/**
		 * Adiciona uma subpágina de menu customizada.
		 *
		 * @param string $parent_slug O slug do menu pai.
		 * @param string $page_title O título da página que aparece na tag <title>.
		 * @param string $menu_title O texto do menu.
		 * @param string $capability A capacidade necessária para acessar a página.
		 * @param string $menu_slug O slug único para a página de menu.
		 * @param callable $callback A função que renderiza o conteúdo da página.
		 */
		private function add_custom_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback ) {
			add_submenu_page(
				$parent_slug,
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback
			);
		}


	/**
	 * Nome único do plugin.
	 */
	private $plugin_name;

	/**
	 * Versão atual do plugin.
	 */
	private $version;

	/**
	 * Inicializa a classe e define as propriedades do plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Enfileira os estilos CSS para a área administrativa do WordPress.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, PDC_V2_URL . 'admin/assets/css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Enfileira os scripts JavaScript para a área administrativa do WordPress.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, PDC_V2_URL . 'admin/assets/js/admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Adiciona o menu principal e submenus do plugin na área administrativa.
	 */
public function add_plugin_admin_menu() {
			$this->add_custom_admin_page(
				__( 'Portal do Cliente V2', 'portal-do-cliente-v2' ),
				__( 'Portal Cliente V2', 'portal-do-cliente-v2' ),
				'manage_options',
				$this->plugin_name,
				array( $this, 'display_plugin_admin_page' ),
				'dashicons-groups',
				25
			);

			$this->add_custom_submenu_page(
				$this->plugin_name,
				__( 'Configurações', 'portal-do-cliente-v2' ),
				__( 'Configurações', 'portal-do-cliente-v2' ),
				'manage_options',
				$this->plugin_name . '-settings',
				array( $this, 'display_plugin_settings_page' )
			);
		}

	/**
	 * Renderiza a página principal do dashboard administrativo do plugin.
	 */
	public function display_plugin_admin_page() {
		require_once PDC_V2_PATH . 'admin/views/dashboard.php';
	}

	/**
	 * Renderiza a página de configurações do plugin.
	 */
	public function display_plugin_settings_page() {
		require_once PDC_V2_PATH . 'admin/views/settings.php';
	}

}
