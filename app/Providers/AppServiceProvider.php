<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom collection macro to pick multiple keys from a collection
        Collection::macro('pick', function (... $columns) {
            return $this->map(function ($item, $key) use ($columns) {
                $data = [];
                foreach ($columns as $column) {
                    $data[$column] = $item[$column] ?? null;
                }

                return $data;
            });
        });
    }
}
