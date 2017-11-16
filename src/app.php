<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
// use Silex\Provider\HttpFragmentServiceProvider;
use GuzzleHttp\Client as GuzzleClient;
use HackerNewsGTD\ItemMapper;
use HackerNewsGTD\HackerNewsClient;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());

// $app->register(new HttpFragmentServiceProvider());


// Defining services for dependency injection
$app['client.hackerNews'] = function ($app) {
    return new HackerNewsClient(new GuzzleClient(), $app['baseUri']);
};

$app['mapper.item'] = function ($app) {
    return new ItemMapper($app['client.hackerNews']);
};

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    return $twig;
});

return $app;
