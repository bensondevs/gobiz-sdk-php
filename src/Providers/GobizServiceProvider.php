<?php

namespace BensonDevs\Gobiz\Providers;

use Illuminate\Support\ServiceProvider;

class GobizServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
        	$this->registerEnvVariables();
            $this->registerConfig();
        }
    }

    /**
     * Register config to project config folder
     * 
     * @return void
     */
    public function registerConfig()
    {
    	$this->publishes([
    		__DIR__ . '/../../config/gobiz.php' => config_path('gobiz.php'),
    	], 'config');
    }

    /**
     * Register env variables to the project ENV file.
     * 
     * @return void
     */
    public function registerEnvVariables()
    {
        // Get the env path
        $envPath = base_path('.env');
        if (! file_exists($envPath)) {
            return;
        }

        // Get the env content
        $envContents = file_get_contents($envPath);

        // Put added vars to the content
        $addedVars = [
            'GOBIZ_CLIENT_ID' => '',
            'GOBIZ_CLIENT_SECRET' => '',
            'GOBIZ_APP_ENV' => 'local',
            'GOBIZ_API_SANDBOX_BASE_URL' => 'https://api.sandbox.gobiz.co.id/',
            'GOBIZ_API_BASE_URL' => 'https://api.gobiz.co.id/',
            'GOBIZ_API_SANDBOX_OAUTH_URL' => 'https://integration-goauth.gojekapi.com',
            'GOBIZ_API_OAUTH_URL' => 'https://accounts.go-jek.com',
            'GOBIZ_ENTERPRISE_ID' => '',
            'GOBIZ_CODE' => '',
            'GOBIZ_REDIRECT_URI' => '',
        ];
        $envContents .= "\n";
        foreach ($addedVars as $addedVar => $value) {
            $envContents .= $addedVar . '=' . $value;
        }

        // Replace the env content with new one
        file_put_contents($envPath, $envContents);
    }

    /**
     * Check if package is running under Lumen application
     * 
     * @return bool
     */
    protected function isLumen()
    {
    	return str_contains($this->app->version(), 'Lumen') === true;
    }
}