<?php

namespace Bonnier\Contentful;

use Bonnier\Contentful\ContentManagement\Locales;
use Bonnier\Contentful\ContentManagement\Entries;
use Illuminate\Support\ServiceProvider;

class ContentfulServiceProvider extends ServiceProvider
{
    protected $managementApiKey, $deliveryApiKey, $spaceId;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/contentful.php' => config_path('contentful.php'),
        ]);

        $this->managementApiKey = config('contentful.management_api_key');
        $this->deliveryApiKey = config('contentful.delivery_api_key');
        $this->spaceId = config('contentful.space_id');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Locales::class, function () {
            return new Locales($this->spaceId, $this->managementApiKey);
        });

        $this->app->singleton(Entries::class, function () {
            return new Entries($this->spaceId, $this->managementApiKey);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Locales::class,
            Entries::class,
        ];
    }
}
