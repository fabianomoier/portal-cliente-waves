<?php
/**
 * Executa ações necessárias no momento da ativação e desativação do plugin.
 */

class PDC_V2_Activator {

	/**
	 * Cria as páginas essenciais para o frontend.
	 */
	public static function create_essential_pages() {
		$pages = array(
			'dashboard' => array(
				'post_title'    => __( 'Dashboard do Cliente', 'portal-do-cliente-v2' ),
				'post_content'  => '[pdc_dashboard]', // Shortcode para o dashboard
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'page_template' => 'front-page.php', // Usar o template do tema
			),
			'novo' => array(
				'post_title'    => __( 'Criar Novo Item', 'portal-do-cliente-v2' ),
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'page_template' => 'template-new-post.php',
			),
			'editar' => array(
				'post_title'    => __( 'Editar Item', 'portal-do-cliente-v2' ),
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'page_template' => 'template-edit-post.php',
			),
		);

		foreach ( $pages as $slug => $page_data ) {
			$page_id = post_exists( $page_data['post_title'] );
			
			if ( ! $page_id ) {
				$page_id = wp_insert_post( $page_data );
			}

			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $page_data['page_template'] );
			}
		}
	}

	/**
	 * Remove as páginas essenciais na desativação.
	 */
	public static function remove_essential_pages() {
		$pages_to_remove = array(
			__( 'Dashboard do Cliente', 'portal-do-cliente-v2' ),
			__( 'Criar Novo Item', 'portal-do-cliente-v2' ),
			__( 'Editar Item', 'portal-do-cliente-v2' ),
		);

		foreach ( $pages_to_remove as $page_title ) {
			$page = get_page_by_title( $page_title );
			if ( $page ) {
				wp_delete_post( $page->ID, true );
			}
		}
	}

	/**
	 * Verifica os requisitos mínimos do ambiente.
	 */	
	public static function check_environment_requirements() {
		global $wp_version;
		$min_php_version = '7.4';
		$min_wp_version  = '5.9';
		$errors = array();

		if ( version_compare( PHP_VERSION, $min_php_version, '<' ) ) {
			$errors[] = sprintf( __( 'O Portal do Cliente V2 requer PHP versão %s ou superior. Você está usando a versão %s.', 'portal-do-cliente-v2' ), $min_php_version, PHP_VERSION );
		}

		if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
			$errors[] = sprintf( __( 'O Portal do Cliente V2 requer WordPress versão %s ou superior. Você está usando a versão %s.', 'portal-do-cliente-v2' ), $min_wp_version, $wp_version );
		}

		if ( ! empty( $errors ) ) {
			deactivate_plugins( plugin_basename( PDC_V2_PATH . 'portal-do-cliente-v2.php' ) );
			wp_die( implode( '<br>', $errors ), __( 'Erro de Ativação do Plugin', 'portal-do-cliente-v2' ), array( 'back_link' => true ) );
		}
	}

	/**
	 * Realiza as tarefas de ativação, como limpeza de permalinks e criação de tabelas (se necessário).
	 */
	public static function activate() {
		require_once PDC_V2_PATH . 'includes/class-pdc-v2-roles.php';
		PDC_V2_Activator::check_environment_requirements();
		PDC_V2_Roles::add_custom_roles();
		PDC_V2_Activator::create_essential_pages();
		flush_rewrite_rules();
	}

	/**
	 * Código executado na desativação do plugin.
	 */
	public static function deactivate() {
		require_once PDC_V2_PATH . 'includes/class-pdc-v2-roles.php';
		PDC_V2_Roles::remove_custom_roles();
		PDC_V2_Activator::remove_essential_pages();
		flush_rewrite_rules();
	}

}
