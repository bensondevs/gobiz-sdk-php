<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService;
use BensonDevs\Gobiz\Contracts\BelongsToOutletContract;

class GoFood extends GobizService implements BelongsToOutletContract
{
	/*
	|--------------------------------------------------------------------------
	| GoFood Module
	|--------------------------------------------------------------------------
	|
	| This module is handling promotion section of Go-Food API.
   	| The main reference of this module is this documentation:
   	|
   	| https://docs.gobiz.co.id/api-reference/index.html#:~:text=or%20go_auth_client_credentials(transaction%3Aread).-,Enterprise%20%2D%20Outlet%20Notification%20Subscriptions,-This%20section%20specifies
	|
	*/

	/**
	 * Gofood base uri
	 * 
	 * @var string
	 */
	private $baseUri = 'outlets/{outlet_id}/gofood/v1/';

	/**
	 * Set outlet ID for the GoFood module
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

		return $this;
	}

	/**
	 * Update gofood outlet catalog
	 * 
	 * @param  string  $requestId
	 * @param  array   $menus
	 * @param  array   $varCategories
	 * @return array
	 */
	public function updateCatalog(
		string $requestId, 
		array $menus, 
		array $varCategories
	) {
		// Prepare API URL endpoint
		$apiUrl = $this->apiUrl(concat_paths([
			$this->baseUri, '/catalog'
		]));

		// Get response from the gofood api request
		$data = $this->makeRequest('PUT', $apiUrl, [
			'request_id' => $requestId,
			'menus' => $menus,
			'variant_categories' => $varCategories,
		]);

		return $data;
	}

	/**
	 * Mark gofood order delivery as ready
	 * 
	 * @param  string  $orderType
	 * @param  string  $orderId
	 * @param  string  $countryCode
	 * @return array
	 */
	public function markFoodReady(
		string $orderType, 
		string $orderId,
		string $countryCode = null
	) {
		// Check if type is correct
		$orderType = strtolower($orderType);
		if ($orderType !== 'delivery' || $orderType !== 'pickup') {
			$excpMsg = 'Unsupported order type. This must be either `delivery` or `pickup`';
			return throw new Exception($excpMsg);
		}

		// Prepare API URL endpoint
		$apiUrl = $this->apiUrl(concat_paths([
			$this->baseUri, 
			'/orders/' . $orderType . '/' . $orderId . '/food-prepared',
		]));

		// Get response from the gofood api request
		$countryCode = $countryCode ?: 'ID';
		$data = $this->makeRequest('PUT', $apiUrl, [
			'country_code' => strtoupper($countryCode)
		]);

		return $data;
	}
}