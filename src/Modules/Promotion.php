<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService;
use BensonDevs\Gobiz\Contracts\BelongsToOutletContract;

class Promotion extends GobizService implements BelongsToOutletContract
{
	/*
	|--------------------------------------------------------------------------
	| SKU Promotion Module
	|--------------------------------------------------------------------------
	|
	| This module is handling promotion section of Go-Biz merchant API.
   	| The main reference of this module is this documentation:
   	|
   	| https://docs.gobiz.co.id/docs/index.html#sku-promo
	|
	*/

	/**
	 * Gofood base uri
	 * 
	 * @var string
	 */
	private $baseUri = 'outlets/{outlet_id}/gofood/v1/food-promos';

	/**
	 * Set outlet ID for the Food Promotion module
	 * 
	 * @param  string  $outletId
	 * @return $this
	 */
	public function setOutlet(string $outletId)
	{
		$explode = explode('/', $this->baseUri);
		$explode[1] = $outletId;

		$newUri = implode('/', $explode);
		$this->baseUri = $newUri;
	}

	/**
	 * Get list of outlet promotions.
	 * 
	 * @param  array  $parameters
	 * @return array
	 */
	public function list(array $parameters = [])
	{
		// Prepare API URL endpoint
		$apiUrl = $this->baseUri;
		$apiUrl = $this->apiUrl($apiUrl);

		// Get response from the gobiz promo api request
		$response = $this->makeRequest('GET', $apiUrl, $parameters);

		return $response;
	}

	/**
	 * Create outlet promotion.
	 * 
	 * This method require the array to have attributes of:
	 * - promo_type
	 * - promo_details
	 * 
	 * @param  array  $promoData
	 * @return array
	 */
	public function create(array $promoData)
	{
		// Prepare API URL endpoint
		$apiUrl = $this->baseUri;
		$apiUrl = $this->apiUrl($apiUrl);

		// Add required header
		$this->guzzleClient->addHeader('X-Idempotency-Key', random_string(32));

		// Get response from the gobiz promo api request
		$response = $this->makeRequest('POST', $apiUrl, $promoData);

		return $response['success'];
	}

	/**
	 * Retrieve the outlet promotion
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function find(string $promoId)
	{
		// Prepare API URL endpoint
		$apiUrl = concat_paths([
			$this->baseUri, 
			$promoId
		]);
		$apiUrl = $this->apiUrl($apiUrl);

		// Get response from the gobiz promo api request
		$response = $this->makeRequest('GET', $apiUrl);

		return $response;
	}

	/**
	 * Activate the outlet promotion.
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function activate(string $promoId)
	{
		// Prepare API URL endpoint
		$apiUrl = concat_paths([
			$this->baseUri, 
			$promoId, 
			'activate'
		]);
		$apiUrl = $this->apiUrl($apiUrl);

		// Get response from the gobiz promo api request
		$response = $this->makeRequest('PUT', $apiUrl);

		return $response;
	}

	/**
	 * Deactivate the outlet promotion.
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function deactivate(string $promoId)
	{
		// Prepare API URL endpoint
		$apiUrl = concat_paths([
			$this->baseUri, 
			$promoId, 
			'deactivate'
		]);
		$apiUrl = $this->apiUrl($apiUrl);

		// Get response from the gobiz promo api request
		$response = $this->makeRequest('PUT', $apiUrl);

		return $response;
	}

	/**
	 * Delete the outlet promotion
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function delete(string $promoId)
	{
		// Prepare API URL endpoint
		$apiUrl = concat_paths([
			$this->baseUri, 
			$promoId, 
			'delete'
		]);
		$apiUrl = $this->apiUrl($apiUrl);

		// Get response from the gobiz promo api request
		$response = $this->makeRequest('DELETE', $apiUrl);

		return $response;
	}
}