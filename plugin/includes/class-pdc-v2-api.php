<?php
/**
 * Gerencia os endpoints customizados da REST API para integração com n8n e IA.
 */

class PDC_V2_API {

	/**
	 * Namespace para os endpoints da API.
	 */
	protected $namespace = 'pdc/v2';

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

	/**
	 * Registra os endpoints customizados.
	 */
	public function register_routes() {
        $this->register_route( 'generate-summary/(?P<id>\d+)', 'POST', 'generate_summary_callback', 'generate_summary' );
        $this->register_route( 'generate-strategy/(?P<id>\d+)', 'POST', 'generate_strategy_callback', 'generate_strategy' );
        $this->register_route( 'approve-strategy/(?P<id>\d+)', 'POST', 'approve_strategy_callback', 'approve_strategy' );
	}

    /**
     * Registra uma rota da API REST.
     *
     * @param string $endpoint A parte final do endpoint da URL.
     * @param string $method O método HTTP (GET, POST, etc.).
     * @param callable $callback A função de callback para a rota.
     * @param string $permission_capability A capacidade necessária para acessar a rota.
     */
    private function register_route( $endpoint, $method, $callback, $permission_capability ) {
        register_rest_route( $this->namespace, '/' . $endpoint, array(
            'methods'             => $method,
            'callback'            => array( $this, $callback ),
            'permission_callback' => function () use ( $permission_capability ) {
                return $this->check_api_permission( $permission_capability );
            },
        ) );
    }

	/**
	 * Verifica as permissões para acessar os endpoints da API.
	 */
	public function check_api_permission( $capability ) {
		return current_user_can( $capability );
	}

	/**
	 * Callback para o endpoint de Gerar Resumo.
	 * Este endpoint será chamado pelo n8n.
	 */
	public function generate_summary_callback( $request ) {
		$client_id = $request['id'];
		
		// Lógica para enviar Webhook para o n8n ou processar internamente
		$webhook_url = get_option( 'pdc_n8n_summary_webhook' );
        if ( empty( $webhook_url ) ) {
            return new WP_Error( 'no_webhook', 'O webhook para geração de resumo não está configurado.', array( 'status' => 500 ) );
        }

        $response = wp_remote_post( $webhook_url, array(
            'body' => json_encode( array( 'client_id' => $client_id ) ),
            'headers' => array( 'Content-Type' => 'application/json' ),
        ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'webhook_failed', 'Falha ao enviar a solicitação para o n8n.', array( 'status' => 500 ) );
        }

		return new WP_REST_Response( array(
			'success' => true,
			'message' => 'Solicitação de geração de resumo enviada com sucesso.',
			'client_id' => $client_id
		), 200 );
	}

	/**
	 * Callback para o endpoint de Gerar Estratégia.
	 */
	public function generate_strategy_callback( $request ) {
		$client_id = $request['id'];
		
        // Lógica para enviar Webhook para o n8n ou processar internamente
		$webhook_url = get_option( 'pdc_n8n_strategy_webhook' );
        if ( empty( $webhook_url ) ) {
            return new WP_Error( 'no_webhook', 'O webhook para geração de estratégia não está configurado.', array( 'status' => 500 ) );
        }

        $response = wp_remote_post( $webhook_url, array(
            'body' => json_encode( array( 'client_id' => $client_id ) ),
            'headers' => array( 'Content-Type' => 'application/json' ),
        ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'webhook_failed', 'Falha ao enviar a solicitação para o n8n.', array( 'status' => 500 ) );
        }

		return new WP_REST_Response( array(
			'success' => true,
			'message' => 'Solicitação de geração estratégica enviada com sucesso.',
			'client_id' => $client_id
		), 200 );
	}

	/**
	 * Callback para o endpoint de Aprovar Estratégia.
	 */
	public function approve_strategy_callback( $request ) {
		$client_id = $request['id'];
		
		update_post_meta( $client_id, '_pdc_estrategia_aprovada', '1' );

		return new WP_REST_Response( array(
			'success' => true,
			'message' => 'Estratégia aprovada com sucesso.',
			'client_id' => $client_id
		), 200 );
	}
}
