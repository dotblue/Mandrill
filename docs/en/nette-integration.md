[Nette Framework](http://nette.org) integration
===============================================

Registration
------------
The library comes along with a [DI Extension](../../DotBlue/Mandrill/NetteBridge/DI/MandrillExtension)
thus the only two things you need to do is to [register it into your DIC](http://doc.nette.org/cs/2.1/di-extensions)
and pass your API key in configuration:
```yaml
extensions:
    mandrill: DotBlue\Mandrill\NetteBridge\DI\MandrillExtension

mandrill:
    apiKey: "your API key"
```
Then `DotBlue\Mandrill\Mailer` is accessible eg. via autowiring.

Autowiring
----------
In case you need to register multiple extensions you can turn off autowiring for one of them:
```yaml
extensions:
    mandrill: DotBlue\Mandrill\NetteBridge\DI\MandrillExtension
    secondMandrill: DotBlue\Mandrill\NetteBridge\DI\MandrillExtension

    mandrill:
        apiKey: "your API key"

    secondMandrill:
        apiKey: "different API key"
        autowire: false
```

Nette\Mail support
------------------
Register the library extension, require a `Nette\Mail\IMailer` instance and pass a `Nette\Mail\Message` object,
it will be sent through Mandrill, attachments included, only embedded files won't. Yep, you don't need to change
anything to change method of sending.

If you need to setup some Mandrill parameters to the message:
```php
$netteMailer->send($message, function(\DotBlue\Mandrill\Message $message) {
	// do whatever you need
});
```
