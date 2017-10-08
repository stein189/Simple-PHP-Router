# Simple PHP Router

[![Latest Stable Version](https://poser.pugx.org/szenis/routing/v/stable)](https://packagist.org/packages/szenis/routing)
[![Total Downloads](https://poser.pugx.org/szenis/routing/downloads)](https://packagist.org/packages/szenis/routing)
[![Build Status](https://travis-ci.org/stein189/Simple-PHP-Router.svg?branch=master)](https://travis-ci.org/stein189/Simple-PHP-Router)

**Updating from version 0.x or 1.x will break your code! read the documentation before upgrading!**

<h2>Getting started</h2>

<b>Step 1 - .htaccess file</b>
create an .htaccess file in the root of your project and fill it with the code below:
```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes...
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

<b>Step 2 - require szenis/routing</b><br/>
In your terminal execute: ``composer require szenis/routing``

<b>Step 3 - create index.php</b><br/>
Create the file index.php in the root of your project

<b>Step 4 - require autoload.php and use the Router</b><br/>

The following snippet shows how the router can be used.

```php
<?php

require './vendor/autoload.php';

use Szenis\Routing\Router;

$router = new Router();
$router->get('/{n:date}-{w:item}', function($date, $item) {
    return 'hello world';
});

$response = $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

switch ($response['code']) {
    case \Szenis\Routing\Route::STATUS_NOT_FOUND:
        // render your 404 page here...
        break;
    
    case \Szenis\Routing\Route::STATUS_FOUND:
        // the router only resolves the route, here is an example how to execute the route.
        if ($response['handler'] instanceof \Closure) {
            echo call_user_func_array($response['handler'], $response['arguments']);
        }
        
        break;
}


```
to your index.php

<b>Optional</b><br/>
For debuging purpose add the following to your index.php
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

<h2>Usage</h2>

```php

/**
 * initialize the router class
 */
$router = new Router();

/**
 * Route matches the url '/' for the GET method
 */
$router->add('/', 'GET', function() {
    // the closure will be executed when route is triggerd and will return 'hello world'
    return 'hello world!'; 
});

/**
 * It is posible to add one or multiple wildcards in one route
 */
$router->add('/user/{id}', 'GET', function($id) {
    return $id;
});

/**
 * It is also posible to allow mulitple methods for one route (methods should be separated with a '|')
 */
$router->add('/user/{id}/edit', 'GET|POST', function($id) {
    return 'user id: '.$id;
});

/**
 * Or when u are using controllers in a namespace you could give the full path to a controller (controller::action)
 *
 * Since version 2.0 executing the handler is up to you.
 */
$router->add('/user/{id}/delete', 'DELETE', 'App\Controllers\UserController::delete');

/**
 * Since version 1.1 there are shortcut methods for get, post, put, patch, delete and any.
 * You can use them as follow
 */
$router->get('/example/get', function() {});        // Will match GET requests
$router->post('/example/post', function() {});      // Will match POST requests
$router->put('/example/put', function() {});        // Will match PUT requests
$router->patch('/example/patch', function() {});    // Will match PATCH requests
$router->delete('/example/delete', function() {});  // Will match DELETE requests
$router->any('/example/any', function() {});        // Will match GET, POST, PUT, PATCH, DELETE requests

/**
 * resolve the route and receive the response
 *
 * The response is an array with the following keys
 * - code (contains 200 if the route is found, else 404)
 * - handler (contains the handler, often a \Closure or full path to your controller action)
 * - arguments (contains the route arguments if any)
 */
$response = $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

/**
 * To execute your route you have to do the following
 */

switch ($response['code']) {
    case \Szenis\Routing\Route::STATUS_NOT_FOUND:
        // render your 404 page here...
        break;
    
    case \Szenis\Routing\Route::STATUS_FOUND:
        // the router only resolves the route, here is an example how to execute the route.
        if ($response['handler'] instanceof \Closure) {
            echo call_user_func_array($response['handler'], $response['arguments']);
        }
        
        // if your handler is a full path to your function you could execute it with this:
        $className = substr($response['handler'], 0, strpos($response['handler'], '::'));
        $functionName = substr($response['handler'], strpos($response['handler'], '::') + 2);

        echo call_user_func_array(array((new $className), $functionName), $response['arguments']);

        break;
}

```

<h2>Wildcard options</h2>
The following options exist
<ul>
    <li>a: (alfabetic chars only)</li>
    <li>n: (numbers only)</li>
    <li>an: (alfanumeric chars only)</li>
    <li>w: (alfanumeric, dash and underscore only)</li>
    <li>?: (optional parameters) - must be last part of the url</li>
    <li>*: (lazy loading) - must be last part of the url</li>
</ul>

<b>How to use</b>
</p>

```php
// The id must be a number
$router->add('/user/{n:id}', 'GET', 'App\Controllers\UserController::show');

// The id must contain either alfabetic chars or numbers
$router->add('/user/{an:id}', 'GET', 'App\Controllers\UserController::show');

// Now we want everything behind docs/ in the page variable
// For example when we go to the url /docs/user/edit we will receive user/edit in the page variable
$router->add('/docs/{*:page}', 'GET', function($page) {
    // do something with $page
});

// Optional parameter example
$router->add('/hello/{a:name}/{?:lastname}', 'GET', function($name, $lastname = null) {
    // check if lastname is provided
    // if ($lastname) {...}
})
```

<h2>Upgrading from v0.x/v1.x to v2.x</h2>

- Namespace has changed from ``\Szenis`` to ``\Szenis\Routing``
- ``$router->setNamespace()`` has been removed!
- RouteResolver shouldnt be used to resolve routes, use the following instead ``$router->resolve($uri, $method);``
- Router does not execute the route anymore. From now one it is your responsibility to execute the handler. At the bottom of the section 'Usage' there is an example how to execute the handler.

<h2>Changelog</h2>

<b>v2.0.0</b>
- Removed 'default' namespace
- Changed namespace from \Szenis to \Szenis\Routing
- Router does not execute the callable itself, this gives you more control over parameter injection
- RouteResolver is callable trough the router
- Bugfix: it is now possible to have more then one parameter in one segment (/{parameter1}-{parameter2}/)

<b>v1.1.0</b>
- Shortcut functions for get, post, put, patch, delete and any

<b>v1.0.0</b>
- Updated readme
- Posible to add default namespace

<b>v0.9.0</b>
- 100% test coverage
- minimum php version reduced from 5.4 to 5.3

<b>v0.8.0</b>
- Added optional parameter
- Added lazy url loading
- Improved code

<b>v0.7.0</b>
- Improved code

<b>v0.6.0</b>
- Changed usages of router check out the ``Usages`` section for more detail
- Posible to add closure to a route
- Routes with query string will be found now (bugfix: v0.6.1)

<b>v0.5.0</b>
- Removed unnecessary code

<b>v0.4.0</b>
- Added interfaces and created an url factory

<b>v0.3.0</b>
- Its now posible to add options to url wildcards for more information see `wildcard options`

<b>v0.2.0</b>
- RouteResolver uses regex to match routes quicker
