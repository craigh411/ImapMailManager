# ImapMailManager

ImapMailManager provides convenient OOP classes for performing common tasks when for managing incoming email via imap in php.

**Note:** This package is still in development and is not currently stable.

## Features

- Provides convenient methods for searching imap mailboxes
- Automatic detection, decoding and encoding of email messages and attachments.
- Provides simple methods for downloading attachments and embedded images.
- Configurable via a config file, rather than having to hardcode username and password information.
- Converts php's native imap responses in to simple value objects for easy access.
 
## Getting Started

ImapMailManager provides two main service classes for performing tasks on the Mailbox (searching the mailbox for messages, folders etc) and on the Message itself (downloading attachments). Convenient factories have been provided to help with the creation of required objects.


### Config.

The simplest way to connect to your mailbox is by providing the settings via a config file.

```
return [
    'server' => 'imap.example.com',
    'username' => 'USERNAME',
    'password' => 'PASSWORD',
    'port' => 993,
    'ssl' => true,
    'validate_cert' => true,
];
```

By default `ImapMailManager` classes looks for your config folder in `imap_config/config.php`, you can change this by passing the config location directly in class constructors or factory methods.


### Listing Emails

This following example uses the provided factories to build a collection of messages from your inbox. See *Creating Imap using constructors and no config* below to see how you may use constructors to acheive the same results.

```php
// Creates the relevant imap classes to be passed to a service
$imap = ImapFactory::create('INBOX');
// Create a new service
$mailboxService = new ImapMailboxService($imap);

// Get all messages in the folder (this returns an array of message numbers)
$all = $mailboxService->getAllMessages();

// Create a collection of Message objects
$messages = ImapMessagesCollectionFactory::create($all);

```

Now you can simply loop through the messages to display a list of message subjects:

```php
if(count($messages)){
    foreach($messages as $message){
      echo $message->getSubject().'<br>';
    }
}
```

### Displaying a message

```php
$messageNum = 1;

// Creates the relevant imap classes to be passed to a service
$imap = ImapFactory::create('INBOX');
// Create a new service
$messageService = new ImapMessageService($imap);

// Get the current message
$message = ImapMessageFactory::create($messageNum, $imap);
// Download any embedded images, without this embedded images will not display.
$messageService->downloadEmbeddedImages('path/to/download/to');
```

Now it's just a matter of displaying the message, you should look at the docs for the `ImapMessage` class to see what methods are available. You should also look at `examples\showMessage.php` which provides an example of displaying a Message.

### Creating Imap using constructors and no config

While the factory methods provide a convenient way to create an the Imap class that is passed in to a service or factory, it hides a lot of what is going on. In order to better understand how ImapMailManager works, the following shows how you would directly construct the `MailboxService` object:

```php
// We don't have a config file, so lets create the mailbox directly
$mailbox = new Mailbox('imap.example.com', 'username', 'password', 'INBOX');
// Now lets connect to the mailbox
$imapConnection = new ImapConnection($mailbox);
// Now create an ImapHandler object, which is used to perform various tasks on the mailbox
$imap = new ImapHandler($imapConnection);
// Finally create the service
$mailboxService = new MailboxService($imap);
```

**Note:** Creating a Message object directly is quite involved, so the Factories should still be used to create Message objects and collections.

## Collections

`ImapMailMainager` returns collections as Collection objects, for the most part you can treat these exactly as you would a normal php array, so you can still use `count` and `foreach` on them as well as access them via their array index `MessageCollection[0]`



