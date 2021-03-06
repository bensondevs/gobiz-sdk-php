<?php

namespace BensonDevs\Gobiz\Modules;

use BensonDevs\Gobiz\Services\GobizService;

class NotificationSubscription extends GobizService
{
	/*
    |--------------------------------------------------------------------------
    | Subscribe to order notification event Module
    |--------------------------------------------------------------------------
    |
    | This module is handling promotion section of Go-Biz merchant API.
   	| The main reference of this module is this documentation:
   	|
   	| https://docs.gobiz.co.id/docs/index.html#:~:text=Subscribe%20to%20order%20notification%20event
    |
    */

    /**
     * Notification subscription base uri
     * 
     * @var string
     */
    private $baseUri = '/notification-subscriptions/';

    /**
     * Get list of notification subscriptions
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
     * Create notification subscription
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
     * Update notification subscription
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
     * Delete notification subscription
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