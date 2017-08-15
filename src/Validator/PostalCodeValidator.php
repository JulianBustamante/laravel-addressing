<?php

namespace Galahad\LaravelAddressing\Validator;

use Galahad\LaravelAddressing\Entity\AdministrativeArea;
use Galahad\LaravelAddressing\Entity\Country;
use Galahad\LaravelAddressing\LaravelAddressing;
use Illuminate\Validation\Validator;

/**
 * Class PostalCodeValidator
 *
 * @package Galahad\LaravelAddressing\Validator
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class PostalCodeValidator
{
	/**
	 * @var array
	 */
	protected $messages = [];
	
	/**
	 * @var LaravelAddressing
	 */
	protected $addressing;
	
	/**
	 * @param LaravelAddressing $addressing
	 */
	public function __construct(LaravelAddressing $addressing)
	{
		$this->addressing = $addressing;
		$this->messages = [
			'postal_code' => trans('laravel-addressing::validation.postal_code'),
		];
	}
	
	/**
	 * Validate a postal code using country and administrative area fields
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @param array $parameters
	 * @param Validator $validator
	 * @return bool|int
	 */
	public function validatePostalCode($attribute, $value, array $parameters, Validator $validator)
	{
		$this->validateParameterCount(2, $parameters, 'postal_code');
		$validator->setCustomMessages($this->messages);
		$country = $this->addressing->findCountry($this->getValue($parameters[0], $validator));
		if ($country instanceof Country) {
			$admAreaValue = $this->getValue($parameters[1], $validator);
			$admArea = $country->findAdministrativeArea($admAreaValue);
			if ($admArea instanceof AdministrativeArea) {
				$postalCodePattern = $admArea->getPostalCodePattern();
				
				return preg_match("/^$postalCodePattern/", $value) === 1;
			}
		}
		
		return false;
	}
	
	/**
	 * @param string $field
	 * @param Validator $validator
	 * @return mixed
	 */
	public function getValue($field, Validator $validator)
	{
		return array_get($validator->getData(), $field);
	}
	
	/**
	 * @return LaravelAddressing
	 */
	public function getAddressing()
	{
		return $this->addressing;
	}
	
	/**
	 * @param LaravelAddressing $addressing
	 */
	public function setAddressing(LaravelAddressing $addressing)
	{
		$this->addressing = $addressing;
	}
	
	/**
	 * @param $count
	 * @param array $parameters
	 * @param $rule
	 */
	protected function validateParameterCount($count, array $parameters, $rule)
	{
		if (count($parameters) !== $count) {
			throw new InvalidArgumentException("Validation rule $rule requires at least $count parameter.");
		}
	}
}
