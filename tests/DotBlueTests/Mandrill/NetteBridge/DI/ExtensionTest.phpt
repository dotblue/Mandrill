<?php

/**
 * Test: DotBlue\Mandrill\NetteBridge\DI\MandrillExtension.
 *
 * @testCase DotBlueTests\Mandrill\NetteBridge\DI\ExtensionTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\NetteBridge\DI\MandrillExtension;
use Tester\Assert;


require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class ExtensionTest extends \Tester\TestCase
{
	public function testAddsServices()
	{
		$container = $this->createContainer();

		$container->getByType('DotBlue\Mandrill\Exporters\IMessageExporter');
		$container->getByType('DotBlue\Mandrill\IApiCaller');
		$container->getByType('DotBlue\Mandrill\Mailer');
		$container->getByType('DotBlue\Mandrill\NetteBridge\Mail\MessageConverter');
		$container->getByType('Nette\Mail\IMailer');
	}


    public function testRejectsEmptyKey()
    {
        $configurator = new \Nette\Configurator();
        $configurator->addParameters([
            'container' => [
                'class' => 'EmptyKeyContainer',
            ]
        ]);
        $configurator->setTempDirectory(TEMP_DIR);
        $configurator->defaultExtensions = [];
        $configurator->onCompile[] = function($configurator, $compiler) {
            $compiler->addExtension('mandrill', new MandrillExtension());
        };

        Assert::throws(function() use ($configurator) {
            $configurator->createContainer();
        }, 'Nette\InvalidArgumentException', 'Mandrill api key has not been set.');
    }


	public function testPassesKey()
	{
		$container = $this->createContainer();

		Assert::same('1234', $container->getByType('DotBlue\Mandrill\Mandrill')->getApiKey());
	}


	/**
	 * @return \SystemContainer
	 */
	private function createContainer()
	{
		$configurator = new \Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/files/config.neon');
		$configurator->defaultExtensions = [];
		$configurator->onCompile[] = function($configurator, $compiler) {
			$compiler->addExtension('mandrill', new MandrillExtension());
		};
		return $configurator->createContainer();
	}
}

(new ExtensionTest())->run();
