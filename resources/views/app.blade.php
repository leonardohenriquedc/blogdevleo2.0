<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $seo?->title ?? config('app.name', 'blogdevleo.com') }}</title>

        <meta name="description" content="{{ $seo?->description ?? '' }}">
        <meta name="robots" content="{{ $seo->noindex ? 'noindex, follow' : ($seo?->robots ?? 'index, follow') }}">

        @if ($seo?->canonical)
            <link rel="canonical" href="{{ $seo->canonical }}">
        @endif

        {{-- Open Graph --}}
        <meta property="og:title" content="{{ $seo?->title ?? '' }}">
        <meta property="og:description" content="{{ $seo?->description ?? '' }}">
        <meta property="og:type" content="{{ $seo?->ogType ?? 'website' }}">
        <meta property="og:url" content="{{ $seo?->canonical ?? \Illuminate\Support\Facades\URL::current() }}">
        <meta property="og:site_name" content="{{ config('seo.site_name', 'blogdevleo') }}">
        @if ($seo?->ogImage)
            <meta property="og:image" content="{{ $seo->ogImage }}">
        @endif

        {{-- Twitter / X Cards --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seo?->title ?? '' }}">
        <meta name="twitter:description" content="{{ $seo?->description ?? '' }}">
        @if ($seo?->ogImage)
            <meta name="twitter:image" content="{{ $seo->ogImage }}">
        @endif

        @if ($seo?->jsonLdData() && ! empty($seo->jsonLdData()))
            <script type="application/ld+json">{!! json_encode($seo->jsonLdData(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endif

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/main.js'])
    </head>
    <body class="font-sans antialiased">
        <div id="app"></div>
    </body>
</html>
