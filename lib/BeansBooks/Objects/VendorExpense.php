<?php
/**
 * File for class Customer definition in the BeansBooksAPI project
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
 * A short teaser of what Customer does.
 *
 * More lengthy description of what Customer does and why it's fantastic.
 *
 * <h3>Usage Examples</h3>
 *
 *
 * @todo Write documentation for Customer
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
class VendorExpense extends Object {

	public function __construct($id = null){

		$this->_urlbase = 'Vendor/Expense';
		$this->_resultKey = 'expense';

		parent::__construct($id);
	}

	public function getBillingAddress(){
		$ret = null;

		if($this->get('default_remit_address_id')){
			$ret = VendorAddress::Lookup($this->get('default_remit_address_id'));
		}

		return $ret;
	}

	/**
	 * Get an array of lines for this expense
	 *
	 * Must be used after lookup is executed.
	 *
	 * @return array
	 */
	public function getLines(){
		$sales = [];

		if(isset($this->_rawResponse['lines'])){
			$sales = $this->_rawResponse['lines'];
		}

		return $sales;
	}

	public function create(){
		if($this->get('id')){
			throw new Exceptions\RequestException('This vendor expense already has an ID, please use update instead of create!');
		}
		if(!$this->get('vendor_id')){
			throw new Exceptions\RequestException('Every vendor expense must have a vendor, unable to create expense as requested.');
		}
		if(!$this->get('account_id')){
			throw new Exceptions\RequestException('Every vendor expense must have an account, unable to create expense as requested.');
		}
		if(!$this->get('date_created')){
			throw new Exceptions\RequestException('Every vendor expense must have a date created, unable to create expense as requested.');
		}
		if(!$this->get('lines')){
			throw new Exceptions\RequestException('Every vendor expense must have expense lines, unable to create expense as requested.');
		}

		$array = $this->getAsArray(
			[
				'vendor_id', 'account_id', 'date_created', 'expense_number',
				'invoice_number', 'so_number', 'remit_address_id', 'lines'
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
			throw new Exceptions\RequestException('This vendor expense does not have an ID, please use create instead of update!');
		}
		if(!$this->get('lines')){
			throw new Exceptions\RequestException('Every vendor expense must have expense lines, unable to update expense as requested.');
		}

		$array = $this->getAsArray(
			[
				'id', 'account_id', 'date_created', 'expense_number',
				'invoice_number', 'so_number', 'remit_address_id', 'lines'
			]
		);

		$res = $this->query( $this->_urlbase . '/Update', $array );

		$this->loadFromArray($res[$this->_resultKey]);
	}
} 