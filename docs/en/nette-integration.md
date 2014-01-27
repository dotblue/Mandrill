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

Nette\Mail support
------------------
Currently there is none although I plan to add it soon.
