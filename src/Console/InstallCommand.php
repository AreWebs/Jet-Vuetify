<?php

namespace AreWebs\JetVuetify\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jet-vuetify:install {--teams : Indicates if team support should be installed}
                                                {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the JetVuetify components and resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.3.5', 'laravel/sanctum:^2.6', 'tightenco/ziggy:^1.0');

        // Install NPM packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/inertia' => '^0.8.2',
                '@inertiajs/inertia-vue' => '^0.5.4',
                'portal-vue' => '^2.1.7',
                "sass" => "^1.32.7",
                "sass-loader"=> "^11.0.1",
                'vue' => '^2.5.17',
                'vue-loader' => '^15.9.6',
                'vue-template-compiler' => '^2.6.10',
                "vuetify" => "^2.4.3",
                "vuetify-loader" => "^1.7.2",
                "vuetifyjs-mix-extension" => "^0.0.20",
            ] + $packages;
        });

        // Package Configuration...
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__.'/../../stubs/webpack.config.js', base_path('webpack.config.js'));

         // Directories...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/API'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Auth'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Profile'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Plugins'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        
        (new Filesystem)->deleteDirectory(resource_path('js/Jetstream'));

        // Blade Views...
        copy(__DIR__.'/../../stubs/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        // Inertia Pages...
        copy(__DIR__.'/../../stubs/resources/js/Pages/Dashboard.vue', resource_path('js/Pages/Dashboard.vue'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/PrivacyPolicy.vue', resource_path('js/Pages/PrivacyPolicy.vue'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/TermsOfService.vue', resource_path('js/Pages/TermsOfService.vue'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/Welcome.vue', resource_path('js/Pages/Welcome.vue'));
        
        
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Plugins', resource_path('js/Plugins'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/API', resource_path('js/Pages/API'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/Auth', resource_path('js/Pages/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/Profile', resource_path('js/Pages/Profile'));

        // Assets...
        copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/resources/js/app.js', resource_path('js/app.js'));
        // copy(__DIR__.'/../../stubs/public/css/app.css', public_path('css/app.css'));

        
        if ($this->option('teams')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/Teams', resource_path('js/Pages/Teams'));
        }

        $this->line('');
        $this->info('JetVuetify scaffolding installed successfully.');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');

    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerPackages($packages)
    {
        $composer = $this->option('composer');
        
        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }
}