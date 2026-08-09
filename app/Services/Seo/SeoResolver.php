<?php

namespace App\Services\Seo;

use Illuminate\Support\Str;

/**
 * Resolves the SEO meta for a page from the current request path. The
 * shell render is shared, so this works for any route that renders the
 * SPA shell (including the home page).
 */
class SeoResolver
{
    public function __construct(
        protected BlogPosts $posts,
    ) {}

    /**
     * Resolve SEO meta for a given request path (e.g. "/" or "/get/slug").
     */
    public function resolve(string $path): SeoMeta
    {
        $path = '/'.ltrim($path, '/');

        if ($path === '/') {
            return $this->home();
        }

        if (Str::startsWith($path, '/get/')) {
            return $this->article(Str::after($path, '/get/'));
        }

        return $this->page($path);
    }

    /**
     * Meta for the home page.
     */
    public function home(): SeoMeta
    {
        $title = config('seo.home.title');
        $description = config('seo.home.description');

        return new SeoMeta(
            title: $title,
            description: $description,
            robots: config('seo.robots_default'),
            segments: '/',
            canonical: $this->absolute('/'),
            ogImage: $this->ogImage(),
            jsonLd: [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => config('seo.site_name'),
                'url' => $this->absolute('/'),
            ],
        );
    }

    /**
     * Meta for an article (blog post).
     */
    public function article(string $slug): SeoMeta
    {
        $post = $this->posts->find($slug);

        if ($post === null) {
            return $this->notFound($slug);
        }

        $canonicalSlug = '/get/'.$post['slug'];
        $title = $post['title'];
        $description = $post['description'] ?: $post['excerpt'];
        $image = $post['image'] ? $this->absolute($post['image']) : $this->ogImage();
        $date = $post['date'] ?: null;

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post['title'],
            'author' => $post['author']
                ? ['@type' => 'Person', 'name' => $post['author']]
                : null,
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('seo.site_name'),
            ],
            'datePublished' => $date,
            'mainEntityOfPage' => $this->absolute($canonicalSlug),
            'image' => $image,
        ];

        if ($description !== '') {
            $jsonLd['description'] = $description;
        }

        return new SeoMeta(
            title: $title.' — blogdevleo',
            description: $description !== '' ? $description : null,
            robots: config('seo.robots_default'),
            segments: $canonicalSlug,
            canonical: $this->absolute($canonicalSlug),
            ogImage: $image,
            ogType: 'article',
            jsonLd: $jsonLd,
        );
    }

    /**
     * Meta for any other public page.
     *
     * The SPA's catch-all route renders unknown single-segment paths as an
     * article reader, so treat them as non-indexable pages to avoid
     * soft-404s being cached by search engines.
     */
    protected function page(string $path): SeoMeta
    {
        $segments = rtrim($path, '/') ?: '/';

        $title = (string) Str::of(basename($segments))
            ->replace(['-', '_'], ' ')
            ->title()
            ->trim();

        return new SeoMeta(
            title: $title !== '' ? $title.' — blogdevleo' : config('seo.home.title'),
            description: null,
            robots: 'noindex, follow',
            segments: $segments,
            canonical: $this->absolute($segments),
            noindex: true,
        );
    }

    /**
     * Meta for a missing article.
     */
    protected function notFound(string $slug): SeoMeta
    {
        return new SeoMeta(
            title: 'Artigo não encontrado — blogdevleo',
            description: null,
            robots: 'noindex, follow',
            segments: '/get/'.$slug,
            noindex: true,
        );
    }

    /**
     * Resolve the site-wide default Open Graph image as an absolute URL.
     */
    protected function ogImage(): string
    {
        return $this->absolute(config('seo.og_image'));
    }

    /**
     * Build an absolute HTTPS URL for a site-relative path.
     */
    public function absolute(string $path): string
    {
        $base = rtrim((string) config('seo.base_url'), '/');
        $path = '/'.ltrim($path, '/');

        return $base.$path;
    }
}
