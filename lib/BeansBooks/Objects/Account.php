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
 * @todo Write documentation for Account
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
class Account extends Object {

	public function __construct($id = null){

		$this->_urlbase = 'Account';
		$this->_resultKey = 'account';

		parent::__construct($id);
	}

	public function create(){
		if($this->get('id')){
			throw new Exceptions\RequestException('This account already has an ID, please use update instead of create!');
		}

		if(!$this->get('account_type_id')){
			throw new Exceptions\RequestException('All accounts must have an account_type_id attribute!');
		}

		if(!$this->get('parent_account_id')){
			throw new Exceptions\RequestException('All accounts must have a parent_account_id attribute!');
		}

		if(!$this->get('name')){
			throw new Exceptions\RequestException('All accounts must have a name attribute!');
		}

		if(!$this->get('code')){
			throw new Exceptions\RequestException('All accounts must have a code attribute!');
		}

		if(!$this->get('writeoff')){
			throw new Exceptions\RequestException('All accounts must have a writeoff attribute!');
		}

		$array = $this->getAsArray(
			[
				'account_type_id', 'parent_account_id', 'name', 'code', 'writeoff', 'terms'
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
			throw new Exceptions\RequestException('This account does not have an ID, please use create instead of update!');
		}

		$array = $this->getAsArray(
			[
				'id', 'parent_account_id', 'name', 'code', 'writeoff', 'terms'
			]
		);

		$res = $this->query( $this->_urlbase . '/Update', $array );

		$this->loadFromArray($res[$this->_resultKey]);
	}
} 