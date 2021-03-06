<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService;

class Outlet extends GobizService
{
	/*
	|--------------------------------------------------------------------------
	| Outlet Management Module
	|--------------------------------------------------------------------------
	|
	| This module is handling promotion section of Go-Biz outlet management.
   	| The main reference of this module is this documentation:
   	|
   	| https://docs.gobiz.co.id/docs/index.html#:~:text=Yes-,Outlet%20Management,-Open%20and%20Close
	|
	*/

	/**
	 * Outlet base uri
	 * 
	 * @var string
	 */
	private $baseUri = '/outlets/';

	/**
	 * Get all gobiz enterprise's outlets
	 * 
	 * @param  array  $parameters
	 * @return array
	 */
	public function all(array $parameters = [])
	{
		// Prepare the URL endpoint for request
		$uri = $this->baseUri;
		$url = $this->apiUrl($uri);

		// Get response from the gobiz promo api request
		$data = $this->makeRequest('GET', $url, $parameters);

		return $data['outlets'];
	}

	/**
	 * Open outlet.
	 * 
	 * @param  string  $outletId
	 * @return array
	 */
	public function open(string $outletId)
	{
		// Prepare the URL endpoint for request
		$apiUrl = concat_paths([
			$this->baseUri, 
			$outletId, 
			'open'
		]);

		// Get response from the gobiz promo api request
		$data = $this->makeRequest('PATCH', $apiUrl);

		return $data;
	}

	/**
	 * Close outlet.
	 * 
	 * @param  string  $outletId
	 * @return array
	 */
	public function close(string $outletId)
	{
		// Prepare the URL endpoint for request
		$apiUrl = concat_paths([
			$this->baseUri, 
			$outletId, 
			'close'
		]);

		// Get response from the gobiz promo api request
		$data = $this->makeRequest('PATCH', $apiUrl);

		return $data;
	}
}