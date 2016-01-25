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
Add ``use Szenis\Router;`` to your index.php

<b>Step 6 *optional</b><br/>
For debuging purpose add the following to your index.php
````
error_reporting(E_ALL);
ini_set('display_errors', 1);
````

<h2>Usage</h2>
For the sake of simplicity consider this code to be inside index.php
````
// initialize the router class
$router = new Router();

// add a route to the homepage
// the first argument is the route that we want to look for
// the second argument is an array, every key in this array is required.
$router->add('/', [
	'method' => ['GET', 'PUT'],                   // array of accepted methods, atleast 1.
	'class' => 'App\Controllers\PageController',  // full namespace to your class
	'function' => 'index',                        // name of the method inside the class
]);

// add a route with a wildcard
// it is posible to add multiple wildcards in one route
$router->add('/user/{id}', [
	'method' => ['GET'],
	'class' => 'App\Controllers\UserController',
	'function' => 'show',
]);

// resolve the route
// the resolve function will search for an matching route
// when a matching route is found the given function will be triggerd. 
// lets asume we have triggerd the route: /user/10
// the function `show` from the class `UserController` will be called
// the wildcard which is the number 10 will be passed on to the `show` function
$router->resolve([
	'uri' => $_SERVER['REQUEST_URI'],
	'method' => $_SERVER['REQUEST_METHOD'],
]);
````
