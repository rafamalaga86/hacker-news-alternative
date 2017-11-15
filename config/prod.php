<?php

// Hacker news API config
$app['scheme'] = 'https';
$app['host'] = 'hacker-news.firebaseio.com';
$app['apiPath'] = '/v0';

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
