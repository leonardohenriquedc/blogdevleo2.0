<?php

namespace App\Http\Controllers;

use App\Services\Seo\BlogPosts;
use App\Services\Seo\SeoResolver;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;

class SeoController extends Controller
{
    public function __construct(
        protected BlogPosts $posts,
        protected SeoResolver $resolver,
    ) {}

    /**
     * Generate the sitemap with all public, indexable URLs.
     */
    public function sitemap(): Response
    {
        return ResponseFacade::view('seo.sitemap', [
            'urlset' => $this->sitemapUrlset(),
        ])->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Generate the llms.txt file that describes the site for LLMs.
     */
    public function llmsTxt(): Response
    {
        return ResponseFacade::view('seo.llms', [
            'siteName' => config('app.name'),
            'siteUrl' => rtrim($this->resolver->absolute('/'), '/'),
            'posts' => $this->posts->all(),
        ])->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Build the list of <url> entries for the sitemap.
     *
     * @return array<int, array{loc: string, lastmod: ?string, priority: string}>
     */
    protected function sitemapUrlset(): array
    {
        $urls = [
            $this->sitemapEntry('/', '0.9'),
        ];

        foreach ($this->posts->all() as $post) {
            $urls[] = $this->sitemapEntry(
                '/get/'.$post['slug'],
                '0.8',
                $post['date'],
            );
        }

        return $urls;
    }

    /**
     * Normalize a single sitemap entry into the expected shape.
     *
     * @return array{loc: string, lastmod: ?string, priority: string}
     */
    protected function sitemapEntry(string $segments, string $priority, ?string $lastmod = null): array
    {
        return [
            'loc' => htmlspecialchars($this->resolver->absolute($segments), ENT_XML1 | ENT_QUOTES, 'UTF-8'),
            'lastmod' => $lastmod,
            'priority' => $priority,
        ];
    }
}
