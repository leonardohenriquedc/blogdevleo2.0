<?php

namespace App\Http\Middleware;

use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves per-page SEO meta based on the request path and makes it
 * available to the SPA shell so the initial HTML carries the right
 * <title>, meta description, canonical, Open Graph and JSON-LD tags.
 */
class SeoMetaMiddleware
{
    public function __construct(
        protected SeoResolver $resolver,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        /** @var SeoMeta $seo */
        $seo = $this->resolver->resolve($request->path());

        view()->share('seo', $seo);

        return $next($request);
    }
}
