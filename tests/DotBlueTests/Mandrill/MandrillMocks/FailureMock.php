<?php
namespace DotBlueTests\Mandrill\MandrillMocks;

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class FailureMock implements \DotBlue\Mandrill\IApiCaller
{
	public function call($url, array $parameters)
	{
		return [
			[
				'email' => 'email@example.com',
				'status' => 'sent',
				'reject_reason' => NULL,
				'_id' => NULL,
			],
			[
				'email' => 'another@example.com',
				'status' => 'rejected',
				'reject_reason' => 'Bad mood',
				'_id' => NULL,
			],
			[
				'email' => 'wow@example.com',
				'status' => 'invalid',
				'reject_reason' => 'no sender',
				'_id' => NULL,
			],
		];
	}
}
