<?php

namespace AreWebs\JetVuetify;

use Illuminate\Support\ServiceProvider;

class JetVuetifyServiceProvider extends ServiceProvider
{
  public function register()
  {
    
  }

  public function boot()
  {
    $this->configurePublishing();
    $this->configureCommands();
  }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
      if (! $this->app->runningInConsole()) {
        return;
      }
      
      $this->publishes([
          __DIR__.'/../stubs/resources/js/Pages/Auth' => resource_path('js/Pages/Auth'),
      ], 'jet-vuetify-auth-pages');
    }

    /**
     * Configure the commands offered by the application.
     *
     * @return void
     */
    protected function configureCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
