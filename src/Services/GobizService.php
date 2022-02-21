<?php

namespace BensonDevs\Gobiz\Services;

class GobizService
{
	/**
	 * Guzzle service class container
	 * 
	 * @var \Bensondevs\Services\GuzzleClientService
	 */
	private $guzzleClient;

	/**
	 * Gobiz token to authenticate service
	 * 
	 * @var stdClass
	 */
	protected $gobizToken;

	/**
	 * Gobiz enterprise ID
	 * 
	 * @var string
	 */
	private $enterpriseId;

	/**
	 * Service constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		// Prepare Guzzle Client instance
		$this->guzzleClient = new GuzzleClientService();

		// Authenticate the gobiz service class
		$this->authenticate();

		// Set enterprise ID if any
		$this->setEnterpriseId(config('gobiz.enterprise_id'));
	}

	/**
	 * Get gobiz token for authentication.
	 * 
	 * This will authenticate the service class and hold token
	 * array instance into the $this->gobizToken
	 * 
	 * @return array
	 */
	private function authenticate()
	{
		// Get gobiz token if no token and token is expired
		if (! $this->gobizToken) {
			// Set basic auth header
			$this->guzzleClient->setHeaders([
				'Content-Type' => 'application/x-www-form-urlencoded',
			]);

			$authUrl = concat_paths([
				config('gobiz.oauth_url'),
				'oauth2/token'
			]);
			$token = $this->guzzleClient->post($authUrl, [
				'client_id' => config('gobiz.client_id'),
				'client_secret' => config('gobiz.client_secret'),
				'grant_type' => 'authorization_code',
				'code' => config('gobiz.code'),
				'redirect_uri' => config('gobiz.redirect_uri'),
			]);

			// Set expiration time of token
			$token['expiry_time'] = now()->addSeconds($token['expires_in']);
			$this->gobizToken = $token;
		}
	
		// Check if token is expired, if yes refresh it
		if ($this->gobizToken['expiry_time'] < now()) {
			$token = $this->guzzleClient->post($authUrl, [
				'client_id' => config('gobiz.client_id'),
				'client_secret' => config('gobiz.client_secret'),
				'grant_type' => 'refresh_token',
				'refresh_token' => $this->gobizToken['access_token'],
			]);
			$this->gobizToken = $token;
		}

		// Set token to the guzzle client service instance
		$accessToken = $this->gobizToken['access_token'];
		$this->guzzleClient->setHeaders([
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $accessToken,
		]);

		return $this->gobizToken;
	}

	/**
	 * Set enterprise ID for the request
	 * 
	 * @param  string  $enterpriseId
	 * @return $this
	 */
	public function setEnterpriseId(string $enterpriseId)
	{
		$this->enterpriseId = $enterpriseId;

		return $this;
	}

	/**
	 * Get built-in Gobiz API url for the request
	 * 
	 * @param  string  $uri
	 * @return string
	 */
	private function apiUrl(string $uri)
	{
		$baseUrl = config('gobiz.base_url');
		$enterpriseId = $this->enterpriseId;

		return concat_paths([$baseUrl, $enterpriseId, $uri]);
	}

	/**
	 * Make request using guzzle client
	 * 
	 * @param  string  $method
	 * @param  ...$params
	 * @return mixed
	 */
	public function makeRequest(string $method = 'GET', ...$params)
	{
		// Prepare the function that will be executed
		$client = $this->guzzleClient;
		$method = strtolower($method);

		// Make request to Gobiz API
		$response = call_user_func([$client, $method], $params);

		if (! isset($response['status'])) {
			return throw new Exception('Unknown response received from API.');
		}

		if ($response['status'] !== 'success') {
			return throw new Exception($response['errors']);
		}

		if (! isset($response['data'])) {
			return throw new Exception('No data found from the response.');
		}

		return $response['data'];
	}
}