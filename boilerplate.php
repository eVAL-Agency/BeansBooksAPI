<?php
/**
 * A main boilerplate for how to use BeansBooks,
 * along with the necessary information to get it setup.
 *
 * This file can be used, or completely omitted if importing into a framework
 * that can handle file includes and configuration setting.
 * 
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141104.1303
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

$here = __DIR__;
if(!file_exists($here . '/config.php')){
	die('Please copy config.example.php to config.php and edit that file as necessary!');
}

// Load the configuration file.
// Really all that is needed are your API access tokens and the URL to connect to.
// These are illustrated in config.example.php.
require_once('config.php');

// Load the application itself, along with all supplemental files.

// Load the exceptions first, these are pretty basic classes that have no dependencies.
require_once('lib/BeansBooks/Exceptions/GeneralException.php');
require_once('lib/BeansBooks/Exceptions/NetworkException.php');
require_once('lib/BeansBooks/Exceptions/ResponseException.php');
require_once('lib/BeansBooks/Exceptions/AuthException.php');
require_once('lib/BeansBooks/Exceptions/RequestException.php');

// Load the main API base, this acts as a parent class for all extending classes.
require_once('lib/BeansBooks/API.php');

// Load the actual classes that perform some action.
require_once('lib/BeansBooks/CustomerSearch.php');
require_once('lib/BeansBooks/Objects/Customer.php');