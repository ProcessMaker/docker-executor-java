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
            // nothing to do here
        });
        
        $config = [
            'name' => 'Java',
            'runner' => 'JavaRunner',
            'mime_type' => 'application/java',
            'image' => env('SCRIPTS_JAVA_IMAGE', 'processmaker4/executor-java'),
            'options' => [
                'invokerPackage' => "ProcessMaker_Client",
                'modelPackage' => "ProcessMaker_Model",
                'apiPackage' => "ProcessMaker_Api",
            ]
        ];
        config(['script-runners.java' => $config]);

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
