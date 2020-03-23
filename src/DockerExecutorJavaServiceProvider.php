<?php
namespace ProcessMaker\Package\DockerExecutorJava;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Traits\PluginServiceProviderTrait;
use ProcessMaker\Package\Packages\Events\PackageEvent;
use ProcessMaker\Package\WebEntry\Listeners\PackageListener;
use ProcessMaker\Models\ScriptExecutor;

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
        \Artisan::command('docker-executor-java:install', function () {
            $scriptExecutor = ScriptExecutor::install([
                'language' => 'java',
                'title' => 'Java Executor',
                'description' => 'Default Java Executor',
            ]);

            // Build the instance image. This is the same as if you were to build it from the admin UI
            \Artisan::call('processmaker:build-script-executor java');
            
            // Restart the workers so they know about the new supported language
            \Artisan::call('horizon:terminate');
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
            'init_dockerfile' => [
                "ARG SDK_DIR",
                'COPY $SDK_DIR /opt/executor/sdk-java',
                'WORKDIR /opt/executor/sdk-java',
                'RUN mvn clean install',
                'WORKDIR /opt/executor',
            ],
            'package_path' => __DIR__ . '/..'
        ];
        config(['script-runners.java' => $config]);

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
