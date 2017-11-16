<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HackerNewsGTD\ControllerHelper;

// Request::setTrustedProxies(array('127.0.0.1'));

// The home page
$app->get('/', function () use ($app) {
    $trees = $app['mapper.item']->fetchTopStories($app['rootsToFech']);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => 1,
    ]);
})
->bind('homepage');


// Delivers the main page with the top stories
$app->get('/news', function (Request $request) use ($app) {
    // Calculate the offset from the page sent by query string and fetch the tree
    $page   = $request->get('p');
    $offset = ControllerHelper::calculateOffset($page, $app['rootsToFech']);

    $trees = $app['mapper.item']->fetchTopStories($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});

// Delivers the main page with the newst stories
$app->get('/newest', function (Request $request) use ($app) {
    // Calculate the offset from the page sent by query string and fetch the tree
    $page   = $request->get('p');
    $offset = ControllerHelper::calculateOffset($page, $app['rootsToFech']);

    $trees = $app['mapper.item']->fetchNewestStories($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers ask main page
$app->get('/ask', function (Request $request) use ($app) {
    // Calculate the offset from the page sent by query string and fetch the tree
    $page   = $request->get('p');
    $offset = ControllerHelper::calculateOffset($page, $app['rootsToFech']);
    $trees = $app['mapper.item']->fetchAskStories($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers jobs main page
$app->get('/jobs', function (Request $request) use ($app) {
    // Calculate the offset from the page sent by query string and fetch the tree
    $page   = $request->get('p');
    $offset = ControllerHelper::calculateOffset($page, $app['rootsToFech']);
    $trees = $app['mapper.item']->fetchJobs($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers Shows main page
$app->get('/show', function (Request $request) use ($app) {
    // Calculate the offset from the page sent by query string and fetch the tree
    $page   = $request->get('p');
    $offset = ControllerHelper::calculateOffset($page, $app['rootsToFech']);
    $trees  = $app['mapper.item']->fetchShows($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers a specific item page along all the comments
$app->get('/item/{id}', function ($id) use ($app) {
    $tree = $app['mapper.item']->fetchArrayItemsTree($id);

    return $app['twig']->render('tree-rows.html.twig', [
        'trees' => [$tree[0]],
    ]);
});






$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
