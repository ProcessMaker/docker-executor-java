<?php

namespace ProcessMaker\Package\DockerExecutorJava;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Models\ScriptExecutor;
use ProcessMaker\Package\DockerExecutorJava\Listeners\PackageListener;
use ProcessMaker\Package\Packages\Events\PackageEvent;
use ProcessMaker\Traits\PluginServiceProviderTrait;

class DockerExecutorJavaServiceProvider extends ServiceProvider
{
    use PluginServiceProviderTrait;

    const version = '1.0.0'; // Required for PluginServiceProviderTrait

    public function register()
    {
    }

    /**
     * After all service provider's register methods have been called, your boot method
     * will be called. You can perform any initialization code that is dependent on
     * other service providers at this time.  We've included some example behavior
     * to get you started.
     *
     * See: https://laravel.com/docs/5.6/providers#the-boot-method
     */
    public function boot()
    {
        Artisan::command('docker-executor-java:install', function () {
            ScriptExecutor::install([
                'language' => 'java',
                'title' => 'Java Executor',
                'description' => 'Default Java Executor',
            ]);

            // Build the instance image. This is the same as if you were to build it from the admin UI
            Artisan::call('processmaker:build-script-executor java');

            // Restart the workers so they know about the new supported language
            Artisan::call('horizon:terminate');
        });

        config(['script-runners.java' => [
            'name' => 'Java',
            'mime_type' => 'application/java',
            'package_path' => __DIR__ . '/..',
            'package_version' => self::version,
            'runner' => 'JavaRunner',
            'options' => [
                'invokerPackage' => 'ProcessMaker_Client',
                'modelPackage' => 'ProcessMaker_Model',
                'apiPackage' => 'ProcessMaker_Api',
            ],
            'init_dockerfile' => [
                'ARG SDK_DIR',
                'COPY $SDK_DIR /opt/executor/sdk-java',
                'WORKDIR /opt/executor/sdk-java',
                'RUN mvn clean install',
                'WORKDIR /opt/executor',
            ],
        ]]);

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
