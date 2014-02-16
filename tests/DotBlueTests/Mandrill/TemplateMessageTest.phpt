<?php

/**
 * Test: DotBlue\Mandrill\TemplateMessage.
 *
 * @testCase DotBlueTests\Mandrill\TemplateMessageTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\TemplateMessage;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class TemplateMessageTest extends \Tester\TestCase
{
	public function testConstructor()
	{
		$message = new TemplateMessage('templateName');

		Assert::same('templateName', $message->getTemplateName());
	}


    public function testTemplateNameSet()
    {
        $message = new TemplateMessage('templateName');
        $message->setTemplateName('differentTemplateName');
        Assert::same('differentTemplateName', $message->getTemplateName());
    }


	public function testContent()
	{
		$message = new TemplateMessage('dummy');
		$message->setEditableRegion('region', 'content');
		$message->setEditableRegion('secondRegion', 'yabadabadooo');

		Assert::same([
			'region' => 'content',
			'secondRegion' => 'yabadabadooo',
		], $message->getEditableRegions());
	}
}

(new TemplateMessageTest())->run();
