<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class Mandrill implements IApiCaller
{
	/** @var string */
	private $apiKey;

	/** @var string */
	public $apiEndpoint = 'https://mandrillapp.com/api/1.0';

	/** @var bool */
	public $sslVerifyPeer = TRUE;

	/** @var bool */
	public $sslVerifyHost = TRUE;


	/**
	 * @param string $apiKey
	 */
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}


	/**
	 * @param string $url
	 * @param array $parameters
	 * @return array|mixed
	 * @throws MandrillException
	 */
	public function call($url, array $parameters)
	{
		$parameters = $this->exportParameters($parameters);

		$request = curl_init($this->getRequestUrl($url));
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, $this->sslVerifyHost);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
		curl_setopt($request, CURLOPT_USERAGENT, 'DotBlue-Mandrill/1.0.0');
		curl_setopt($request, CURLOPT_POST, TRUE);
		curl_setopt($request, CURLOPT_HEADER, FALSE);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($request, CURLOPT_TIMEOUT, 600);
		curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($request, CURLOPT_POSTFIELDS, $parameters);

		$response = curl_exec($request);
		$responseInfo = curl_getinfo($request);
		if (curl_error($request)) {
			throw new MandrillException("API call to '$url' failed: " . curl_error($request));
		}
		curl_close($request);
		return $this->processResponse($responseInfo, $response);
	}


	/**
	 * @param array $responseInfo
	 * @param string $response
	 * @return array|mixed
	 * @throws MandrillException
	 */
	private function processResponse(array $responseInfo, $response)
	{
		$response = json_decode($response, TRUE);
		if ($response === NULL) {
			throw new MandrillException("Unable to decode Mandrill response.");
		}
		if ($responseInfo['http_code'] != 200) {
			$this->castError($response);
		}
		return $response;
	}


	/**
	 * @param string $response
	 * @throws MandrillException
	 */
	private function castError($response)
	{
		if ($response['status'] !== 'error' || !isset($response['name'])) {
			throw new MandrillException("Unexpected error.");
		}

		throw new MandrillException($response['name'] . ': ' . $response['message'], $response['code']);
	}


	/**
	 * @param string $url
	 * @return string
	 */
	private function getRequestUrl($url)
	{
		return "$this->apiEndpoint/$url.json";
	}


	/**
	 * @param array $parameters
	 * @return string
	 */
	private function exportParameters(array $parameters)
	{
		$parameters['key'] = $this->apiKey;
		return json_encode($parameters);
	}


	/**
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->apiKey;
	}
}
