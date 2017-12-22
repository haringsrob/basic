<?php

/**
 * Settings configuration.
 *
 * This defines your basic site settings that will be used in your website.
 */

$settings = [
    // If true, it will avoid caching the pages. Must be false on production.
    'debug' => true,
    // site_name will be in your page title by default.
    'site_name' => 'Basic',
    // base url is used to generate your sitemap. Without a trailing /.
    'base_url' => 'http://yoursiteurl.com',
    // Css assets: A list of css file, relative to your project root.
    'css_assets' => [
        '/assets/css/style.css',
    ],
    // JS assets: A list of css file, relative to your project root.
    'js_assets' => [
        '/assets/js/script.js',
    ],
    // Exceptions words... By default we use the path autodiscovery to know what
    // twig file we serve, if there are dashes in these files, we use them as
    // spaces: my-page, will have the title: My page.
    // However in some cases this should not happen, those words can be added
    // to this list to avoid that issue.
    'exception_words' => [
        'e-commerce',
    ],
    // Cache exceptions. This avoids twig from caching a page. Usefull on form
    // pages with csfr protection.
    'twig_no_cache_pages' => [
        'contact',
    ],
];
