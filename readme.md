#Event Based Request Dispatch

This module provides a way to deligate handling of requests to multiple listeners.

This has the effect of making your controllers thin and your code DRY and testable

A sample installation utilizing the module can be found here

[Sample Application](https://github.com/prodeveloper/sample_dispatcher)

##Installation

You can use composer to install

    composer require chencha/dispatcher

##Basic Usage

An assumption is made that your application utilizes PSR-4 Autoloading

##Directory structure

    .
    └── Sample
        ├── Commands
        │   └── SaveUser.php
        ├── Handlers
        │   ├── CommandHandler.php
        │   └── RequestHandler.php
        ├── Models
        │   └── Transactions.php
        └── Requests
            └── RetreiveUser.php

##The handlers

The handlers are classes that register all classes that will respond to a request or a command. They must extend

    Chencha\Dispatcher\EventSubscriber;

The handler class must then provide the location of the commands of the commands or requests it will handle to the parent constructor. 

Eg

    function __construct()
    {
        $path = "Sample.Commands";
        parent::__construct($path);
    }

The class has three methods of which only one is required. This are:

* beforeListeners
* duringListeners (Required)
* afterListeners
* queuedListeners (Called outsite the request cycle. See http://laravel.com/docs/4.2/queues)

each of this methods must return an array if defined.

Eg


    /**
     * @return array
     */
    function duringListeners()
    {
        return [
            Transactions::class
        ];
    }
    
##Subscribe the handlers

For the framework to be aware of your registered classes. You need to register your handlers.

This is bootstrap work and should be done in either app/start/global.php file or whereever you normally register listeners.

A sample declaration

    Event::subscribe(new \Sample\Handlers\CommandHandler());

##Usage

In your controllers use the trait

    use \Chencha\Dispatcher\RequestDispatcher;

Now to run the request

    function getSaveuser()
    {
        $command = new \Sample\Commands\SaveUser(rand(1, 5));
        $this->runRequest($command);
        return "Success";
    }

In this way all classes registered in the command handler will be called.

Since objects are usually passed by reference. Changes are made directly to the command object.

This is useful in a request where a response is needed 

Eg

    function getUser()
    {
        $request = new \Sample\Requests\RetreiveUser(rand(1, 5));
        $this->runRequest($request);
        return $request->response;
    }

In this way you can populate say the *response* public property with all needed values for the response.

##Gotchas

###Nesting Level

If you register a lot of classes you are likely to run into this error

    PHP Error: Maximum function nesting level of '100' reached, aborting
    
This is because of how the laravel event dispatcher works. 

To sort this problem simply add the line

    xdebug.max_nesting_level = 200
   
to */etc/php5/fpm/conf.d/20-xdebug.ini*

The higher the max nesting level the more classes you can register.

###Serialization of closure

Your objects should be as simple as possible, preferably native php types.

At all costs avoid *closures* as they can not be serialized.


