<?php

namespace App\Services\Seo;

/**
 * Immutable collection of resolved SEO values for a single page. It is
 * shared to the Blade shell so the initial HTML carries the right tags.
 */
class SeoMeta
{
    /**
     * @param  array<string, mixed>  $jsonLd
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $robots,
        public readonly string $segments,
        public readonly ?string $ogImage = null,
        public readonly ?string $canonical = null,
        public readonly string $ogType = 'website',
        public readonly bool $noindex = false,
        public readonly array $jsonLd = [],
    ) {}

    /**
     * Build the JSON-LD block, keeping only non-null values.
     *
     * @return array<string, mixed>
     */
    public function jsonLdData(): array
    {
        return array_filter(
            $this->jsonLd,
            fn (mixed $value): bool => $value !== null,
        );
    }

    /**
     * Whether a page carries an Article JSON-LD schema.
     */
    public function isArticle(): bool
    {
        return $this->ogType === 'article';
    }
}
