<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SEO Base URL
    |--------------------------------------------------------------------------
    |
    | The absolute, HTTPS base URL used for canonical URLs, Open Graph, the
    | sitemap and llms.txt. It defaults to the production domain. Define
    | SEO_URL in your .env to override it for other environments.
    |
    */

    'base_url' => env('SEO_URL', 'https://blogdevleo.com'),

    /*
    |--------------------------------------------------------------------------
    | Default Open Graph image
    |--------------------------------------------------------------------------
    |
    | Used as the og:image / twitter:image fallback when a page or article
    | does not provide its own image.
    |
    */

    'og_image' => env('SEO_OG_IMAGE', 'apple-touch-icon.png'),

    /*
    |--------------------------------------------------------------------------
    | Meta defaults
    |--------------------------------------------------------------------------
    |
    | Defaults applied to the site-wide pages (home, fallback routes).
    |
    */

    'site_name' => 'blogdevleo',

    'home' => [
        'title' => 'blogdevleo — Desenvolvimento e Tecnologia',
        'description' => 'Artigos sobre programação, projetos e experiências de desenvolvimento escritos por Leonardo Henrique.',
    ],

    'robots_default' => 'index, follow',
];
