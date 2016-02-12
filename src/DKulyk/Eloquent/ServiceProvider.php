<?php

namespace DKulyk\Eloquent;

use DKulyk\Eloquent\Properties\Factory;
use Illuminate\Foundation\Application;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected function configPath()
    {
        return __DIR__.'/../../../config/eloquent-extra.php';
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'eloquent-extra');

        $this->app->bind(
            'command.eloquent-extra.logging-table',
            function (Application $app) {
                return new Logging\TableCommand(
                    $app->make('migration.creator'),
                    $app->make('composer')
                );
            }, true
        );

        $this->app->bind(
            'command.eloquent-extra.properties-table',
            function (Application $app) {
                return new Properties\MakeMigrationCommand(
                    $app->make('migration.creator'),
                    $app->make('composer')
                );
            }, true
        );

        $this->commands('command.eloquent-extra.logging-table');
        $this->commands('command.eloquent-extra.properties-table');

        $types = (array) $this->app->make('config')->get('eloquent-extra.property_types', []);
        foreach ($types as $type => $class) {
            Factory::registerType($type, $class);
        }
    }

    public function boot()
    {
        $configPath = __DIR__.'/../config/eloquent-extra.php';
        $this->publishes([$configPath => $this->app->make('path.config').'/eloquent-extra.php'], 'config');
    }
}
