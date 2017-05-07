<?php

namespace Chali5124\LaravelH5p;

class LaravelH5pServiceProvider extends \Illuminate\Support\ServiceProvider {

    protected $defer = false;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Chali5124\LaravelH5p\Events\H5pEvent' => [
            'Chali5124\LaravelH5p\Listeners\H5pNotification',
        ],
    ];

    public function register() {

        $this->app->singleton('LaravelH5p', function ($app) {
            $LaravelH5p = new LaravelH5p($app);
            return $LaravelH5p;
        });

        $this->app->bind('H5pHelper', function() {
            return new Chali5124\LaravelH5p\Helpers\H5pHelper();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/../routes/laravel-h5p.php');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'laravel-h5p');
        $this->loadViewsFrom(__DIR__ . '/../views', 'laravel-h5p');

        $this->mergeConfigFrom(
                __DIR__ . '/../config/laravel-h5p.php', 'laravel-h5p'
        );

        $this->publishes([
            __DIR__ . '/../seeds/H5pUserSeeder.php' => database_path('seeds/H5pUserSeeder.php')
                ], 'seeds');

        $this->publishes([
            __DIR__ . '/../config/laravel-h5p.php' => config_path('laravel-h5p.php')
                ], 'config');

        $this->publishes([
            __DIR__ . '/../views/layouts/app.blade.php' => resource_path('views/layouts/app.blade.php')
                ], 'resources');

        $this->publishes([
            __DIR__ . '/../assets' => public_path('vendor/laravel-h5p'),
            __DIR__ . '/../../vendor/h5p/h5p-core/fonts' => public_path('vendor/h5p/h5p-core/fonts'),
            __DIR__ . '/../../vendor/h5p/h5p-core/images' => public_path('vendor/h5p/h5p-core/images'),
            __DIR__ . '/../../vendor/h5p/h5p-core/js' => public_path('vendor/h5p/h5p-core/js'),
            __DIR__ . '/../../vendor/h5p/h5p-core/styles' => public_path('vendor/h5p/h5p-core/styles'),
            __DIR__ . '/../../vendor/h5p/h5p-editor/ckeditor' => public_path('vendor/h5p/h5p-editor/ckeditor'),
            __DIR__ . '/../../vendor/h5p/h5p-editor/images' => public_path('vendor/h5p/h5p-editor/images'),
            __DIR__ . '/../../vendor/h5p/h5p-editor/language' => public_path('vendor/h5p/h5p-editor/language'),
            __DIR__ . '/../../vendor/h5p/h5p-editor/libs' => public_path('vendor/h5p/h5p-editor/libs'),
            __DIR__ . '/../../vendor/h5p/h5p-editor/scripts' => public_path('vendor/h5p/h5p-editor/scripts'),
            __DIR__ . '/../../vendor/h5p/h5p-editor/styles' => public_path('vendor/h5p/h5p-editor/styles'),
                ], 'public');
    }

}
