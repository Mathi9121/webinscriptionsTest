<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$apcLoader = new ApcClassLoader('sf2', $loader);
$loader->unregister();
$apcLoader->register(true);
*/

require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('form', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();

$req_ext = Request::createFromGlobals();
$method = $req_ext->getMethod();

$donnees = array();

$urlWP = $req_ext->getUri();

if($method == 'GET'){
    $donnees = $req_ext->query->all();
}
else{
    $donnees = $req_ext->request->all();
}

$request = Request::create('/', $method, $donnees);
$request->overrideGlobals();

$request->query->set("urlwp", $urlWP);

//$response = new Response('ok','200')
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

