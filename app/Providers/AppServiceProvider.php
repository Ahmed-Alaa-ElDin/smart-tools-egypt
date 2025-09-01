<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use App\Services\Front\meta\MetaPixelService;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('meta-pixel', fn() => new MetaPixelService());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ########### todo :: For Production ############
        // $this->app->bind('path.public', function () {
        //     return base_path() . '/../public_html';
        // });
        // $this->app->usePublicPath(__DIR__ . '/../../../public_html'); For V10

        // Macro for pagination in Collection
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            $items = $this->values();

            return new LengthAwarePaginator(
                $items->forPage($page, $perPage),
                $total ?: $items->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
