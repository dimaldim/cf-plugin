<?php

namespace DXCF;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class DX_Cloudflare {
	private $api_email;
	private $api_key;
	private $api_url;


	/**
	 * DX_Cloudflare constructor.
	 *
	 * @param $api_email
	 * @param $api_key
	 */
	public function __construct( $api_email, $api_key ) {
		$this->api_email = $api_email;
		$this->api_key   = $api_key;
		$this->api_url   = 'https://api.cloudflare.com/client/v4/';
	}

	/**
	 * Get user details. 
	 *
	 * @return false
	 * @throws GuzzleException
	 */
	public function get_user_details() {
		try {
			$user_data = json_decode( $this->get_client()->get( 'user' )->getBody() );

			return $user_data->result;
		} catch ( RequestException $exception ) {
			return false;
		}
	}

	/**
	 * Get user ID. 
	 *
	 * @return mixed
	 * @throws GuzzleException
	 */
	public function get_user_id() {
		$user_data = $this->get_user_details();

		return $user_data->id;
	}

	/**
	 * Invoke the Guzzle client
	 * with necessary headers for
	 * Cloudflare API
	 *
	 * @return Client
	 */
	private function get_client() {
		return new Client(
			array(
				'base_uri' => $this->api_url,
				'headers'  => array(
					'X-Auth-Email' => $this->api_email,
					'X-Auth-Key'   => $this->api_key,
				),
			)
		);
	}
}
