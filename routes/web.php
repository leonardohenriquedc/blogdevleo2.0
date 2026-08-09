<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/get/{name}', "App\Http\Controllers\BlogsController@get")->name('get');

Route::get('/get/', "App\Http\Controllers\BlogsController@get_all_names")->name('get');

// --- SEO endpoints ---
Route::get('/sitemap.xml', "App\Http\Controllers\SeoController@sitemap")->name('seo.sitemap');
Route::get('/llms.txt', "App\Http\Controllers\SeoController@llmsTxt")->name('seo.llms');
