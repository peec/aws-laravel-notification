<?php

namespace NotificationChannels\AWS;

use Illuminate\Support\ServiceProvider;

class AWSSMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.



        $this->app->when(AWSSMSChannel::class)
            ->needs(AWSSMS::class)
            ->give(function () {
                $config = $this->app['config']['services.awssms'];
                return new AWSSMS(
                    $this->app->make(\Aws\Sns\SnsClient::class, [
                        'credentials' => array(
                            'key' => $config['key'],
                            'secret' => $config['secret'],
                        ),
                        'region' => $config['region'], // < your aws from SNS Topic region
                        'version' => 'latest'
                    ]),
                    $config['from']
                );
            });

    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
