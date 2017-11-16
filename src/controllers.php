<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use GuzzleHttp\Client as HttpClient;

//Request::setTrustedProxies(array('127.0.0.1'));

// Common code
$itemMapper = new ItemMapper($app['baseUri']);

// The home page
$app->get('/', function () use ($app, $itemMapper) {
    $trees = $itemMapper->fetchTopStories($app['rootsToFech']);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => 1,
    ]);
})
->bind('homepage');


// Delivers the main page with the top stories
$app->get('/news', function (Request $request) use ($app, $itemMapper) {
    // Calculate the offset from the page sent by query string
    $page = $request->get('p');
    $page = is_numeric($page) && (int) $page > 0 ? (int) $page : 1;
    $offset = $app['rootsToFech'] * ($page - 1);

    $trees = $itemMapper->fetchTopStories($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});

// Delivers the main page with the newst stories
$app->get('/newest', function (Request $request) use ($app, $itemMapper) {
    // Calculate the offset from the page sent by query string
    $page = $request->get('p');
    $page = is_numeric($page) && (int) $page > 0 ? (int) $page : 1;
    $offset = $app['rootsToFech'] * ($page - 1);

    $trees = $itemMapper->fetchNewestStories($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers ask main page
$app->get('/ask', function (Request $request) use ($app, $itemMapper) {
    // Calculate the offset from the page sent by query string
    $page = $request->get('p');
    $page = is_numeric($page) && (int) $page > 0 ? (int) $page : 1;
    $offset = $app['rootsToFech'] * ($page - 1);

    $trees = $itemMapper->fetchAskStories($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers jobs main page
$app->get('/jobs', function (Request $request) use ($app, $itemMapper) {
    // Calculate the offset from the page sent by query string
    $page = $request->get('p');
    $page = is_numeric($page) && (int) $page > 0 ? (int) $page : 1;
    $offset = $app['rootsToFech'] * ($page - 1);

    $trees = $itemMapper->fetchJobs($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers Shows main page
$app->get('/show', function (Request $request) use ($app, $itemMapper) {
    // Calculate the offset from the page sent by query string
    $page = $request->get('p');
    $page = is_numeric($page) && (int) $page > 0 ? (int) $page : 1;
    $offset = $app['rootsToFech'] * ($page - 1);

    $trees = $itemMapper->fetchShows($app['rootsToFech'], $offset);

    return $app['twig']->render('rows.html.twig', [
        'trees' => $trees,
        'page'  => $page,
    ]);
});


// Delivers a specific item page along all the comments
$app->get('/item/{id}', function ($id) use ($app, $itemMapper) {
    $tree = $itemMapper->fetchArrayItemsTree($id);

    return $app['twig']->render('tree-rows.html.twig', [
        'trees' => [$tree[0]],
    ]);
});






$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
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
