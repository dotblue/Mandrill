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
		\Tester\Environment::$checkAssertions = FALSE;
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


	public function testAutowireIsConfigurable()
	{
		$container = $this->createContainer(TRUE);

		$container->getByType('DotBlue\Mandrill\Exporters\IMessageExporter');
		$container->getByType('DotBlue\Mandrill\IApiCaller');
		$container->getByType('DotBlue\Mandrill\Mailer');
		$container->getByType('DotBlue\Mandrill\NetteBridge\Mail\MessageConverter');
		$container->getByType('Nette\Mail\IMailer');

		Assert::same('1234', $container->getService('mandrill.mandrill')->getApiKey());
		Assert::same('5678', $container->getService('mandrill2.mandrill')->getApiKey());
	}


	/**
	 * @return \SystemContainer
	 */
	private function createContainer($registerSecondExtension = FALSE)
	{
		$configurator = new \Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/files/config.neon');
		if ($registerSecondExtension) {
			$configurator->addConfig(__DIR__ . '/files/config2.neon');
		}
		$configurator->defaultExtensions = [];
		$configurator->onCompile[] = function($configurator, $compiler) use ($registerSecondExtension) {
			$compiler->addExtension('mandrill', new MandrillExtension());
			if ($registerSecondExtension) {
				$compiler->addExtension('mandrill2', new MandrillExtension());
			}
		};
		return $configurator->createContainer();
	}
}

(new ExtensionTest())->run();
