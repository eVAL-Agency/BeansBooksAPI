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
class AccountSearch extends Search {

	// BeansBooks doesn't allow any search criteria on Accounts, implement all that logic internally.

	public function __construct(){
		$this->_resultClass = 'BeansBooks\\Objects\\Account';
		$this->_resultKey = 'accounts';
		$this->_url = 'Account/Search';

		parent::__construct();
	}

	/**
	 * Get a flat list of accounts based on the requested search criteria.
	 *
	 * @param bool              $hierarchical Set to false to return a flat list instead of hierarchical.
	 * @param null|string|array $type         Account Type keyword (or keywords) to match on.
	 * @param null|bool         $reserved     Set to NULL to ignore flag, TRUE to only return reserved accounts, FALSE to only return standard accounts
	 * @param null|bool         $payable      NULL to ignore, TRUE to return only payable, FALSE anything but.
	 * @param null|bool         $receivable   NULL to ignore, TRUE to return only receivable, FALSE anything but.
	 *
	 * @return array
	 *
	 * @throws Exceptions\GeneralException
	 */
	public function getFlatList($hierarchical = true, $type = null, $reserved = null, $payable = null, $receivable = null){
		if($this->results === null){
			// Execute the initial query first!
			$this->execute();
		}

		// Build a flat list of all accounts, (so I have child-parent relationships without querying multiple times).
		$allaccounts = [];
		$accounts = [];
		if($hierarchical){
			foreach($this->results as $a){
				/** @var $a Objects\Account */
				$allaccounts[ $a->get('id') ] = $a;
			}
		}

		// I only need payable accounts here.
		foreach($this->results as $a){
			/** @var $a Objects\Account */
			//var_dump($a->getAsArray());

			$parents = [];
			if($hierarchical){
				$parent = $a->get('parent_account_id');
				while($parent){
					$parents[] = $allaccounts[$parent]->get('name');
					$parent = $allaccounts[$parent]->get('parent_account_id');
				}
				$parents = array_reverse($parents);
			}

			if(sizeof($parents)){
				$label = implode($parents, ' -> ') . ' -> ' . $a->get('name');
			}
			else{
				$label = $a->get('name');
			}
			//$type = $a->get('type');

			// Requested to skip reserved accounts?
			if($reserved !== null && $reserved != $a->get('reserved')){
				continue;
			}

			if($payable !== null && $payable != $a->get('payable')){
				continue;
			}

			if($receivable !== null && $receivable != $a->get('receivable')){
				continue;
			}

			if($type !== null){
				// Type matching is requested.

				// Skip entries that have no type.
				if(!$a->get('type')){
					continue;
				}

				// Type is an array and this record is not in the array.
				if(is_array($type) && !in_array($a->get('type')['type'], $type)){
					continue;
				}

				if(is_scalar($type) && $type != $a->get('type')['type']){
					continue;
				}
			}

			$accounts[ $a->get('id') ] = $label;

		}
		asort($accounts);

		return $accounts;
	}
} 