MessageBoy
==========
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3e8fad1e-d0ef-41b4-98b9-8ee977390b34/mini.png)](https://insight.sensiolabs.com/projects/3e8fad1e-d0ef-41b4-98b9-8ee977390b34) [![Build Status](https://travis-ci.org/ecoreng/messageboy.svg)](https://travis-ci.org/ecoreng/messageboy)

Unified API (Facade) to send a Message (Email, HttpPost, DB, etc).


This package provides a unified API to send a message/notification, etc. by abstracting the concept of a message to contain a body, subject, remitents and potential destinataries.


The library requires you to instantiate the ``MessageDispatcher``, register Adapters to handle your message by Group, Type or Globally.

The usage is as follows:

```php

// ==== Boilerplate code ====

use ecoreng\MessageBoy\Concrete\MessageDispatcher;
use ecoreng\MessageBoy\Concrete\SimpleMessage;
use ecoreng\MessageBoy\Concrete\Adapters\NativeMailAdapter as Mail;
use ecoreng\MessageBoy\Concrete\Adapters\ClosureAdapter;

$md = new MessageDispatcher;

// (Register example adapters)
$md
	->registerAdapter(new Mail, 'mail')
	->registerAdapter(new ClosureAdapter(function($message){
		// do something with $message;
	}));

// ==== End Boilerplate code ====


$message = (new SimpleMessage)
	->setBodyString('Long Message Body aaaa eeee iiii ooo uuu')
	->setSubject('Short Subject')
	->setToArray(['test@example.com'])
	->setFrom('me@me.com');

// Dispatch the message globally to all adapters
$md->dispatch($message);

// Dispatch the message to adapters registered as 'mail'
$md->dispatch($message, 'mail');

```

This previous example demonstrates the abstraction of the API, but it doesn't show a clear advantage vs sending the mail manually, but notice that we can use adapters to do handle or interpret the message however we want, for instance sending the message to a Database, through HTTP Post to another API, to a socket connection, a File, another API using any SDK, etc.

```php

// ==== Boilerplate code ====

// ...

// (Register example adapters)
$md
	->registerAdapter(new MailAdapter, 'regular')
	->registerAdapter(new SMSAdapter, 'priority')
	->registerAdapter(new VoicePhoneAdapter, 'priority')
	->registerAdapter(new DataBaseAdapter, 'backup')
	->registerAdapter(new TwitterAdapter, 'social')
	->registerAdapter(new FacebookPostAdapter, 'social');

// ==== End Boilerplate code ====


$message = (new Message);
	->setBody(new LongStreamableMessage());
	->setSubject('EMERGENCY!!');
	->setTo(new ContactIterator);
	->setFrom('Your Emergency System');

// Dispatch the message globally to all adapters ?? 
// (maybe not)
// $md->dispatch($message);

// Dispatch the message to adapters registered as 'priority'
$md->dispatch($message, 'priority');
```

Notice that we are using now the class ``Message`` instead of ``SimpleMessage``, which allows us to pass a ``Stream`` object as the body of th message, and an ``Iterator`` for the destinataries of that message, so it's really flexible if it's needed.

The adapters registered in this last example are virtual as I am not providing them, but it's not rocket science, just create a class the implements ``ecoreng\MessageBoy\Adapter``, register it in the dispatcher and you will receive an implementation of ``ecoreng\MessageBoy\Message`` ready to be used.

Alternative:

```
https://github.com/namshi/notificator
```
