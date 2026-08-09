<?php

namespace App\Services\Seo;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Reads blog posts from the public markdown files that also back the
 * JSON content endpoint. It is the single source of truth for the post
 * metadata used by the SEO layer (sitemap, llms.txt and article meta).
 */
class BlogPosts
{
    /**
     * The public disk storing the {@code *.md} files.
     */
    protected const DISK = 'public';

    /**
     * Directory (relative to the disk root) that holds the posts.
     */
    protected const BLOG_PATH = 'blogs';

    /**
     * Return every blog post as a normalized data array.
     *
     * @return Collection<int, array{
     *     slug: string,
     *     name: string,
     *     title: string,
     *     author: ?string,
     *     date: ?string,
     *     image: ?string,
     *     description: string,
     *     excerpt: string,
     * }>
     */
    public function all(): Collection
    {
        $posts = collect();

        foreach (Storage::disk(self::DISK)->files(self::BLOG_PATH) as $file) {
            if (Str::endsWith($file, '.md')) {
                $posts->push($this->parse($file));
            }
        }

        return $posts;
    }

    /**
     * Find a single post by its URL slug (with or without the leading
     * "get/" prefix).
     *
     * @return array<string, mixed>|null
     */
    public function find(string $slug): ?array
    {
        $slug = ltrim(strtolower(trim($slug)), '/');
        $slug = str_starts_with($slug, 'get/') ? Str::after($slug, 'get/') : $slug;

        return $this->all()
            ->first(fn (array $post): bool => $post['slug'] === $slug);
    }

    /**
     * Parse a single markdown file into normalized post data.
     *
     * @return array{
     *     slug: string,
     *     name: string,
     *     title: string,
     *     author: ?string,
     *     date: ?string,
     *     image: ?string,
     *     description: string,
     *     excerpt: string,
     * }
     */
    protected function parse(string $file): array
    {
        $name = Str::replaceLast('.md', '', basename($file));
        $slug = $this->slug($name);

        $contents = Storage::disk(self::DISK)->get($file) ?? '';

        $frontmatter = $this->frontmatter($contents);
        $body = $this->stripFrontmatter($contents);

        $title = $this->stringify($frontmatter['title'] ?? null) ?: $this->titleFromSlug($name);

        return [
            'slug' => $slug,
            'name' => $name,
            'title' => $title,
            'author' => $this->nullableString($frontmatter['author'] ?? null),
            'date' => $this->normalizeDate($frontmatter['date'] ?? null),
            'image' => $this->nullableString($frontmatter['image'] ?? null),
            'description' => $this->stringify($frontmatter['description'] ?? null),
            'excerpt' => $this->excerpt($body),
        ];
    }

    /**
     * Turn a file name into the URL slug used across the app.
     */
    protected function slug(string $name): string
    {
        return strtolower(trim($name));
    }

    /**
     * Derive a readable title from a slug when no title is declared.
     */
    protected function titleFromSlug(string $name): string
    {
        return Str::of($name)
            ->replace(['-', '_'], ' ')
            ->title()
            ->toString();
    }

    /**
     * Parse the YAML-ish frontmatter block at the top of the file.
     *
     * @return array<string, mixed>
     */
    protected function frontmatter(string $contents): array
    {
        $lines = preg_split('/\R/', $contents) ?: [];

        if (! isset($lines[0]) || trim($lines[0]) !== '---') {
            return [];
        }

        $data = [];

        foreach (array_slice($lines, 1) as $line) {
            if (trim($line) === '---') {
                break;
            }

            $pos = strpos($line, ':');

            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            $data[$key] = trim($value, " \t\n\r\0\x0B\"'");
        }

        return $data;
    }

    /**
     * Remove the leading frontmatter block from the raw markdown body.
     */
    protected function stripFrontmatter(string $contents): string
    {
        $lines = preg_split('/\R/', $contents) ?: [];

        if (! isset($lines[0]) || trim($lines[0]) !== '---') {
            return $contents;
        }

        foreach (array_slice($lines, 1) as $index => $line) {
            if (trim($line) === '---') {
                return implode("\n", array_slice($lines, $index + 2));
            }
        }

        return $contents;
    }

    /**
     * Build a truncated plain-text excerpt from the markdown body.
     */
    protected function excerpt(string $markdown): string
    {
        // Drop heading lines, links and stray HTML before collapsing.
        $plain = preg_replace('/^#{1,6}\s+.*$/m', '', $markdown) ?? $markdown;
        $plain = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $plain) ?? $plain;
        $plain = trim(preg_replace('/\R+/', ' ', $plain) ?? $plain);
        $plain = trim(preg_replace('/\s+/', ' ', $plain) ?? $plain);

        $sentence = '';

        // Take the first sentence-like chunk.
        foreach (preg_split('/(?<=[.!?])\s+/', $plain) ?: [] as $chunk) {
            $chunk = trim($chunk);

            if ($chunk === '') {
                continue;
            }

            $sentence = $chunk;
            break;
        }

        if ($sentence === '') {
            return '';
        }

        return Str::limit(html_entity_decode(trim(strip_tags($sentence))), 155);
    }

    protected function normalizeDate(?string $date): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        return (string) Str::of($date)->trim();
    }

    protected function stringify(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        return trim((string) $value);
    }

    protected function nullableString(mixed $value): ?string
    {
        $string = $this->stringify($value);

        return $string === '' ? null : $string;
    }
}
