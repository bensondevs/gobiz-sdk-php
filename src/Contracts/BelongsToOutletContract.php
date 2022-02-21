<?php

namespace BensonDevs\Gobiz\Contracts;

interface BelongsToOutletContract
{
	/**
	 * The implemeting class must have the outlet id setter
	 * 
	 * @param  string  $outletId
	 * @return $this
	 */
	public function setOutlet(string $outletId);
}