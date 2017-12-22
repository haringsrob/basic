<?php

namespace haringsbe;

/**
 * This class handles the segments and provides helper functions.
 *
 * @package haringsbe
 */
class Page
{

    /**
     * @var array
     */
    private $segments;

    /**
     * @var array
     */
    private $exception_words;

    public function __construct(array $segments, array $exception_words)
    {
        if (empty($segments)) {
            $segments[] = 'home';
        }

        $this->exception_words = $exception_words;
        $this->segments = $segments;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function getPageTitle(): string
    {
        $last_segment = end($this->segments);
        if (!\in_array($last_segment, $this->exception_words, true)) {
            $last_segment = str_replace('-', ' ', $last_segment);
        }

        return ucfirst($last_segment);
    }

    public function getPageHtmlTitle(): string
    {
        $parts = [];
        $parts[] = $this->getPageTitle();

        if (\count($this->segments) >= 2) {
            $reversed = array_reverse($this->segments);
            array_shift($reversed);
            foreach ($reversed as $segment) {
                $parts[] = ucfirst($segment);
            }
        }

        return implode(' | ', $parts);
    }

    public function getCurrentPage(): string
    {
        return end($this->segments);
    }

    public function getParentPage(): string
    {
        return reset($this->segments);
    }

    public function getBreadcrumbs(): array
    {
        $reversed = array_reverse($this->segments);
        array_shift($reversed);

        $breadcrumbs = [];
        foreach ($reversed as $index => $item) {
            $foo = \array_slice($reversed, $index, \count($reversed) - $index);
            $breadcrumb_path = '/'.implode('/', array_reverse($foo));

            $breadcrumbs[] = [
                'path' => $breadcrumb_path,
                'title' => ucfirst($item),
            ];
        }
        $breadcrumbs[] = [
            'path' => '/',
            'title' => 'Home',
        ];

        return array_reverse($breadcrumbs);
    }

    public function getCanonical(): string
    {
        return $this->getSiteUrl().implode('/', $this->segments);
    }

    private function getSiteUrl(): string
    {
        return 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['HTTP_HOST'].'/';
    }

}
