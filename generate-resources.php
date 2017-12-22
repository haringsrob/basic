<?php

require_once './vendor/autoload.php';
require_once './settings.php';

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use samdark\sitemap\Sitemap;

generate_sitemap($settings);
minify_css($settings);
minify_js($settings);

function generate_sitemap(array $settings)
{
    $sitemap = new Sitemap(__DIR__.'/web/sitemap.xml');

    $pages_path = __DIR__.'/theme/pages';

    $path = realpath($pages_path);

    $objects = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($objects as $name => $object) {
        if (!$object->isDir()) {
            $sitemap->addItem($settings['base_url'].str_replace([
                    $path,
                    '.twig',
                ], '', $name));
        }
    }

    $sitemap->write();
}

function minify_css(array $settings)
{
    $minifier = new CSS();

    foreach ($settings['css_assets'] as $css) {
        $minifier->add($css);
    }
    $minifier->minify(__DIR__.'/web/assets/css.min.css');
}

function minify_js(array $settings)
{
    $minifier = new JS();

    foreach ($settings['js_assets'] as $js) {
        $minifier->add($js);
    }
    $minifier->minify(__DIR__.'/web/assets/js.min.js');
}
