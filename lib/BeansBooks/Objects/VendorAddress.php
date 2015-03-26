<?php
/**
 * File for class VendorAddress definition in the BeansBooksAPI project
 * 
 * @package BeansBooks\Objects
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141106.1530
 * @copyright Copyright (C) 2009-2014  Charlie Powell
 * @license GNU Affero General Public License v3 <http://www.gnu.org/licenses/agpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/agpl-3.0.txt.
 */

namespace BeansBooks\Objects;
use BeansBooks\API;
use BeansBooks\Exceptions;
use BeansBooks\Object;


/**
 * A short teaser of what Vendor does.
 *
 * More lengthy description of what Vendor does and why it's fantastic.
 *
 * <h3>Usage Examples</h3>
 *
 *
 * @todo Write documentation for VendorAddress
 * <h4>Example 1</h4>
 * <p>Description 1</p>
 * <code>
 * // Some code for example 1
 * $a = $b;
 * </code>
 *
 *
 * <h4>Example 2</h4>
 * <p>Description 2</p>
 * <code>
 * // Some code for example 2
 * $b = $a;
 * </code>
 *
 * 
 * @package BeansBooks\Objects
 * @author Charlie Powell <charlie@eval.bz>
 *
 */
class VendorAddress extends Object {

	private static $_LookupCache = [];

	public function __construct($id = null){

		$this->_urlbase = 'Vendor/Address';
		$this->_resultKey = 'address';

		parent::__construct($id);
	}

	public function create(){
		if($this->get('id')){
			throw new Exceptions\RequestException('This vendor address already has an ID, please use update instead of create!');
		}
		if(!$this->get('address1')){
			throw new Exceptions\RequestException('Every vendor address must have an address1, unable to create as requested.');
		}
		if(!$this->get('city')){
			throw new Exceptions\RequestException('Every vendor address must have a city, unable to create as requested.');
		}
		if(!$this->get('zip')){
			throw new Exceptions\RequestException('Every vendor address must have a zip, unable to create as requested.');
		}
		if(!$this->get('country')){
			throw new Exceptions\RequestException('Every vendor address must have a country, unable to create as requested.');
		}

		$array = $this->getAsArray(
			['vendor_id', 'first_name', 'last_name', 'company_name', 'address1', 'address2', 'city', 'state', 'zip', 'country']
		);

		$res = $this->query( $this->_urlbase . '/Create', $array );

		$this->loadFromArray($res[$this->_resultKey]);
	}

	public function update(){
		if(!$this->changed()){
			// No change, no reason to push anything.
			return;
		}

		if(!$this->get('id')){
			throw new Exceptions\RequestException('This vendor address does not have an ID, please use create instead of update!');
		}
		if(!$this->get('address1')){
			throw new Exceptions\RequestException('Every vendor address must have an address1, unable to update as requested.');
		}
		if(!$this->get('city')){
			throw new Exceptions\RequestException('Every vendor address must have a city, unable to update as requested.');
		}
		if(!$this->get('zip')){
			throw new Exceptions\RequestException('Every vendor address must have a zip, unable to update as requested.');
		}
		if(!$this->get('country')){
			throw new Exceptions\RequestException('Every vendor address must have a country, unable to update as requested.');
		}

		$array = $this->getAsArray(
			['id', 'first_name', 'last_name', 'company_name', 'address1', 'address2', 'city', 'state', 'zip', 'country']
		);

		$res = $this->query( $this->_urlbase . '/Update', $array );

		$this->loadFromArray($res[$this->_resultKey]);
	}

	public static function Lookup($id) {
		if(isset(self::$_LookupCache[$id])){
			// Enable caching
			return self::$_LookupCache[$id];
		}

		$obj = new self();
		$res = $obj->query(
			'Vendor/Address/Lookup',
			[
				'id' => $id
			]
		);

		$obj->setFromArray($res['address']);

		// Cache for next lookup request
		self::$_LookupCache[$id] = $obj;

		return $obj;
	}
}
