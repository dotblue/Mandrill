<?php

/**
 * Test: DotBlue\Mandrill\Attachment.
 *
 * @testCase DotBlueTests\Mandrill\AttachmentTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\Attachment;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class AttachmentTest extends \Tester\TestCase
{
	public function testConstructor()
	{
		$attachment = new Attachment('of a bad name', 'mime/type', 'with some content');

		Assert::same('of a bad name', $attachment->getName());
		Assert::same('mime/type', $attachment->getType());
		Assert::same('with some content', $attachment->getContent());
	}


	public function testFromFileNameDetection()
	{
		$attachment = Attachment::fromFile(__DIR__ . '/files/attachment.txt', 'text/plain');

		Assert::same('attachment.txt', $attachment->getName());
	}


	public function testFromFile()
	{
		$attachment = Attachment::fromFile(__DIR__ . '/files/attachment.txt', 'text/plain', 'name.pdf');

		$content = base64_encode("42");
		Assert::same('name.pdf', $attachment->getName());
		Assert::same('text/plain', $attachment->getType());
		Assert::same($content, $attachment->getContent());
	}


	public function testFromFileThrowsExceptionWhenFileDoesNotExist()
	{
		Assert::throws(function() {
			Attachment::fromFile(__DIR__ . '/file-which-does-not-exist.truly', 'does/matter');
		}, 'RuntimeException');
	}
}

(new AttachmentTest())->run();
