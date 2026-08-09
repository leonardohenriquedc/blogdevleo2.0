<?php

use App\Services\Seo\BlogPosts;

it('provides a robots.txt that allows crawling and points at the sitemap', function () {
    $publicPath = public_path('robots.txt');

    expect(file_exists($publicPath))->toBeTrue();

    $content = (string) file_get_contents($publicPath);

    expect($content)->toContain('User-agent: *');
    expect($content)->toContain('Allow: /');
    expect($content)->toContain('Sitemap: https://blogdevleo.com/sitemap.xml');
});

it('serves a valid sitemap.xml with absolute public URLs', function () {
    $response = $this->get('/sitemap.xml');

    $response
        ->assertStatus(200)
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);

    $xml = simplexml_load_string($response->getContent());
    expect($xml)->not->toBeFalse();

    $urls = sitemapLocations($xml);

    // The home page is always present and must be absolute HTTPS.
    expect($urls)->toContain('https://blogdevleo.com/');
    expect(collect($urls)->every(fn ($url) => str_starts_with($url, 'https://blogdevleo.com/')))->toBeTrue();
    expect(collect($urls)->unique()->count())->toBe(collect($urls)->count());
});

it('includes every blog post in the sitemap', function () {
    $posts = app(BlogPosts::class)->all();

    $xml = simplexml_load_string($this->get('/sitemap.xml')->getContent());
    $urls = collect(sitemapLocations($xml));

    foreach ($posts as $post) {
        $expected = 'https://blogdevleo.com/get/'.$post['slug'];
        expect($urls->contains($expected))->toBeTrue();
    }
});

it('serves llms.txt with the site content and article links', function () {
    $response = $this->get('/llms.txt');

    $response
        ->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('blogdevleo', false)
        ->assertSee('https://blogdevleo.com/', false);
});

/**
 * Extract every <loc> from a parsed sitemap SimpleXMLElement.
 *
 * @return array<int, string>
 */
function sitemapLocations(SimpleXMLElement $xml): array
{
    $locations = [];

    foreach ($xml->url as $url) {
        $locations[] = (string) $url->loc;
    }

    return $locations;
}

it('renders SEO meta tags in the initial HTML of the home page', function () {
    $response = $this->get('/')->assertStatus(200);

    $response->assertSee('<title>', false);
    $response->assertSee('og:title', false);
    $response->assertSee('og:description', false);
    $response->assertSee('og:url', false);
    $response->assertSee('og:site_name', false);
    $response->assertSee('twitter:card', false);
    $response->assertSee('<link rel="canonical"', false);
});

it('renders a unique, canonicalized JSON-LD article when a real post is visited', function () {
    $post = app(BlogPosts::class)->all()->first();

    if ($post === null) {
        return $this->markTestSkipped('No blog posts available.');
    }

    $response = $this->get('/get/'.$post['slug'])->assertStatus(200);

    $html = $response->getContent();
    expect($html)->toContain('application/ld+json');
    expect($html)->toContain('"@type":"Article"');
    expect($html)->toContain('https://blogdevleo.com/get/'.$post['slug']);
});

it('keeps serving the article content as JSON to the SPA', function () {
    $post = app(BlogPosts::class)->all()->first();

    if ($post === null) {
        return $this->markTestSkipped('No blog posts available.');
    }

    $this->withHeader('Accept', 'application/json')
        ->get('/get/'.$post['slug'])
        ->assertStatus(200)
        ->assertJsonStructure(['content']);
});

it('applies noindex to unknown article slugs', function () {
    $this->withHeader('Accept', 'text/html')
        ->get('/get/artigo-que-nao-existe')
        ->assertStatus(200)
        ->assertSee('name="robots" content="noindex, follow"', false);
});
