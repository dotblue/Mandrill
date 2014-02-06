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
use DotBlue\Mandrill\Utils\MimeTypeDetector;
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
		$attachment = new Attachment('of a bad name', 'with some content', 'mime/type');

		Assert::same('of a bad name', $attachment->getName());
		Assert::same('mime/type', $attachment->getType());
		Assert::same('with some content', $attachment->getContent());
	}


	public function testFromFileNameDetection()
	{
		$attachment = Attachment::fromFile(__DIR__ . '/files/attachment.txt', NULL, 'text/plain');

		Assert::same('attachment.txt', $attachment->getName());
	}


	public function testFromFile()
	{
		$attachment = Attachment::fromFile(__DIR__ . '/files/attachment.txt', 'name.pdf', 'text/plain');

		$content = "42";
		Assert::same('name.pdf', $attachment->getName());
		Assert::same('text/plain', $attachment->getType());
		Assert::same($content, $attachment->getContent());
	}


	/**
	 * Yep, ain't test of detector itself
	 */
	public function testAutomaticMimeTypeDetection()
	{
		$attachment = Attachment::fromFile(__DIR__ . '/files/attachment.txt');
		$expected = MimeTypeDetector::fromString(file_get_contents(__DIR__ . '/files/attachment.txt'));
		Assert::same($expected, $attachment->getType());

		$attachment = new Attachment('attachment.txt', $attachment->getContent());
		Assert::same($expected, $attachment->getType());
	}


	public function testFromFileThrowsExceptionWhenFileDoesNotExist()
	{
		Assert::throws(function() {
			Attachment::fromFile(__DIR__ . '/file-which-does-not-exist.truly', 'does/matter');
		}, 'RuntimeException');
	}
}

(new AttachmentTest())->run();
