<?php
/**
 * File for class API definition in the BeansBooksAPI project
 * 
 * @package BeansBooks
 * @author Charlie Powell <charlie@eval.bz>
 * @date 20141103.2239
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
 * A short teaser of what API does.
 *
 * More lengthy description of what API does and why it's fantastic.
 *
 * <h3>Usage Examples</h3>
 *
 *
 * @todo Write documentation for API
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
class API {
	/** @var null|int Auth_UID provided by beans */
	private $_authUid = null;
	/** @var null|int Expiration provided by beans */
	private $_authExpiration = null;
	/** @var null|string Auth Key provided by beans */
	private $_authKey = null;
	/** @var null|string Server name (with protocol) to connect to */
	protected $_host = null;
	/** @var string Version of this API library */
	protected $_version = '1.0.0';
	/** @var array Cache of all requests and their meta data, (useful for performance and debugging) */
	public static $Requests = [];

	public function __construct(){
		if(!function_exists('curl_init')){
			throw new Exceptions\GeneralException('cURL is required for BeansBooks API to function.');
		}

		// If you have a good reason as to why these shouldn't look for defined constants, let me know!
		// This allows this system to be dropped into
		if(defined('BEANSBOOKS_AUTH_UID')){
			$this->setAuthUid(BEANSBOOKS_AUTH_UID);
		}
		if(defined('BEANSBOOKS_AUTH_KEY')){
			$this->setAuthKey(BEANSBOOKS_AUTH_KEY);
		}
		if(defined('BEANSBOOKS_AUTH_EXPIRATION')){
			$this->setAuthExpiration(BEANSBOOKS_AUTH_EXPIRATION);
		}
		if(defined('BEANSBOOKS_HOST')){
			$this->setHost(BEANSBOOKS_HOST);
		}
	}

	/**
	 * Set the UID for authentication.
	 *
	 * @param int $uid
	 *
	 * @throws Exceptions\GeneralException
	 */
	public function setAuthUid($uid){
		if(!is_numeric($uid)){
			throw new Exceptions\GeneralException('auth_uid must be a integer.');
		}

		$this->_authUid = $uid;
	}

	/**
	 * Set the key for authentication.
	 *
	 * @param string $key
	 */
	public function setAuthKey($key){
		if(function_exists('gzcompress')){
			// Compress the key just to prevent over-the-shoulder snooping when viewing debug information.
			// var_dump will otherwise display the key in plain-text.
			// This wouldn't be an issue, except that var_dump is extremely useful,
			// and some people may develop in a public coffee shoppe.
			$this->_authKey = gzcompress($key, 1);
		}
		else{
			$this->_authKey = $key;
		}
	}

	/**
	 * Set the expiration int for authentication.
	 *
	 * @param int $expire
	 *
	 * @throws Exceptions\GeneralException
	 */
	public function setAuthExpiration($expire){
		if(!is_numeric($expire)){
			throw new Exceptions\GeneralException('auth_expiration must be a integer.');
		}

		$this->_authExpiration = $expire;
	}

	/**
	 * Set the host to connect to.
	 *
	 * @param string $host
	 */
	public function setHost($host){
		// Beginning whitespace trim
		$host = ltrim($host);

		if(strpos($host, '://') === false){
			// No protocol given, assume http://, I guess....
			$host = 'http://' . $host;
		}

		// Trim ending slashes, these will be taken care of internally.
		$host = rtrim($host, " \t\n\r\0\x0B/");

		if(strpos($host, '/api') === strlen($host) - 4){
			// Trim off any /api on the URL, this will also be taken care of internally.
			$host = substr($host, 0, -4);
		}

		$this->_host = $host;
	}

	/**
	 * @param $uri
	 * @param $data
	 *
	 * @throws Exceptions\AuthException
	 * @throws Exceptions\NetworkException
	 * @throws Exceptions\RequestException
	 * @throws Exceptions\ResponseException
	 * @return mixed
	 */
	protected function query($uri, $data){
		$payload = [
			'auth_uid' => $this->_authUid,
		    'auth_key' => $this->_authKey,
		    'auth_expiration' => $this->_authExpiration,
		];

		if(!$this->_authKey){
			throw new Exceptions\AuthException('No authkey provided');
		}

		if(function_exists('gzcompress')){
			// Decompress the key just to prevent over-the-shoulder snooping when viewing debug information.
			// var_dump will otherwise display the key in plain-text.
			// This wouldn't be an issue, except that var_dump is extremely useful,
			// and some people may develop in a public coffee shoppe.
			$payload['auth_key'] = gzuncompress($payload['auth_key']);
		}

		// Merge the data into the payload.
		$payload = array_merge($payload, $data);

		// This will be a flattened string.
		$payload_str = json_encode($payload);

		$fullurl = $this->_host . '/api/' . $uri;

		// Grab a snapshot of the current time (in ms), for reporting and performance reasons.
		$start = microtime(true) * 1000;
		// Perform the request
		$response = $this->_splitResult(
			$this->_postPayload($payload_str, $fullurl)
		);
		// Close up the connection time and round the results, 120 will be 120ms.
		$time = round( (microtime(true) * 1000 - $start), 3);

		if($response['code'] == 302 && isset($response['headers']['Location'])){
			// Hmm, try that again I suppose.
			// This will allow for ONE 302 redirect.

			self::$Requests[] = [
				'time'    => $time,
			    'uri'     => $uri,
			    'url'     => $fullurl,
			    'headers' => $response['headers'],
			    'result'  => 'REDIRECT',
			];

			// Grab a snapshot of the current time (in ms), for reporting and performance reasons.
			$start = microtime(true) * 1000;
			// Perform the request
			$response = $this->_splitResult(
				$this->_postPayload($payload_str, $response['headers']['Location'])
			);
			// Close up the connection time and round the results, 120 will be 120ms.
			$time = round( (microtime(true) * 1000 - $start), 3);
		}

		$debug = [
			'time'    => $time,
			'uri'     => $uri,
			'url'     => $fullurl,
			'headers' => $response['headers'],
			'result'  => 'EXCEPTION',
		];

		// This will have the result reset to OK after everything passes.
		self::$Requests[] =& $debug;

		if(!isset($response['headers']['Content-Type'])){
			throw new Exceptions\ResponseException('Server ' . $this->_host . ' did not return a valid Content-Type header, refusing to process payload');
		}

		if($response['headers']['Content-Type'] != 'application/json'){
			throw new Exceptions\ResponseException('Server ' . $this->_host . ' did not return application/json as the Content-Type header, refusing to process payload');
		}

		// All payloads from BeansBooks are JSON encoded.
		// Anything else causes an exception.
		$body = json_decode($response['body'], true);

		if(!$body){
			throw new Exceptions\ResponseException('Server ' . $this->_host . ' did not return valid JSON data, unable to process payload');
		}

		if(!isset($body['success'])){
			throw new Exceptions\ResponseException('Server ' . $this->_host . ' did not return a "success" flag with the JSON data');
		}

		if(!$body['success'] && isset($body['auth_error']) && $body['auth_error']){
			throw new Exceptions\AuthException($body['auth_error']);
		}

		if(!$body['success'] && isset($body['error']) && $body['error']){
			throw new Exceptions\RequestException($body['error']);
		}

		// All the checks passed!
		$debug['result'] = 'OK';
		return $body['data'];
	}

	/**
	 * Craft a cURL payload and send it to the requested URL as a POST block.
	 *
	 * @param string $payload_str
	 * @param string $url
	 *
	 * @throws Exceptions\NetworkException
	 *
	 * @return string
	 */
	private function _postPayload($payload_str, $url){
		$headers = [
			'User-Agent: BeansBooks API ' . $this->_version . ' (http://eval.agency)',
			'Servername: ' . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost'),
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload_str),
		];

		// And transmit the POST request.
		$curl = curl_init();
		curl_setopt_array(
			$curl, [
				CURLOPT_HEADER         => true,
				CURLOPT_NOBODY         => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL            => $url,
				CURLOPT_HTTPHEADER     => $headers,
				CURLOPT_POST           => 1,
				CURLOPT_POSTFIELDS     => $payload_str,
			]
		);

		// Perform the actual request
		$result = curl_exec($curl);
		if($result === false){
			switch(curl_errno($curl)){
				case CURLE_COULDNT_CONNECT:
				case CURLE_COULDNT_RESOLVE_HOST:
				case CURLE_COULDNT_RESOLVE_PROXY:
					throw new Exceptions\NetworkException('Could not connect to ' . $url, 404);
					break;
				default:
					throw new Exceptions\NetworkException('Server did not return with a successful response', 500);
					break;
			}
		}

		curl_close($curl);

		return $result;
	}

	/**
	 * Split the headers, status, and body from a cURL response.
	 *
	 * Will return an array of {code: ###, headers: { ... }, body: ""}
	 *
	 * @param string $result
	 *
	 * @return array
	 */
	private function _splitResult($result){
		$response = [
			'code'    => 400,
			'headers' => [],
		    'body' => null,
		];

		$h = explode("\n", $result);
		$start = 0;

		// Will read all the headers
		foreach ($h as $line) {
			$start += strlen($line) + 1;

			if (strpos($line, 'HTTP/1.') !== false) {
				$response['code'] = substr($line, 9, 3);
				continue;
			}
			if (strpos($line, ':') !== false) {
				$k                  = substr($line, 0, strpos($line, ':'));
				$v                  = trim(substr($line, strpos($line, ':') + 1));
				// Content-Type can have an embedded charset request.
				if($k == 'Content-Type' && strpos($v, 'charset=') !== false){
					$response['headers']['Charset'] = substr($v, strpos($v, 'charset=') + 8);
					$v = substr($v, 0, strpos($v, 'charset=') - 2);
				}
				$response['headers'][$k] = $v;
				continue;
			}
			if(trim($line) == ''){
				break;
			}
		}

		if(isset($response['headers']['Content-Length'])){
			$response['body'] = substr($result, $start, $response['headers']['Content-Length']);
		}
		else{
			$response['body'] = substr($result, $start);
		}


		return $response;
	}
} 