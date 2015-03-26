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


/**
 * Base class for the underlying Search system in BeansBooks.
 *
 * This class by itself is useless, but can be extended by another class to gain the base functionality.
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
class Search extends API implements \Iterator {
	/** @var int The page to return */
	public $page = 0;
	/** @var int The number of results on each page. */
	protected $pageSize = 50;
	/** @var int Total pages found */
	public $totalResults = 0;
	/** @var boolean Require all provided fields to match. */
	public $searchAnd = false;
	/** @var null|array Array of results from this search criteria, repopulated each time execute is called. */
	public $results = null;
	/** @var array Associative array of search criteria, utilized by the extending *Search class. */
	protected $_searchCriteria = [];
	/** @var int Position tracker for array access */
	protected $_pos = 0;
	/** @var string Base URL to perform the search on, set by the extending *Search class. */
	protected $_url = '';
	/** @var string The sort pattern for the returned results: 'newest', 'oldest' */
	protected $sortBy = 'newest';
	/** @var string Result key to search for results from within, set by the extending *Search class */
	protected $_resultKey = '';
	/** @var string Class name, (fully resolved), to wrap each result in.  If omitted, an associative array is used instead. */
	protected $_resultClass = '';

	/**
	 * Set the page size for results.
	 *
	 * Will clear away all previous results when set.
	 *
	 * @param int $size
	 *
	 * @throws Exceptions\GeneralException
	 */
	public function setPageSize($size){
		if($size == $this->pageSize){
			// No change.
			return;
		}

		if(!is_numeric($size)){
			throw new Exceptions\GeneralException('Please set page size as a valid number');
		}

		// If there are results, clear them out!
		$this->clearResults();

		$this->pageSize = $size;
	}

	/**
	 * Set the sort by field (aka order by)
	 *
	 * MUST be either "newest" or "oldest".
	 *
	 * @param string $sort
	 *
	 * @throws Exceptions\GeneralException
	 */
	public function setSortBy($sort){
		// If there are results, clear them out!
		$this->clearResults();

		switch($sort){
			case 'newest':
			case 'oldest':
				$this->sortBy = $sort;
				break;
			default:
				throw new Exceptions\GeneralException('Invalid sort criteria, please set only "newest" or "oldest"');
		}
	}


	/**
	 * @throws Exceptions\AuthException
	 * @throws Exceptions\NetworkException
	 * @throws Exceptions\ResponseException
	 * @throws Exceptions\GeneralException
	 */
	public function execute(){
		if(!$this->_url){
			throw new Exceptions\GeneralException('Please set the search URL in the extending class!');
		}

		if(!$this->_resultKey){
			throw new Exceptions\GeneralException('Please set the result key in the extending class!');
		}

		$payload = [
			'sort_by' => $this->sortBy,
			'page' => $this->page,
			'page_size' => $this->pageSize,
			'search_and' => $this->searchAnd,
		];

		$payload = array_merge($payload, $this->_searchCriteria);

		$res = $this->query( $this->_url, $payload );

		if($this->_resultClass){
			$ref = new \ReflectionClass($this->_resultClass);
		}
		else{
			$ref = null;
		}

		if($this->page == 0 || $this->results === null){
			// Replace, (first page)
			$this->results = [];
		}

		foreach($res[ $this->_resultKey ] as $record){
			if($ref){
				$object = $ref->newInstance();
				$object->loadFromArray($record);
				$this->results[] = $object;
			}
			else{
				$this->results[] = $record;
			}
		}

		$this->totalResults = $res['total_results'];
	}

	/**
	 * Clear all results and zero out counters.
	 *
	 * This is called internally in various locations.
	 */
	public function clearResults(){
		$this->totalResults = 0;
		$this->page = 0;
		$this->results = null;
		$this->_pos = 0;
	}

	/****  Iterator Methods *****/

	function rewind() {
		$this->_pos = 0;
	}

	function current() {
		if($this->results === null){
			// If no data was selected before... I need to execute the query!
			$this->_pos = 0;
			$this->execute();
		}

		return isset($this->results[$this->_pos]) ? $this->results[$this->_pos] : null;
	}

	function key() {
		if($this->results === null){
			// If no data was selected before... I need to execute the query!
			$this->_pos = 0;
			$this->execute();
		}

		return $this->_pos;
	}

	function next() {
		// Advance!
		++$this->_pos;

		if($this->results === null){
			// If no data was selected before... I need to execute the query!
			$this->_pos = 0;
			$this->execute();
		}

		if(isset($this->results[$this->_pos])){
			// The next element exists, yay.
			return $this->results[$this->_pos];
		}
		elseif($this->totalResults > sizeof($this->results)){
			// The next element doesn't exist, but there are more pages!
			++$this->page;
			$this->execute();

			return $this->results[$this->_pos];
		}
		else{
			// Nothing more here, false to signify that.
			return false;
		}
	}

	function valid() {
		if($this->results === null){
			// If no data was selected before... I need to execute the query!
			$this->_pos = 0;
			$this->execute();
		}

		return isset($this->results[$this->_pos]);
	}
} 