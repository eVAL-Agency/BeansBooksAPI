<?php
/**
 * File for class Model definition in the Agency-Portal project
 * 
 * @package BeansBooks
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141211.2216
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


/**
 * A short teaser of what Model does.
 *
 * More lengthy description of what Model does and why it's fantastic.
 *
 * <h3>Usage Examples</h3>
 *
 *
 * @todo Write documentation for Model
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
class Object extends API {
	protected $_exists      = false;
	protected $_data        = [];
	protected $_datainit    = [];
	protected $_urlbase     = null;
	protected $_resultKey   = null;
	protected $_rawResponse = [];

	public function __construct($id = null){
		parent::__construct();

		if($id && $this->_urlbase && $this->_resultKey){
			$res = $this->query( $this->_urlbase . '/Lookup', ['id' => $id] );

			$this->loadFromArray($res[$this->_resultKey]);
			$this->_rawResponse = $res;
		}
	}

	public function get($key){
		if (array_key_exists($key, $this->_data)) {
			// Check if this data was loaded from the original data array
			return $this->_data[$key];
		}
		else{
			return null;
		}
	}

	public function set($key, $value){
		$this->_data[$key] = $value;
	}

	/**
	 * Get if this model exists in the datastore already.
	 *
	 * @return bool
	 */
	public function exists() {
		return $this->_exists;
	}

	/**
	 * Get if this model is a new entity that doesn't exist in the datastore.
	 *
	 * @return bool
	 */
	public function isnew() {
		return !$this->_exists;
	}

	/**
	 * Get if this model has changes that are pending to be applied back to BeansBooks.
	 *
	 * @param string|null $key Optionally set a key name here to check only that one key.
	 *
	 * @return bool
	 */
	public function changed($key = null){
		foreach ($this->_data as $k => $v) {
			if($key !== null && $key != $k){
				// Allow checking only a specific key.
				continue;
			}

			// It's a standard column, check and see if it matches the datainit value.
			// If the datainit key doesn't exist, that also constitutes as a changed flag!
			if(!array_key_exists($k, $this->_datainit)){
				//echo "$k changed!<br/>\n"; // DEBUG
				return true;
			}

			if($this->_datainit[$k] != $this->_data[$k]){
				// This will match if "blah" is different than "foo", but fails at "" is different than "0".

				//echo "$k changed!<br/>\n"; // DEBUG
				//var_dump($this->_datainit[$k], $this->_data[$k]); // DEBUG
				return true;
			}

			// The data seems to have matched up, nothing to see here, move on!
		}

		// Oh, if it's gotten past all the data keys, then the data must have been identical!
		return false;
	}

	/**
	 * Get the data of this object as an array that can be used in the application.
	 *
	 * @param $keys array|null Optional array of only keys to retrieve.
	 *
	 * @return array
	 */
	public function getAsArray($keys = null){

		if(is_array($keys)){
			$ret = [];
			foreach($keys as $k){
				$ret[$k] = $this->get($k);
			}
			return $ret;
		}
		else{
			return $this->_data;
		}
	}

	/**
	 * Return this object as a flattened JSON array using json_encode.
	 *
	 * @return string
	 */
	public function getAsJSON(){
		return json_encode($this->getAsArray());
	}

	/**
	 * Get the raw data of this model.
	 *
	 * @return array
	 */
	public function getData(){
		return $this->_data;
	}

	/**
	 * Get the initial data of this model as it was when it was loaded from BeansBooks.
	 *
	 * @return array
	 */
	public function getInitialData(){
		return $this->_datainit;
	}

	/**
	 * Set the properties on this object based on its mappings
	 *
	 * @param $array
	 */
	public function setFromArray($array){
		foreach($array as $k => $v){
			$this->set($k, $v);
		}
	}

	/**
	 * Similar to setFromArray, but is intended to be called from a Lookup or Search method
	 *
	 * @param $array Array of data from BeansBooks.
	 */
	public function loadFromArray($array){
		$this->_data = $array;
		$this->_datainit = $array;
		$this->_exists = true;
	}
} 