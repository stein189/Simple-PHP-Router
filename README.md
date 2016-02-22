# SimpleRouting

Note: This package is still in development, to use it add "minimum-stability": "dev" to your composer.json.

<h2>Getting started</h2>

<b>Step 1 - .htaccess file</b>
create an .htaccess file in the root of your project and fill it with the code below:
````
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
````

<b>Step 2 - require szenis/routing</b><br/>
In your terminal execute: ``composer require szenis/routing 0.*``

<b>Step 3 - create index.php</b><br/>
Create the file index.php in the root of your project

<b>Step 4 - require autoload.php</b><br/>
Require vendor/autoload.php in your index.php

<b>Step 5 - use Router</b><br/>
Add 
```php
use Szenis\Router;
use Szenis\RouteResolver;
````
to your index.php

<b>Step 6 *optional</b><br/>
For debuging purpose add the following to your index.php
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
````

<h2>Usage</h2>
For the sake of simplicity consider this code to be inside index.php
```php
/**
 * initialize the router class
 */
$router = new Router();

/**
 * Add a route to the homepage
 * The first argument is the route that we want to look for
 * The second argument are the accepted methods, methods have to be seperated by a |
 * The third and last argument is the full path to the action that has to be executed,
 * this could also be an closure
 */
$router->add('/', 'GET|PUT', 'App\Controllers\PageController::index');

/**
 * It is posible to add one or multiple wildcards in one route
 */
$router->add('/user/{id}', 'GET', 'App\Controllers\UserController::show');

/**
 * Closure route example
 */
$router->add('/user/{id}/edit', 'GET|POST', function($id) {
    echo $id;

    return;
});

$resolver = new RouteResolver($router);

/**
 * resolve the route
 * the resolve function will search for an matching route
 * when a matching route is found the given function will be triggerd. 
 * lets asume we have triggerd the route: /user/10
 * the function `show` from the class `UserController` will be called
 * the wildcard which is the number `10` will be passed on to the `show` function
 */
$resolver->resolve([
	'uri' => $_SERVER['REQUEST_URI'],
	'method' => $_SERVER['REQUEST_METHOD'],
]);
````

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
````

<h2>Placeholder requirements</h2>
<p>It is posible to add requirements to a placeholder since version 0.3.0<br/>
The following requirements exist
<ul>
    <li>a: (alfabetic chars only)</li>
    <li>n: (numbers only)</li>
    <li>an: (alfanumeric chars only)</li>
    <li>w: (alfanumeric, dash and underscore only)</li>
</ul>

<b>How to use</b>
</p>

```php
// In this case the id may be a number
$router->add('/user/{n:id}', 'GET', 'App\Controllers\UserController::show');

// In this case the id may only contain alfabetic chars or numbers (or both)
$router->add('/user/{an:id}', 'GET', 'App\Controllers\UserController::show'); 
````

<h2>Changelog</h2>
<b>b0.7.0</b>
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
- Its now posible to add requirement to url placeholders for more information see `placeholder requirements`

<b>v0.2.0</b>
- RouteResolver uses regex to match routes quicker

<h2>Comming soon</h2>
- Optional parameters
- Lazy url loading


Click <a href="https://github.com/stein189/SimpleRoutingExample/tree/master">here</a> to see the working example.
