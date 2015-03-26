<?php
/**
 * File for class VendorSearch definition in the BeansBooksAPI project
 * 
 * @package BeansBooks
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141104.0005
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

namespace BeansBooks;
use BeansBooks\Exceptions;
use BeansBooks\Objects\Vendor;


/**
 * A short teaser of what VendorSearch does.
 *
 * More lengthy description of what VendorSearch does and why it's fantastic.
 *
 * <h3>Usage Examples</h3>
 *
 *
 * @todo Write documentation for VendorSearch
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
 * @package BeansBooks
 * @author Charlie Powell <charlie@eval.bz>
 *
 */
class VendorSearch extends Search {
	public function __construct(){
		$this->_resultClass = 'BeansBooks\\Objects\\Vendor';
		$this->_resultKey = 'vendors';
		$this->_url = 'Vendor/Search';

		parent::__construct();
	}

	/**
	 * Set the search to a wildcard match based on the given email address.
	 *
	 * @param $email string
	 */
	public function setEmail($email){
		$this->_searchCriteria['search_email'] = $email;
	}

	/**
	 * Set the search to a wildcard match based on a given name ( including first, last, and company name ).
	 *
	 * @param $name string
	 */
	public function setName($name){
		$this->_searchCriteria['search_name'] = $name;
	}

	/**
	 * Set the search to a wildcard match on a phone or fax number.
	 *
	 * @param $number string
	 */
	public function setNumber($number){
		$this->_searchCriteria['search_number'] = $number;
	}
} 