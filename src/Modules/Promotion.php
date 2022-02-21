<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService;
use BensonDevs\Gobiz\Contracts\BelongsToOutletContract;

class Promotion extends GobizService implements BelongsToOutletContract
{
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
	 * Get all outlet promotion list
	 * 
	 * @param  int  $limit
	 * @param  int  $page
	 * @return array
	 */
	public function all(int $limit = 0, int $page = 0)
	{
		//
	}

	/**
	 * Create outlet promotion
	 * 
	 * @param  array  $promoData
	 * @return array
	 */
	public function create(array $promoData)
	{
		//
	}

	/**
	 * Retrieve the outlet promotion
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function find(string $promoId)
	{
		//
	}

	/**
	 * Deactivate the outlet promotion
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function deactivate(string $promoId)
	{
		//
	}

	/**
	 * Delete the outlet promotion
	 * 
	 * @param  string  $promoId
	 * @return array
	 */
	public function delete(string $promoId)
	{
		//
	}
}