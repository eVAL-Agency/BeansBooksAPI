<?php
/**
 * File for class CustomerSearch definition in the BeansBooksAPI project
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
use BeansBooks\Objects\Customer;


/**
 * A short teaser of what CustomerSearch does.
 *
 * More lengthy description of what CustomerSearch does and why it's fantastic.
 *
 * <h3>Usage Examples</h3>
 *
 *
 * @todo Write documentation for CustomerSearch
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
class CustomerSaleSearch extends Search {

	public function __construct(){
		$this->_resultClass = 'BeansBooks\\Objects\\CustomerSale';
		$this->_resultKey = 'sales';
		$this->_url = 'Customer/Sale/Search';

		parent::__construct();
	}

	/**
	 * Limit the search to a specific Beans_Customer.
	 *
	 * @param $id int
	 */
	public function setCustomer($id){
		$this->_searchCriteria['search_customer_id'] = $id;
	}

	/**
	 * generic query string that will be compared to both users ( name, company name, phone number ) and sales ( total, Sale Number, Order Number, and PO Number ).
	 *
	 * @param $keywords string
	 */
	public function setKeywords($keywords){
		$this->_searchCriteria['keywords'] = $keywords;
	}

	/**
	 * Whether or not the sale has been invoiced. Can be true or false.
	 *
	 * @param $invoiced boolean
	 */
	public function setInvoiced($invoiced){
		$this->_searchCriteria['invoiced'] = $invoiced;
	}

	/**
	 * Whether or not the sale has been sent in its current form. Can be true or false.
	 *
	 * @param $sent boolean
	 */
	public function setSent($sent){
		$this->_searchCriteria['sent'] = $sent;
	}

	/**
	 * Whether or not the sale is past due. Can be true or false.
	 *
	 * @param $pastdue boolean
	 */
	public function setPastDue($pastdue){
		$this->_searchCriteria['past_due'] = $pastdue;
	}

	/**
	 * Whether or not the sale has a balance. Can be true or false.
	 *
	 * @param $hasbalance boolean
	 */
	public function setHasBalance($hasbalance){
		$this->_searchCriteria['has_balance'] = $hasbalance;
	}

	/**
	 * Search sales created before a YYYY-MM-DD date.
	 *
	 * @param $date string "YYYY-MM-DD" format
	 */
	public function setCreatedBefore($date){
		$this->_searchCriteria['date_created_before'] = $date;
	}

	/**
	 * Search sales created after a YYYY-MM-DD date.
	 *
	 * @param $date string "YYYY-MM-DD" format
	 */
	public function setCreatedAfter($date){
		$this->_searchCriteria['date_created_after'] = $date;
	}

	/**
	 * Search sales billed before a YYYY-MM-DD date.
	 *
	 * @param $date string "YYYY-MM-DD" format
	 */
	public function setBilledBefore($date){
		$this->_searchCriteria['date_billed_before'] = $date;
	}

	/**
	 * Search sales billed after a YYYY-MM-DD date.
	 *
	 * @param $date string "YYYY-MM-DD" format
	 */
	public function setBilledAfter($date){
		$this->_searchCriteria['date_billed_after'] = $date;
	}
} 