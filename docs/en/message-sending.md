Message sending
===============
Mailer initialization
---------------------
```php
$messageExporter = new DotBlue\Mandrill\Exporters\MessageExporter(); // if you want to use custom message classes, you might find it handy to pass your own exporter

$mandrill = new DotBlue\Mandrill\Mandrill('pass your API key');

$mailer = new DotBlue\Mandrill\Mailer($messageExporter, $mandrill);
```
And you're ready to go!

Normal message
--------------
```php
$message = new DotBlue\Mandrill\Message();
$message->setFrom('me@example.com', 'Name');
$message->addTo('friend1@example.com');
$message->addCc('friend2@example.com');
$message->addBcc('friend3@example.com');
$message->subject = 'Long report from a trip to the Mars'; // or ->setSubject(...)

$message->setMergeVar('friend1@example.com', 'name', 'Friend 1');
$message->setGlobalMergeVar('myName', 'My name');

$message->html = 'Hi *|name|*,<br>Info about an <b>awesome</b> trip to Mars.<br>Yours *|myName|*';
$message->text = 'Hi *|name|*, Info about an awesome trip to Mars. Yours *|myName|*';

$message->addAttachment(new DotBlue\Mandrill\Attachment('scribble.txt', 'Content', 'text/plain'));
$message->addAttachment(DotBlue\Mandrill\Attachment::fromFile('photo.png', 'image/png', $forceName = NULL));

$message->addImage(DotBlue\Mandrill\Attachment::fromFile('selfie.png', 'image/png'));

$mailer->send($message);
```

Using a template
----------------
Use `DotBlue\Mandrill\TemplateMessage($templateName)` and `DotBlue\Mandrill\Mailer::sendTemplate($message)`.
`TemplateMessage` contains only one method more than `Message does`:
```php
$templateMessage->setEditableRegion('region', 'content');
```

More extensive settings
-----------------------
See properties of [DotBlue\Mandrill\AbstractMessage](../../DotBlue/Mandrill/AbstractMessage), using them you can set all
message parameters which Mandrill supports.
````php
$message->important = TRUE;

// Value of merge var 'merge' is set to 'var' for this recipient
$message->addTo(..., ..., [
	'merge' => 'var',
]);

// Sets recipient metadata
$message->addTo()->metadata = [
	'id' => 42,
];
```

Be aware
--------
Default value of `inline_css` is `TRUE` and of `preserve_recipients` `FALSE`.
