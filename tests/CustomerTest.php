<?php
/**
 * @todo Enter a meaningful file description here!
 * 
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141106.1350
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

 

class CustomerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @return \BeansBooks\Objects\Customer
	 *
	 * @throws \BeansBooks\Exceptions\RequestException
	 */
	public function testCreateCustomer(){
		$customer = new \BeansBooks\Objects\Customer();
		$customer->firstName = 'Test';
		$customer->lastName = 'User';
		$customer->create();

		$this->assertGreaterThan(0, $customer->id);
		$this->assertEquals('Test', $customer->firstName);
		$this->assertEquals('User', $customer->lastName);

		return $customer;
	}

	/**
	 * @depends testCreateCustomer
	 *
	 * @param \BeansBooks\Objects\Customer $customer
	 *
	 * @return \BeansBooks\Objects\Customer
	 */
	public function testUpdateCustomer($customer){
		$customer->firstName = 'tseT';
		$customer->companyName = 'Acme Corp';
		$customer->update();

		$this->assertEquals('tseT', $customer->firstName);
		$this->assertEquals('Acme Corp', $customer->companyName);

		return $customer;
	}


	public function testSearchCustomer(){
		$search = new \BeansBooks\CustomerSearch();
		$search->searchName = 'e';
		$search->execute();

		$this->assertGreaterThan(0, $search->totalResults);
		// This is a customer-only search!
		foreach($search as $record){
			$this->assertInstanceOf('\\BeansBooks\\Objects\\Customer', $record);
		}
	}


}
 