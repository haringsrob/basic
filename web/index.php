<?php
session_start();

use haringsbe\Page;

require_once '../vendor/autoload.php';
require_once '../settings.php';

$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = array_filter(explode('/', $path_only));

$page = new Page($segments, $settings['exception_words']);

$loader = new Twig_Loader_Filesystem('../theme');
$twig = new Twig_Environment(
    $loader,
    [
        'cache' => '../compilation_cache',
    ]
);

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

$variables = [
    'css_path' => '/assets',
    'js_path' => '/assets',
    'img_path' => '/images',
    'page_title' => $page->getPageHtmlTitle(),
    'primary_segment' => $page->getParentPage(),
    'segment' => $page->getCurrentPage(),
    'segment_title' => $page->getPageTitle(),
    'breadcrumbs' => $page->getBreadcrumbs(),
    'canonical' => $page->getCanonical(),
];

try {
    if (in_array(
        $page->getCurrentPage(),
        $settings['twig_no_cache_pages'],
        true
    )) {
        $variables['csrf_token'] = $easyCSRF->generate('form_token');
        $twig->enableAutoReload();
    }
    if ($settings['debug']) {
        $twig->enableAutoReload();
    }
    echo $twig->render(
        'pages/'.implode('/', $page->getSegments()).'.twig',
        $variables
    );
} catch (Exception $e) {
    $variables['page_title'] = 'Page not found';
    $variables['segment_title'] = 'Page not found';
    $variables['breadcrumbs'] = [];
    $variables['breadcrumbs'][] = [
        'path' => '/',
        'title' => 'Home',
    ];

    echo $twig->render('404.twig', $variables);
}

