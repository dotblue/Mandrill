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
		'apiKey' => '',
		'autowire' => TRUE,
		'replaceNetteMailer' => TRUE,
	];


	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		$autowire = $config['autowire'];

        if (empty($config['apiKey'])) {
            throw new \Nette\InvalidArgumentException("Mandrill api key has not been set.");
        }

		$container->addDefinition($this->prefix('mandrill'))
			->setClass('DotBlue\Mandrill\Mandrill', [
				$config['apiKey'],
			])
			->setAutowired($autowire);

		$container->addDefinition($this->prefix('messageExporter'))
			->setClass('DotBlue\Mandrill\Exporters\MessageExporter')
			->setAutowired($autowire);

		$container->addDefinition($this->prefix('mailer'))
			->setClass('DotBlue\Mandrill\Mailer')
			->setAutowired($autowire);

		$container->addDefinition($this->prefix('messageConverter'))
			->setClass('DotBlue\Mandrill\NetteBridge\Mail\MessageConverter')
			->setAutowired($autowire);

		if ($config['replaceNetteMailer'] && $autowire) {
			$this->loadMailerReplacement();
		}
	}


	/**
	 * @param  bool
	 */
	private function loadMailerReplacement()
	{
		$container = $this->getContainerBuilder();

		$container->removeDefinition('nette.mailer');
		$container->addDefinition('nette.mailer')
			->setClass('DotBlue\Mandrill\NetteBridge\Mail\MandrillMailer', [
				'converter' => $this->prefix('@messageConverter'),
				'mailer' => $this->prefix('@mailer')
			]);
	}
}
