<?php
namespace DotBlueTests\Mandrill\MandrillMocks;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class DoNothingMock implements \DotBlue\Mandrill\IApiCaller
{
	public function call($url, array $parameters)
	{
		return [];
	}
}
