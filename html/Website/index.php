<?php

namespace Website;

use \lib\output\html\WebPage;

class index extends WebPage
{
    public $definition = [
        'NAME' => 'index',
        'TYPE' => 'HTML',
        'MODEL' => null,
        'CACHING' => ['TYPE' => 'NO-CACHE'],
        'ENCODING' => 'utf8',
        'LAYOUT' => ['/index.wid'],
        'PERMISSIONS' => ['_PUBLIC_'],
        'FIELDS' => [],
        'PATH' => '/index',
        'WIDGETPATH' => [
            '/output/html/Widgets',
            '/',
        ],
    ];
}
