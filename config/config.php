<?php

// Hacker news API config
$app['baseUri'] = 'https://hacker-news.firebaseio.com/v0';

// Ammount of stories (roots) fetched.
$app['rootsToFech'] = 10;

$app['debug'] = true;
$app['twig.options'] = ['cache' => false];
$app['twig.path']    = [__DIR__.'/../templates'];
$app['twig.options'] = ['cache' => __DIR__.'/../var/cache/twig'];
