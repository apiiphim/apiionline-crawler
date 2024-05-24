<?php

namespace ApiiOnline\Crawler\ApiiOnlineCrawler;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as SP;
use ApiiOnline\Crawler\ApiiOnlineCrawler\Console\CrawlerScheduleCommand;
use ApiiOnline\Crawler\ApiiOnlineCrawler\Option;

class ApiiOnlineCrawlerServiceProvider extends SP
{
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [];
    }

    public function register()
    {

        config(['plugins' => array_merge(config('plugins', []), [
            'hacoidev/apii-crawler' =>
            [
                'name' => 'ApiiOnline Crawler',
                'package_name' => 'hacoidev/apii-crawler',
                'icon' => 'la la-hand-grab-o',
                'entries' => [
                    ['name' => 'Crawler', 'icon' => 'la la-hand-grab-o', 'url' => backpack_url('/plugin/apii-crawler')],
                    ['name' => 'Option', 'icon' => 'la la-cog', 'url' => backpack_url('/plugin/apii-crawler/options')],
                ],
            ]
        ])]);

        config(['logging.channels' => array_merge(config('logging.channels', []), [
            'apii-crawler' => [
                'driver' => 'daily',
                'path' => storage_path('logs/hacoidev/apii-crawler.log'),
                'level' => env('LOG_LEVEL', 'debug'),
                'days' => 7,
            ],
        ])]);

        config(['apii.updaters' => array_merge(config('apii.updaters', []), [
            [
                'name' => 'ApiiOnline Crawler',
                'handler' => 'ApiiOnline\Crawler\ApiiOnlineCrawler\Crawler'
            ]
        ])]);
    }

    public function boot()
    {
        $this->commands([
            CrawlerScheduleCommand::class,
        ]);

        $this->app->booted(function () {
            $this->loadScheduler();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'apii-crawler');
    }

    protected function loadScheduler()
    {
        $schedule = $this->app->make(Schedule::class);
        $schedule->command('apii:plugins:apii-crawler:schedule')->cron(Option::get('crawler_schedule_cron_config', '*/10 * * * *'))->withoutOverlapping();
    }
}
