DotBlue\Mandrill
================
This is an OO library wrapping around Mandrill API for sending messages. Enjoy!

Requirements
------------
PHP 5.4+ (we love short array syntax).

Installation
------------
The easiest way is to use [Composer](http://getcomposer.org/):
```sh
$ composer require dotblue/mandrill@~1.0
```
Of course you can always clone this repository and commit it into your project manually.

Usage
-----
```php
$mandrill = new DotBlue\Mandrill\Mandrill($apiKey);
$mailer = new Dotblue\Mandrill\Mailer(new DotBlue\Mandrill\Exporters\MessageExporter(), $mandrill);

$message = new DotBlue\Mandrill\Message();
$message->setFrom('maesters@citadel.sif');
$message->subject = 'Winter is coming!';

$message->addTo('jeoffrey@baratheon.sif');
$message->addBcc('varys@spider.sif');
$message->addBcc('petyr@baelish.sif');

$message->html = '<html><body>Winter is coming!</body></html>';
$message->text = 'Winter is coming!';

$mailer->send($message);
```
Similary you can send a template by using class `DotBlue\Mandrill\TemplateMessage` in combination
with `DotBlue\Mandrill\Mailer::sendTemplate` method.
