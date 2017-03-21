# Simple PHP Router

[![Latest Stable Version](https://poser.pugx.org/szenis/routing/v/stable)](https://packagist.org/packages/szenis/routing)
[![Total Downloads](https://poser.pugx.org/szenis/routing/downloads)](https://packagist.org/packages/szenis/routing)
[![Build Status](https://travis-ci.org/stein189/Simple-PHP-Router.svg?branch=master)](https://travis-ci.org/stein189/Simple-PHP-Router)

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

<b>Step 4 - require autoload.php</b><br/>
Require vendor/autoload.php in your index.php

<b>Step 5 - use Router</b><br/>
Add 
```php
use Szenis\Router;
use Szenis\RouteResolver;
```
to your index.php

<b>Optional</b><br/>
For debuging purpose add the following to your index.php
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

<h2>Usage</h2>
For the sake of simplicity consider this code to be inside index.php
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
 */
$router->add('/user/{id}/delete', 'DELETE', 'App\Controllers\UserController::delete');

/**
 * When all the controller are in the same namespace you could set the default namespace like so
 */
$router->setNamespace('App\\Controllers\\');

/**
 * The route now uses the default namespace + the given namespace
 */
$router->add('/user/{id}/update', 'PUT', 'UserController::update');


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
 * After all the routes are created the resolver must be initialized
 */
$resolver = new RouteResolver($router);

/**
 * resolve the route and receive the response
 */
$response = $resolver->resolve([
	'uri' => $_SERVER['REQUEST_URI'],
	'method' => $_SERVER['REQUEST_METHOD'],
]);
```

<b>When a route is not found an RouteNotFoundException will be thrown</b>
<p>Its posible to catch this exception and display a good looking 404 page, the try catch block will look something like this</p>

```php
try {
    // You have to resolve the route inside the try block
    $resolver->resolve([
        'uri' => $_SERVER['REQUEST_URI'],
        'method' => $_SERVER['REQUEST_METHOD'],
    ]);
} catch (Szenis\Exceptions\RouteNotFoundException $e) {
    // route not found, add a nice 404 page here if you like 
    die($e->getMessage());
} catch (Szenis\Exceptions\InvalidArgumentException $e) {
    // when an arguments of a route is missing an InvalidArgumentException will be thrown 
    // it is not necessary to catch this exception as this exception should never occur in production
    die($e->getMessage());
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

<h2>Changelog</h2>

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

<hr/>

Click <a href="https://github.com/stein189/SimpleRoutingExample/tree/master">here</a> to see the working example.
