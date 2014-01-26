<?php
namespace DotBlueTests\Mandrill\MandrillMocks;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class ParameterMock implements \DotBlue\Mandrill\IApiCaller
{
	/** @var array */
	public $lastParameters;


	public function call($url, array $parameters)
	{
		$this->lastParameters = $parameters;
		return [];
	}
}
