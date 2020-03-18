<?php
namespace ProcessMaker\Package\DockerExecutorJava;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Traits\PluginServiceProviderTrait;
use ProcessMaker\Package\Packages\Events\PackageEvent;
use ProcessMaker\Package\WebEntry\Listeners\PackageListener;

class DockerExecutorJavaServiceProvider extends ServiceProvider
{
    use PluginServiceProviderTrait;

    const version = '0.0.1'; // Required for PluginServiceProviderTrait

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
        \Artisan::command('docker-executor-java:install', function () {
            // Copy the default custom dockerfile to the storage folder
            copy(
                __DIR__ . '/../storage/docker-build-config/Dockerfile-java',
                storage_path("docker-build-config/Dockerfile-java")
            );

            // Restart the workers so they know about the new supported language
            \Artisan::call('horizon:terminate');

            // Build the base image that `executor-instance-php` inherits from
            system("docker build -t processmaker4/executor-java:latest " . __DIR__ . '/..');

            // Build the instance image. This is the same as if you were to build it from the admin UI
            \Artisan::call('processmaker:build-script-executor java');
        });
        
        $config = [
            'name' => 'Java',
            'runner' => 'JavaRunner',
            'mime_type' => 'application/java',
            'image' => env('SCRIPTS_JAVA_IMAGE', 'processmaker4/executor-instance-java:v1.0.0'),
            'options' => [
                'invokerPackage' => "ProcessMaker_Client",
                'modelPackage' => "ProcessMaker_Model",
                'apiPackage' => "ProcessMaker_Api",
            ],
            'init_dockerfile' => "FROM processmaker4/executor-java:latest\nARG SDK_DIR\n",
        ];
        config(['script-runners.java' => $config]);

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
