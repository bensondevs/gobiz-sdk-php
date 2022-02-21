<?php

namespace BensonDevs\Gobiz\Modules;

use Illuminate\Support\Facades\Storage;
use BensonDevs\Gobiz\Services\GobizService;

class Transaction extends GobizService
{
	/*
	|--------------------------------------------------------------------------
	| Transaction Module
	|--------------------------------------------------------------------------
	|
	| This module is handling promotion section of Go-Biz pay integration.
   	| The main reference of this module is this documentation:
   	|
   	| https://docs.gobiz.co.id/docs/index.html#:~:text=force_close%22%3A%20true/false%0A%20%20%20%7D%0A%7D-,Pay%20Integration,-Pre%2DRequisite
    |
    */

    /**
     * Pay integration base uri
     * 
     * @var string
     */
    private $baseUri = '/transactions/';

    /**
     * Payment type of the transaction
     * 
     * @var string
     */
    private $paymentType = 'qris';

    /**
     * Outlet that do the transaction
     * 
     * @var array<string, mixed>
     */
    private $outlet = [];

    /**
     * Transaction details
     * 
     * @var array<string, mixed>
     */
    private $transaction = [];

    /**
     * Item details of the transaction
     * 
     * @var array<int, <string, mixed>>
     */
    private $items = [];

    /**
     * Customer details
     * 
     * @var array<string, string>
     */
    private $customer = [];

    /**
     * Module class getter PHP magic method.
     * 
     * This will convert the unknown attribute called
     * to it's camel case version of method.
     * 
     * Example:
     * 		`$transaction->payment_type`
     * 		is equal to
     * 		`$transaction->getPaymentType()`
     * 
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key)
    {
    	// Get the method name of the class
    	$methodName = 'get' . str_camel_case($key);

    	return $this->{$methodName}();
    }

    /**
     * Module class setter PHP magic method.
     * 
     * This will convert the unknown attribute called
     * to it's camel case version of method.
     * 
     * Example:
     * 		`$transaction->payment_type = $value`
     * 		is equal to
     * 		`$transaction->setPaymentType($value)`
     * 
     * @param  string  $key
     * @param  mixed   $value
     * @return mixed
     */
    public function __set(string $key, $value)
    {
    	// Get the method name of the class
    	$methodName = 'get' . str_camel_case($key);

    	return $this->{$methodName}($value);
    }

    /**
     * Set the payment type of the transaction
     * 
     * @param  string  $type
     * @return $this
     */
    public function setPaymentType(string $type = 'qris')
    {
    	$this->paymentType = $type;

    	return $this;
    }

    /**
     * Get the payment type of the transaction
     * 
     * @return string
     */
    public function getPaymentType()
    {
    	return $this->paymentType;
    }

    /**
     * Set the outlet instance of transation
     * 
     * @param  array|stdClass  $outlet
     * @return $this
     */
    public function setOutlet($outlet)
    {
    	// Convert stdClass to array
    	if (! is_array($outlet)) {
    		$outlet = json_decode(json_encode($outlet), true);
    	}

    	// Set the outlet
    	$this->outlet = $outlet ?: [];

    	return $this;
    }

    /**
     * Get the outlet instance of transation
     * 
     * @return  array
     */
    public function getOutlet()
    {
    	return $this->outlet;
    }

    /**
     * Set transaction details
     * 
     * @param   array  $details
     * @return  $this
     */
    public function setTransactionDetails(array $details)
    {
    	$this->transaction = $details;

    	return $this;
    }

    /**
     * Get transaction details
     * 
     * @return  array
     */
    public function getTransactionDetails()
    {
    	return $this->transaction;
    }

    /**
     * Set item details of the transactions
     * 
     * @param  array  $items
     * @return $this
     */
    public function setItemDetails(array $items)
    {
    	$this->items = $items;

    	// Recount the item total 
    	// This will help transaction details
		if (isset($this->transaction['gross_amount'])) {
	    	foreach ($this->items as $item) {
    			// Count item sub total
    			$itemSubTotal = 0;
    			if (isset($item['price'])) {
    				$itemSubTotal += $item['price'];
    			}
    			if (isset($item['quantity'])) {
    				$itemSubTotal = $itemSubTotal * $item['quantity'];
    			}

    			// Add to transation gross amount
    			$this->transaction['gross_amount'] += $itemSubTotal;
	    	}
		}

    	return $this;
    }

    /**
     * Add items for the transactions
     * 
     * @param  array  $item
     * @return $this
     */
    public function addItemDetail(array $item)
    {
    	$items = $this->items;
    	array_push($items, $item);

    	return $this->setItemDetails($items);
    }

    /**
     * Get item details of the transaction
     * 
     * @return array
     */
    public function getItemDetails()
    {
    	return $this->items;
    }

    /**
     * Set the customer details of the transaction
     * 
     * @param  array  $customer
     * @return $this
     */
    public function setCustomerDetails(array $customer)
    {
    	$this->customer = $customer;

    	return $this;
    }

    /**
     * Get the customer details of the transaction
     * 
     * @return array
     */
    public function getCustomerDetails()
    {
    	return $this->customer;
    }

    /**
     * Create transaction.
     * 
     * @param  array  $data
     * @return array
     */
    public function create(array $data = [])
    {
    	// Prepare transaction payment type
    	if (! isset($data['payment_type'])) {
    		$data['payment_type'] = $this->getPaymentType();
    	}

    	// Prepare transaction outlet
    	if (! isset($data['outlet'])) {
    		$data['outlet'] = $this->getOutlet();
    	}

    	// Prepare transation details
    	if (! isset($data['transaction_details'])) {
    		$data['transaction_details'] = $this->getTransactionDetails();
    	}

    	// Prepare item details
    	if (! isset($data['item_details'])) {
    		$data['item_details'] = $this->getItemDetails();
    	}

    	// Prepare customer details
    	if (! isset($data['customer_details'])) {
    		$data['customer_details'] = $this->getCustomer();
    	}

    	// Prepare API URL endpoint
    	$apiUrl = $this->apiUrl($this->baseUri);

    	// Get response from the gobiz transactions api request
    	$response = $this->makeRequest('POST', $apiUrl, $data);

    	return $response;
    }

    /**
     * Get transaction detail
     * 
     * @param  string  $transactionId
     * @return array
     */
    public function find(string $transactionId)
    {
    	// Prepare API URL endpoint
    	$apiUrl = $this->apiUrl(concat_paths([
    		$this->baseUri, $transactionId
    	]));

    	// Get response from the gobiz transaction api request
    	$response = $this->makeRequest('GET', $apiUrl);

    	return $response;
    }

    /**
     * Get rendered QRIS Transaction Image
     * 
     * @param  string  $transactionId
     * @return image
     */
    public function getQrisTransactionImage(string $transactionId)
    {
    	// Prepare API URL endpoint
    	$apiUrl = $this->apiUrl(concat_paths([
    		$this->baseUri, $transactionId, 'qr-code'
    	]));

    	// Get image from the gobiz transactions api request
    	$this->guzzleClient->addHeader('Accept', 'image/png');
    	$image = $this->guzzleClient->get($apiUrl);

    	return $image;
    }

    /**
     * Get rendered QRIS Transaction Image URL.
     * 
     * This method extends the official endpoint return
     * of image to the URL. This uses laravel storage
     * to save the image locally and later on you can
     * use the URL to access the image in the local.
     * 
     * @param   string  $transactionId
     * @return  string
     */
    public function getQrisTransactionImageUrl(string $transactionId)
    {
    	$image = $this->getQrisTransactionImage($transactionId);

    	// Store image to the local path
    	$path = public_path('/gobiz/' . $transactionId . '.png');
    	Storage::put($path, $image);

    	return $path;
    }
}