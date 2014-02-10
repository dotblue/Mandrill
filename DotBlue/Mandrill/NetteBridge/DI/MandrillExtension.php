<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill\NetteBridge\DI;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MandrillExtension extends \Nette\DI\CompilerExtension
{
	/**
	 * @var array
	 */
	public $defaults = [
		'replaceNetteMailer' => TRUE,
	];


	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$container->addDefinition($this->prefix('mandrill'))
			->setClass('DotBlue\Mandrill\Mandrill', [
				$config['apiKey'],
			]);

		$container->addDefinition($this->prefix('messageExporter'))
			->setClass('DotBlue\Mandrill\Exporters\MessageExporter');

		$container->addDefinition($this->prefix('mailer'))
			->setClass('DotBlue\Mandrill\Mailer');

		$container->addDefinition($this->prefix('messageConverter'))
			->setClass('DotBlue\Mandrill\NetteBridge\Mail\MessageConverter');

		if ($config['replaceNetteMailer']) {
			$this->loadMailerReplacement();
		}
	}


	private function loadMailerReplacement()
	{
		$container = $this->getContainerBuilder();

		$container->removeDefinition('nette.mailer');
		$container->addDefinition('nette.mailer')
			->setClass('DotBlue\Mandrill\NetteBridge\Mail\MandrillMailer');
	}
}
