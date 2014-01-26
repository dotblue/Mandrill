<?php
namespace DotBlueTests\Mandrill\MandrillMocks;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class UrlMock implements \DotBlue\Mandrill\IApiCaller
{
	/** @var string */
	public $lastUrl;


	public function call($url, array $parameters)
	{
		$this->lastUrl = $url;
		return [];
	}
}
