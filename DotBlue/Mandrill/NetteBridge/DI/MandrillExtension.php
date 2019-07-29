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
		$config = $this->validateConfig($this->defaults);
		$autowire = $config['autowire'];

        if (empty($config['apiKey'])) {
            throw new \Nette\InvalidArgumentException("Mandrill api key has not been set.");
        }

		$container->addDefinition($this->prefix('mandrill'))
			->setFactory('DotBlue\Mandrill\Mandrill', [
				$config['apiKey'],
			])
			->setAutowired($autowire);

		$container->addDefinition($this->prefix('messageExporter'))
			->setFactory('DotBlue\Mandrill\Exporters\MessageExporter')
			->setAutowired($autowire);

		$container->addDefinition($this->prefix('mailer'))
			->setFactory('DotBlue\Mandrill\Mailer', [
				'exporter' => $this->prefix('@messageExporter'),
				'api' => $this->prefix('@mandrill'),
			])
			->setAutowired($autowire);

		$container->addDefinition($this->prefix('messageConverter'))
			->setFactory('DotBlue\Mandrill\NetteBridge\Mail\MessageConverter')
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
			->setFactory('DotBlue\Mandrill\NetteBridge\Mail\MandrillMailer', [
				'converter' => $this->prefix('@messageConverter'),
				'mailer' => $this->prefix('@mailer')
			]);
	}
}
