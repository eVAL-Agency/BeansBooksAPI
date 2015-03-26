<?php
/**
 * File for class Vendor definition in the BeansBooksAPI project
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
 * @todo Write documentation for Vendor
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
class Vendor extends Object {

	public function __construct($id = null){

		$this->_urlbase = 'Vendor';
		$this->_resultKey = 'vendor';

		parent::__construct($id);
	}

	public function getRemitAddress(){
		$ret = null;

		if($this->get('default_remit_address_id')){
			$ret = VendorAddress::Lookup($this->get('default_remit_address_id'));
		}

		return $ret;
	}

	public function create(){
		if($this->get('id')){
			throw new Exceptions\RequestException('This customer already has an ID, please use update instead of create!');
		}
		if(!$this->get('first_name')){
			throw new Exceptions\RequestException('Every customer must have a first name, unable to create customer as requested.');
		}
		if(!$this->get('last_name')){
			throw new Exceptions\RequestException('Every customer must have a last name, unable to create customer as requested.');
		}

		$array = $this->getAsArray(
			[
				'id', 'first_name', 'last_name', 'company_name', 'email',
				'phone_number', 'fax_number', 'default_account_id'
			]
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
			throw new Exceptions\RequestException('This customer does not have an ID, please use create instead of update!');
		}
		if(!$this->get('first_name')){
			throw new Exceptions\RequestException('Every customer must have a first name, unable to create customer as requested.');
		}
		if(!$this->get('last_name')){
			throw new Exceptions\RequestException('Every customer must have a last name, unable to create customer as requested.');
		}

		$array = $this->getAsArray(
			[
				'id', 'first_name', 'last_name', 'company_name', 'email',
				'phone_number', 'fax_number', 'default_account_id', 'default_remit_address_id'
			]
		);

		$res = $this->query( $this->_urlbase . '/Update', $array );

		$this->loadFromArray($res[$this->_resultKey]);
	}
} 