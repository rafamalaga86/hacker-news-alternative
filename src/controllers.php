<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use GuzzleHttp\Client as HttpClient;

//Request::setTrustedProxies(array('127.0.0.1'));


$app->get('/', function () use ($app) {
    
    $itemMapper = new ItemMapper($app['scheme'], $app['host'], $app['apiPath']);
    $trees = $itemMapper->fetchNewest(30);

    // Shape
    $entries = array_map(function ($tree) {
        $arrayTree = $tree->nodeToArray();
        $arrayTree['comments'] = $tree->countNodes()-1;
        return $arrayTree;
    }, $trees);

    return $app['twig']->render('index.html.twig', [
        'entries' => $entries,
    ]);
})
->bind('homepage');


$app->get('/item/{id}', function ($id) use ($app) {

    $itemMapper = new ItemMapper($app['scheme'], $app['host'], $app['apiPath']);
    $item = $itemMapper->fetchItem($id);

    var_dump($item);
    die("pfdfddf");

    return $app['twig']->render('index.html.twig', [
        'entries' => $entries
    ]);
});


$app->get('/admin', function () use ($app) {

    $entries = [
        [
            'id' => 15702725,
            'author' => 'ftomassetti',
            'text' => null,
            'title' => 'A Guide to Natural Language Processing (NLP)',
            'time' => '3 hours ago',
            'score' => 153,
            'itemType' => 'story',
            'comments' => 15,
            'url' => 'https://tomassetti.me/guide-natural-language-processing/',
        ],
        [
            'id' => 15702962,
            'author' => 'dnetesn',
            'text' => null,
            'title' => 'The Hidden Science and Tech of the Byzantine Empire',
            'time' => 'yesterday',
            'score' => 41,
            'itemType' => 'story',
            'comments' => 5,
            'url' => 'https://tomassetti.me/guide-natural-language-processing/',
        ],
        [
            'id' => 15701238,
            'author' => 'ahomescu1',
            'text' => null,
            'title' => 'Fearless Concurrency in Firefox Quantum',
            'time' => '2 days ago',
            'score' => 257,
            'itemType' => 'story',
            'comments' => 48,
            'url' => 'https://tomassetti.me/guide-natural-language-processing/',
        ],
        [
            'id' => 15703187,
            'author' => 'adventured',
            'text' => null,
            'title' => "The Shock of Sweden's Housing Market Is Hitting the Country's Currency",
            'time' => '3 minutes ago',
            'score' => 22,
            'itemType' => 'story',
            'comments' => 6,
            'url' => 'https://tomassetti.me/guide-natural-language-processing/',
        ]
    ];

    return $app['twig']->render('index.html.twig', [
        'entries' => $entries
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
