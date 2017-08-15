<?php

namespace Galahad\LaravelAddressing\Collection;

use CommerceGuys\Addressing\Repository\SubdivisionRepository;
use Illuminate\Support\Collection;

/**
 * Class AdministrativeAreaCollection
 *
 * @package Galahad\LaravelAddressing\Collection
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class AdministrativeAreaCollection extends Collection
{
	/**
	 * @var array|mixed
	 */
	protected $countryCode;
	
	/**
	 * @var string
	 */
	protected $parentId;
	
	/**
	 * @var string|null
	 */
	protected $locale = null;
	
	/**
	 * @var SubdivisionRepository
	 */
	protected $subdivisionRepository;
	
	/**
	 * @return array|mixed
	 */
	public function getCountryCode()
	{
		return $this->countryCode;
	}
	
	/**
	 * @param array|mixed $countryCode
	 */
	public function setCountryCode($countryCode)
	{
		$this->countryCode = $countryCode;
	}
	
	/**
	 * @return mixed
	 */
	public function getParentId()
	{
		return $this->parentId;
	}
	
	/**
	 * @param mixed $parentId
	 */
	public function setParentId($parentId)
	{
		$this->parentId = $parentId;
	}
	
	/**
	 * @return null
	 */
	public function getLocale()
	{
		return $this->locale;
	}
	
	/**
	 * @param null $locale
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
	}
	
	/**
	 * @return mixed
	 */
	public function getSubdivisionRepository()
	{
		return $this->subdivisionRepository;
	}
	
	/**
	 * @param mixed $subdivisionRepository
	 */
	public function setSubdivisionRepository(SubdivisionRepository $subdivisionRepository)
	{
		$this->subdivisionRepository = $subdivisionRepository;
	}
}
