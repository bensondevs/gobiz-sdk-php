<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService as Service;
use BensonDevs\Gobiz\Contracts\BelongsToOutletContract as OutletContract;

class OutletNotificationSubscription extends Service implements Contract
{
	/*
	|--------------------------------------------------------------------------
	| Outlet Notification Subscription Module
	|--------------------------------------------------------------------------
	|
	| This module is handling promotion section of Outlet Notification Subscription.
   	| The main reference of this module is this documentation:
   	|
   	| https://docs.gobiz.co.id/docs/index.html#pre-requisite11
    |
    */

    /**
	 * Outlet Notification Subscription base uri
	 * 
	 * @var string
	 */
	private $baseUri = 'outlets/{outlet_id}/notification-subscriptions/';

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
     * Get list of outlet notification subscriptions
     * 
     * @return array
     */
    public function list()
    {
    	// Prepare API URL endpoint
    	$apiUrl = $this->apiUrl($this->baseUri);

    	// Get response from the gobiz notif subs api request
    	$response = $this->makeRequest('GET', $apiUrl);

    	return $response['subscriptions'];
    }

    /**
     * Create outlet notification subscription
     * 
     * @param  array  $eventData
     * @return array
     */
    public function create(array $eventData)
    {
    	// Prepare API URL endpoint
    	$apiUrl = $this->apiUrl($this->baseUri);

    	// Get response from the gobiz notif subs api request
    	$response = $this->makeRequest('POST', $apiUrl, $eventData);

    	return $response['subscription'];
    }

    /**
     * Update outlet notification subscription
     * 
     * @param  string $id
     * @param  array  $eventData
     * @return array
     */
    public function update(string $id, array $eventData)
    {
    	// Prepare API URL endpoint
    	$apiUrl = concat_paths([$this->baseUri, $id]);
    	$apiUrl = $this->apiUrl($apiUrl);

    	// Get response from the gobiz notif subs api request
    	$response = $this->makeRequest('PUT', $apiUrl, $eventData);

    	return $response['subscription'];
    }

    /**
     * Delete outlet notification subscription
     * 
     * @param  string $id
     * @return bool
     */
    public function delete(string $id)
    {
    	// Prepare API URL endpoint
    	$apiUrl = concat_paths([$this->baseUri, $id]);
    	$apiUrl = $this->apiUrl($apiUrl);

    	// Get response from the gobiz notif subs api request
    	$response = $this->makeRequest('DELETE', $apiUrl);

    	return true;
    }
}