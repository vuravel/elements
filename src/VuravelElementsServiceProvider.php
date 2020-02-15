<?php

namespace Vuravel\Elements;

use Illuminate\Support\ServiceProvider;
use Vuravel\Dashboard\Directory;

use Illuminate\Support\Facades\Blade;

class VuravelElementsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');

        Blade::directive('pushonce', function ($expression) {
            [$pushName, $pushSub] = explode(':', trim(substr($expression, 1, -1)));
            $key = '__pushonce_'.str_replace('-', '_', $pushName).'_'.str_replace('-', '_', $pushSub);
            return "<?php if(! isset(\$__env->{$key})): \$__env->{$key} = 1; \$__env->startPush('{$pushName}'); ?>";
        });
        Blade::directive('endpushonce', function () {
            return '<?php $__env->stopPush(); endif; ?>';
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'vuravel-elements');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
