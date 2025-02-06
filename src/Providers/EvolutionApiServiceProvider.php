<?php

namespace Brunojsbr\EvolutionApi\Providers;

use Illuminate\Support\ServiceProvider;
use Brunojsbr\EvolutionApi\Services\EvolutionApiService;

class EvolutionApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registra o arquivo de configuração
        $this->mergeConfigFrom(
            __DIR__.'/../../config/evolution-whatsapp.php', 'evolution-whatsapp'
        );

        // Registra o serviço principal como singleton
        $this->app->singleton('evolution-api', function ($app) {
            return new EvolutionApiService(
                config('evolution-whatsapp.api.url'),
                config('evolution-whatsapp.api.key'),
                config('evolution-whatsapp.api.instance_name')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publica o arquivo de configuração
        $this->publishes([
            __DIR__.'/../../config/evolution-whatsapp.php' => config_path('evolution-whatsapp.php'),
        ], 'evolution-whatsapp-config');

        // Publica as migrations
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
        ], 'evolution-whatsapp-migrations');

        // Carrega as migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Carrega as rotas
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        // Carrega as views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'evolution-whatsapp');

        // Publica as views
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/evolution-whatsapp'),
        ], 'evolution-whatsapp-views');

        // Registra os comandos
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Adicione seus comandos aqui
            ]);
        }
    }
}
