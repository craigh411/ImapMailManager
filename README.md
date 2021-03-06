# ImapMailManager

ImapMailManager provides convenient OOP classes for performing common tasks when for managing incoming email via imap in php.

**Note:** This package is now under a beta realease. A fully stable realease is expected in the coming weeks.

## Features

- Provides convenient methods for searching imap mailboxes
- Automatic detection, decoding and encoding of email messages and attachments.
- Encodes messages in UTF-8 for optimal display.
- Provides simple methods for downloading attachments and embedded images.
- Configurable via a config file, rather than having to hardcode username and password information.
- Converts php's native imap responses in to simple value objects for easy access.
 
## Getting Started

ImapMailManager provides two main service classes for performing tasks on the Mailbox (searching the mailbox for messages, folders etc) and on the Message itself (downloading attachments). Convenient factories have been provided to help with the creation of required objects.


### Config

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

#### Aliases

Aliases provide a convenient way to reference mailbox folders, for example if you use different `config` files to connect to multiple mailboxes you can provide folder aliases in the config itself. This means you can avoid hardcoding folder names, and using if statements to detect which mailbox you are connected to to get the correct folder name. To create an alias, you simply need to add an `aliases` array to your `config` file, these aliases can then be passed to methods that request a folder name, e.g. `moveToFolder('spam')`:

```
return [
    'server' => 'imap.example.com',
    'username' => 'USERNAME',
    'password' => 'PASSWORD',
    'port' => 993,
    'ssl' => true,
    'validate_cert' => true,
    'aliases' => [
      'trash' => 'INBOX.Trash',
      'spam' => 'INBOX.Spam'
    ]
];
```

It's a good idea to add the `trash` alias, as this is the alias that is automatically used by the `moveToTrash()` and `emptyTrash()` methods. Otherwise you will need to pass the folder name in as a parameter: e.g. `moveToTrash('INBOX.Trash')`

### Generating the config file


`ImapMailManager` provides a config generator called `createImapConfig.php` which can be run from composers `vendor/bin` folder, which will create
'imap_config/config.php' in the folder the script is run from.

Alternatively, you can simply copy and paste the code from config.php to your own config file.

By default `ImapMailManager` looks for your config file in `imap_config/config.php`, you can change this by passing the config location directly in to the relevant class constructors or factory methods. (see docs)


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
$messageNum = $_REQUEST['messageNum'];

// Creates the relevant imap classes to be passed to a service
$imap = ImapFactory::create('INBOX');

// Get the current message
$message = ImapMessageFactory::create($messageNum, $imap);
// Create a new service
$messageService = new ImapMessageService($message, $imap);

echo $message->getSubject().'<br />';
echo $message->getHtmlBody();
```

You should look at the docs for the `ImapMessage` class to see what methods are availble for retrieving message information. You can also look at `examples\showMessage.php` which provides a more complete example of displaying a message.

### Handling Attachments

`ImapMailManager` takes all the pain out of downloading attachments by extracting the attachment information, decoding and saving the attachment to the relevant folder.

#### Downloading all attachments

To download all attachments for a message you simply need the following:

```php
$messageNum = $_REQUEST['messageNum'];

// Creates the relevant imap classes to be passed to a service
$imap = ImapFactory::create('INBOX');

// Get the current message
$message = ImapMessageFactory::create($messageNum, $imap);
$message->downloadAttachments('path/to/download/to');
```

#### Downloading A Single Attachment

You will often want to download a single attachment, in order to do this `ImapMailManager` provides a few convenience methods: `downloadAttachmentByFilename()`, `downloadAttachmentByPart()` and `downloadAttachment()`. You can use the `getAttachments()` method on the returned message to retrieve a collection of `Attachment` objects which provide `getFilename()` and `getPart()` methods:

**Downloading attachment by filename**
```php
// Filename passed from the previous page, this can be retrieved using the `getFilename()` method on the Attachment object.
$filename = $_REQUEST['filename'];
// The folder for the message, this can be retreived using `getFolder()` on the Mailbox object (see `examples\exmple.php`)
$folder = $_REQUEST['folder'];

// Creates the relevant imap classes to be passed to a service
$imap = ImapFactory::create($folder);

// Get the current message
$message = ImapMessageFactory::create($messageNum, $imap);
// Create a new service
$messageService = new ImapMessageService($message, $imap);

$messageService->downloadAttachmentByFilename($filename, 'attachment/download/path');
```

### Downloading Embedded images

Some E-mails have images directly embedded in them, by default `ImapMailManager` will not download these images, so they will not be displayed. If you want to download and display embedded images you simply need to call the `downloadEmbeddedImages()` method, when displaying the message:


```php
$messageNum = $_REQUEST['messageNum'];
// The folder for the message, this can be retreived using `getFolder()` on the Mailbox object (see `examples\exmple.php`)
$folder = $_REQUEST['folder'];
// Creates the relevant imap classes to be passed to a service
$imap = ImapFactory::create(folder);

// Get the current message
$message = ImapMessageFactory::create($messageNum, $imap);
// Create a new service
$messageService = new ImapMessageService($message, $imap);
// Download any embedded images, without this embedded images will not display.
$messageService->downloadEmbeddedImages('path/to/download/to');

// Display Message with embedded images
$message->getHtmlBody()
```

In order to avoid overwriting images embedded images are saved to `your/path/folderName/messageNumber`.

**Note:** Embedded images are only downloaded if they do not exist in the download location, they will not be downloaded each time the message is opened.

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

## Running the Examples

The examples require you to provide your own config file in `examples/imap_config/config.php`, you can do this by using the generator found
in `vendor\bin` or simply creating one manually, you can find the basic config file in `config.php` in the root of this project.

You then need to change the settings in your `config.php` file to match your email account and you should then be able to run the examples.

## Documentation

There's a lot more that can be done with `ImapMailManager` than just these examples. You can find the full list of methods and descriptions in the `docs` folder, provided as HTML. I hope to have these online soon.


## Notes

#### A Note About Collections

`ImapMailMainager` returns collections as Collection objects. You can treat these exactly as you would a normal php array, so you can still use `count` and `foreach` on them as well as access them via their array index `MessageCollection[0]`

#### A Note on JSON Encoding

All value objects and collections are designed to be easily json encodable (and provide a `toJson()` method), however, you may find that some parts of an object are not included when json encoded, these are generally the raw responses from php's imap functions which could cause `json_encode()` to fail if they are not `UTF-8` encoded.

#### A Note on echoing

All value objects and collections have a `__toString()` method that will automatically display their json representations if echoed or printed to the screen, so you don't need to `var_dump()`. You should be aware that json is not the actual returned value when creating an object, but it's string represntation, so all methods can be used on the object as normal. 

#### A Note About Cloning

Classes that contain properties that are objects are deep cloned, so those object properties are thmeselves clones.




