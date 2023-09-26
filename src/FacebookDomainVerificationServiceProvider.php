<?php

namespace Alilor\FacebookDomainVerification;

use App\Models\backend\Plugin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class FacebookDomainVerificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'facebook-domain-verification');


        // Optionally, publish the config file if you want to customize the meta tag content
        $this->publishes([
            __DIR__ . '/../config/facebook-domain-verification.php' => config_path('facebook-domain-verification.php'),
        ], 'facebook-domain-verification-config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $enabled = false;

        if (Schema::hasTable('alilor_plugins')) {
            $plugin_facebook_verification_domain = DB::table('alilor_plugins')->where('name', 'facebook-verification-domain')->select('value')->first();
            if ($plugin_facebook_verification_domain !== null) {
                $facebook_verification_domain = json_decode($plugin_facebook_verification_domain->value, true);
                $enabled = $facebook_verification_domain['active'];
                $content = $facebook_verification_domain['content'];
            }
        }
    
        $this->app['view']->share('enabled', $enabled);
        $this->app['view']->share('content', isset($content) ? $content : null);
    }
}
