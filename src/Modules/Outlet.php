<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService;

class Outlet extends GobizService
{
	/**
	 * Outlet base uri
	 * 
	 * @var string
	 */
	private $baseUri = '/outlets/';

	/**
	 * Outlet results per page
	 * 
	 * @var int
	 */
	private $perPage;

	/**
	 * Outlet results current page
	 * 
	 * @var int
	 */
	private $page;

	/**
	 * Set result per page of the outlets collection
	 * 
	 * @param  int  $perPage
	 * @return $this
	 */
	public function setPerPage(int $perPage)
	{
		$this->perPage = $perPage;

		return $this;
	}

	/**
	 * Set current page of the outlets results
	 * 
	 * @param  int  $page
	 * @return $this
	 */
	public function setPage(int $page)
	{
		$this->page = $page;

		return $this;
	}

	/**
	 * Get all gobiz enterprise's outlets
	 * 
	 * @return array
	 */
	public function all()
	{
		// Prepare the URL endpoint for request
		$uri = $this->baseUri;
		$url = $this->apiUrl($uri);

		// Prepare request parameters
		$params = [];
		if ($this->perPage) {
			$params['per'] = $this->perPage;
		}
		if ($this->page) {
			$params['page'] = $this->page;
		}

		// Make request to API
		$data = $this->makeRequest('GET', $url, $params);

		return $data['outlets'];
	}
}