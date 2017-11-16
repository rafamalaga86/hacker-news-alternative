<?php

// Hacker news API config
$app['baseUri'] = 'https://hacker-news.firebaseio.com/v0';

// Ammount of stories (roots) fetched.
$app['rootsToFech'] = 10;

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
