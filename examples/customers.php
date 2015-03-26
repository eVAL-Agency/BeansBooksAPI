<?php
/**
 * @todo Enter a meaningful file description here!
 * 
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141104.1320
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

require_once('../boilerplate.php');

try{
	$customersearch = new \BeansBooks\CustomerSearch();
	$customersearch->searchName = 'smith';
	//$customersearch->execute();

	foreach($customersearch as $c){
		var_dump($c);
	}
}
catch(Exception $e){
	die($e->getMessage());
}

var_dump($customersearch, \BeansBooks\API::$Requests);