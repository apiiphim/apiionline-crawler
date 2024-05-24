<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'ApiiOnline\Crawler\ApiiOnlineCrawler\Controllers',
], function () {
    Route::get('/plugin/apii-crawler', 'CrawlController@showCrawlPage');
    Route::get('/plugin/apii-crawler/options', 'CrawlerSettingController@editOptions');
    Route::put('/plugin/apii-crawler/options', 'CrawlerSettingController@updateOptions');
    Route::get('/plugin/apii-crawler/fetch', 'CrawlController@fetch');
    Route::post('/plugin/apii-crawler/crawl', 'CrawlController@crawl');
    Route::post('/plugin/apii-crawler/get-movies', 'CrawlController@getMoviesFromParams');
});
